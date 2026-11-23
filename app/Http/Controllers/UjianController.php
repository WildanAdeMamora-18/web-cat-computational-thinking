<?php

namespace App\Http\Controllers;

use App\Models\ButirSoal;
use App\Models\HasilTes;
use App\Models\Respons;
use App\Models\SesiTes;
use App\Models\Siswa;
use App\Services\CatEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    public function __construct(protected CatEngineService $catEngine) {}

    public function showLogin()
    {
        return view('siswa.login');
    }

    public function login(Request $request)
    {
        $request->validate(['kode_peserta' => 'required|string']);

        $sesiAktif = SesiTes::where('status', 'aktif')->first();
        if (!$sesiAktif) {
            return back()->withErrors(['kode_peserta' => 'Belum ada sesi tes yang aktif saat ini. Hubungi pengawas.']);
        }

        $siswa = Siswa::where('kode_peserta', strtoupper(trim($request->kode_peserta)))->first();
        if (!$siswa) {
            return back()->withErrors(['kode_peserta' => 'Kode peserta tidak terdaftar. Periksa kembali kartu Anda.']);
        }

        $hasilTes = HasilTes::firstOrCreate(
            ['siswa_id' => $siswa->id, 'sesi_id' => $sesiAktif->id],
            ['theta_akhir' => config('cat.theta_awal'), 'waktu_mulai' => now()]
        );

        if ($hasilTes->status_tes === 'selesai') {
            return back()->withErrors(['kode_peserta' => 'Kode peserta ini sudah menyelesaikan tes.']);
        }

        session(['hasil_tes_id' => $hasilTes->id]);
        return redirect()->route('ujian.petunjuk');
    }

    public function petunjuk()
    {
        $hasilTes = HasilTes::with('siswa')->findOrFail(session('hasil_tes_id'));
        if ($hasilTes->status_tes === 'selesai') {
            return redirect()->route('ujian.selesai');
        }
        return view('siswa.petunjuk', compact('hasilTes'));
    }

    public function tampilSoal()
    {
        $hasilTes = HasilTes::with('butirAktif')->findOrFail(session('hasil_tes_id'));

        if ($hasilTes->status_tes === 'selesai') {
            return redirect()->route('ujian.selesai');
        }

        // Anti-refresh: pilih butir pertama jika belum ada butir aktif & set waktu mulai saat soal #1 dibuka
        if (!$hasilTes->butir_aktif_id) {
            $butirPertama = $this->catEngine->pilihButirBerikutnya($hasilTes);
            if (!$butirPertama) {
                abort(500, 'Bank soal kosong atau belum ada butir yang fit.');
            }
            $hasilTes->update([
                'butir_aktif_id' => $butirPertama->id,
                'waktu_mulai'    => now(),
            ]);
            $hasilTes->load('butirAktif');
        }

        // Hitung sisa detik real-time dari server (Durasi 60 menit = 3600 detik)
        $durasiTotal = 3600;
        $sudahBerjalan = (int) $hasilTes->waktu_mulai->diffInSeconds(now());
        $sisaDetik = max(0, $durasiTotal - $sudahBerjalan);

        // Jika waktu sudah habis saat membuka kembali tab, otomatis akhiri tes
        if ($sisaDetik <= 0) {
            $hasilTes->update([
                'butir_aktif_id'  => null,
                'status_tes'      => 'selesai',
                'alasan_berhenti' => 'WAKTU_HABIS',
                'waktu_selesai'   => now(),
            ]);
            return redirect()->route('ujian.selesai');
        }

        return view('siswa.tes', [
            'soal'      => $hasilTes->butirAktif,
            'nomorUrut' => $hasilTes->jumlah_butir + 1,
            'sisaDetik' => $sisaDetik,
        ]);
    }

    public function submitJawaban(Request $request)
    {
        $request->validate(['jawaban' => 'required|in:A,B,C,D,E']);

        $hasilTes = HasilTes::findOrFail(session('hasil_tes_id'));
        if ($hasilTes->status_tes === 'selesai' || !$hasilTes->butir_aktif_id) {
            return redirect()->route('ujian.selesai');
        }

        DB::transaction(function () use ($hasilTes, $request) {
            $soalAktif = ButirSoal::lockForUpdate()->findOrFail($hasilTes->butir_aktif_id);
            $skor      = ($request->jawaban === $soalAktif->kunci_jawaban) ? 1 : 0;
            $urutan    = $hasilTes->jumlah_butir + 1;

            $responsBaru = Respons::create([
                'hasil_tes_id'    => $hasilTes->id,
                'butir_soal_id'   => $soalAktif->id,
                'urutan'          => $urutan,
                'jawaban_dipilih' => $request->jawaban,
                'skor'            => $skor,
                'theta_setelah'   => $hasilTes->theta_akhir,
            ]);

            $riwayat = Respons::with('butirSoal')
                ->where('hasil_tes_id', $hasilTes->id)
                ->orderBy('urutan')
                ->get();

            $thetaBaru = $this->catEngine->estimasiTheta($riwayat, (float) $hasilTes->theta_akhir);
            $daftarB   = $riwayat->pluck('butirSoal.ukuran_b')->toArray();
            $semBaru   = $this->catEngine->hitungSEM($thetaBaru, $daftarB);

            $responsBaru->update([
                'theta_setelah' => $thetaBaru,
                'sem_setelah'   => $semBaru,
            ]);

            $hasilTes->theta_akhir = $thetaBaru;
            $butirBerikutnya = $this->catEngine->pilihButirBerikutnya($hasilTes);
            $cekStop         = $this->catEngine->cekPenghentian($riwayat, $semBaru, $butirBerikutnya !== null);

            if ($cekStop['berhenti']) {
                $hasilTes->update([
                    'butir_aktif_id'  => null,
                    'theta_akhir'     => $thetaBaru,
                    'sem_akhir'       => $semBaru,
                    'jumlah_butir'    => $urutan,
                    'status_tes'      => 'selesai',
                    'alasan_berhenti' => $cekStop['alasan'],
                    'waktu_selesai'   => now(),
                ]);
            } else {
                $hasilTes->update([
                    'butir_aktif_id' => $butirBerikutnya->id,
                    'theta_akhir'    => $thetaBaru,
                    'sem_akhir'      => $semBaru,
                    'jumlah_butir'   => $urutan,
                ]);
            }
        });

        $hasilTes->refresh();
        return $hasilTes->status_tes === 'selesai'
            ? redirect()->route('ujian.selesai')
            : redirect()->route('ujian.tes');
    }

    // Endpoint pencatat jika siswa keluar/pindah tab saat tes
    public function catatKeluarTab()
    {
        $hasilTes = HasilTes::find(session('hasil_tes_id'));
        if ($hasilTes && $hasilTes->status_tes === 'sedang_mengerjakan') {
            $hasilTes->increment('jumlah_keluar_tab');
            $hasilTes->update(['terakhir_keluar_tab' => now()]);
        }
        return response()->json(['status' => 'logged']);
    }

    public function selesai()
    {
        $hasilTes = HasilTes::with('siswa')->find(session('hasil_tes_id'));
        session()->forget('hasil_tes_id');

        return view('siswa.selesai', compact('hasilTes'));
    }
}
