<?php

namespace App\Http\Controllers;

use App\Models\ButirSoal;
use App\Models\HasilTes;
use App\Models\Respons;
use App\Models\SesiTes;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.siswa');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.siswa');
        }

        return back()->withErrors(['email' => 'Email atau kata sandi admin salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // ==========================================
    // 1. HALAMAN DATA SISWA
    // ==========================================
    public function siswa(Request $request)
    {
        $sesiAktif = SesiTes::where('status', 'aktif')->first();

        $query = Siswa::with(['hasilTes' => function ($q) use ($sesiAktif) {
            if ($sesiAktif) {
                $q->where('sesi_id', $sesiAktif->id);
            }
        }])->orderBy('kode_peserta');

        if ($request->filled('kelas') && $request->kelas !== 'semua') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('q')) {
            $cari = $request->q;
            $query->where(function ($sub) use ($cari) {
                $sub->where('kode_peserta', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%");
            });
        }

        $semuaSiswa  = Siswa::all();
        $totalSiswa  = $semuaSiswa->count();
        $sedangUjian = $sesiAktif ? HasilTes::where('sesi_id', $sesiAktif->id)->where('status_tes', 'sedang_mengerjakan')->count() : 0;
        $selesaiUjian = $sesiAktif ? HasilTes::where('sesi_id', $sesiAktif->id)->where('status_tes', 'selesai')->count() : 0;
        $belumLogin  = max(0, $totalSiswa - ($sedangUjian + $selesaiUjian));

        $daftarSiswa = $query->paginate(10)->withQueryString();
        $daftarKelas  = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $kodeOtomatis = $this->generateKodePesertaBerikutnya('X MPK 1');
        $daftarSesi = SesiTes::latest()->get();

        return view('admin.siswa', compact(
            'daftarSiswa',
            'daftarKelas',
            'totalSiswa',
            'sedangUjian',
            'selesaiUjian',
            'belumLogin',
            'kodeOtomatis',
            'daftarSesi'
        ));
    }

    public function simpanSiswa(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'kelas' => 'required|string|max:30',
        ]);

        $kelasBersih = strtoupper(trim($request->kelas));
        $kodeBaru    = $this->generateKodePesertaBerikutnya($kelasBersih);

        Siswa::create([
            'kode_peserta' => $kodeBaru,
            'nama'         => strtoupper(trim($request->nama)),
            'kelas'        => $kelasBersih,
        ]);

        return back()->with('sukses', "Peserta baru berhasil ditambahkan dengan kode {$kodeBaru} ({$kelasBersih}).");
    }

    public function getNextKode(Request $request)
    {
        $kelas = $request->query('kelas', 'X MPK 1');
        return response()->json([
            'kode' => $this->generateKodePesertaBerikutnya($kelas),
        ]);
    }

    public function updateSiswa(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'kelas' => 'required|string|max:30',
        ]);

        $siswa->update([
            'nama'  => strtoupper(trim($request->nama)),
            'kelas' => trim($request->kelas),
        ]);

        return back()->with('sukses', "Data peserta {$siswa->kode_peserta} berhasil diperbarui.");
    }

    public function resetTesSiswa(Request $request, Siswa $siswa)
    {
        $request->validate([
            'sesi_id' => 'required|exists:sesi_tes,id',
        ]);

        $sesi = SesiTes::findOrFail($request->sesi_id);

        $hasilIds = HasilTes::where('siswa_id', $siswa->id)
            ->where('sesi_id', $sesi->id)
            ->pluck('id');

        if ($hasilIds->isNotEmpty()) {
            Respons::whereIn('hasil_tes_id', $hasilIds)->delete();
            HasilTes::whereIn('id', $hasilIds)->delete();
            return back()->with('sukses', "Tes {$siswa->nama} ({$siswa->kode_peserta}) pada sesi '{$sesi->nama_sesi}' berhasil direset.");
        }

        return back()->with('sukses', "Peserta {$siswa->kode_peserta} belum memiliki riwayat tes pada sesi '{$sesi->nama_sesi}'.");
    }

    public function hapusSiswa(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('sukses', 'Peserta berhasil dihapus.');
    }

    public function imporSiswaCsv(Request $request)
    {
        $request->validate(['file_csv' => 'required|file|mimes:csv,txt']);
        $handle = fopen($request->file('file_csv')->getRealPath(), 'r');
        fgetcsv($handle); // Lewati header

        $jumlah = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 2 && !empty(trim($row[0]))) {
                Siswa::updateOrCreate(
                    ['kode_peserta' => strtoupper(trim($row[0]))],
                    ['nama' => trim($row[1]), 'kelas' => trim($row[2] ?? 'X MPK 1')]
                );
                $jumlah++;
            }
        }
        fclose($handle);
        return back()->with('sukses', "$jumlah data siswa berhasil diimpor.");
    }

    // ==========================================
    // 2. HALAMAN BANK SOAL TERKALIBRASI
    // ==========================================
    public function soal(Request $request)
    {
        $query = ButirSoal::orderBy('nomor_butir');

        if ($request->filled('indikator') && $request->indikator !== 'semua') {
            $query->where('indikator', $request->indikator);
        }

        if ($request->filled('fit') && $request->fit !== 'semua') {
            $query->where('status_fit', $request->fit === '1');
        }

        if ($request->filled('q')) {
            $cari = $request->q;
            $query->where('teks_soal', 'like', "%{$cari}%");
        }

        $semuaSoal    = ButirSoal::all();
        $totalButir   = $semuaSoal->count();
        $jumlahFit    = $semuaSoal->where('status_fit', true)->count();
        $jumlahMisfit = $totalButir - $jumlahFit;
        $persenFit    = $totalButir > 0 ? round(($jumlahFit / $totalButir) * 100) : 0;
        $meanB        = $totalButir > 0 ? $semuaSoal->avg('ukuran_b') : 0;

        $daftarSoal = $query->paginate(10)->withQueryString();

        return view('admin.soal', compact(
            'daftarSoal',
            'totalButir',
            'jumlahFit',
            'jumlahMisfit',
            'persenFit',
            'meanB'
        ));
    }

    public function simpanSoal(Request $request)
    {
        $data = $request->validate([
            'nomor_butir'   => 'required|integer|unique:butir_soal,nomor_butir',
            'indikator'     => 'required|string',
            'teks_soal'     => 'required|string',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'opsi_e'        => 'required|string',
            'kunci_jawaban' => 'required|in:A,B,C,D,E',
            'ukuran_b'      => 'required|numeric',
            'status_fit'    => 'required|boolean',
        ]);

        ButirSoal::create($data);
        return back()->with('sukses', 'Butir soal baru berhasil ditambahkan.');
    }

    public function updateKalibrasiSoal(Request $request, ButirSoal $soal)
    {
        $data = $request->validate([
            'indikator'     => 'sometimes|string',
            'teks_soal'     => 'sometimes|string',
            'opsi_a'        => 'sometimes|string',
            'opsi_b'        => 'sometimes|string',
            'opsi_c'        => 'sometimes|string',
            'opsi_d'        => 'sometimes|string',
            'opsi_e'        => 'sometimes|string',
            'kunci_jawaban' => 'sometimes|in:A,B,C,D,E',
            'ukuran_b'      => 'required|numeric',
            'status_fit'    => 'required|boolean',
        ]);

        $soal->update($data);
        return back()->with('sukses', "Butir soal #{$soal->nomor_butir} berhasil diperbarui.");
    }

    public function imporKalibrasiCsv(Request $request)
    {
        $request->validate(['file_csv' => 'required|file|mimes:csv,txt']);
        $handle = fopen($request->file('file_csv')->getRealPath(), 'r');
        fgetcsv($handle); // Header: nomor_butir,ukuran_b,status_fit

        $jumlah = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 3 && is_numeric($row[0])) {
                $updated = ButirSoal::where('nomor_butir', (int)$row[0])->update([
                    'ukuran_b'   => (float) str_replace(',', '.', $row[1]),
                    'status_fit' => (int)$row[2] === 1,
                ]);
                if ($updated) $jumlah++;
            }
        }
        fclose($handle);
        return back()->with('sukses', "Kalibrasi Rasch untuk $jumlah butir soal berhasil diperbarui.");
    }

    // ==========================================
    // 3. HALAMAN SESI TES
    // ==========================================
    public function sesi(Request $request)
    {
        $query = SesiTes::withCount([
            'hasilTes as selesai_count' => fn($q) => $q->where('status_tes', 'selesai')
        ])->latest();

        if ($request->filled('q')) {
            $query->where('nama_sesi', 'like', '\%' . $request->q . '%');
        }

        $daftarSesi     = $query->get();
        $totalSesi      = SesiTes::count();
        $sesiBerjalan   = SesiTes::where('status', 'aktif')->count();
        $totalPeserta   = Siswa::count();
        $kandidatLogin  = HasilTes::where('status_tes', 'sedang_mengerjakan')->count();

        return view('admin.sesi', compact(
            'daftarSesi',
            'totalSesi',
            'sesiBerjalan',
            'totalPeserta',
            'kandidatLogin'
        ));
    }

    public function simpanSesi(Request $request)
    {
        $request->validate([
            'nama_sesi'   => 'required|string|max:100',
            'waktu_mulai' => 'nullable|date',
        ]);

        SesiTes::create([
            'nama_sesi'   => $request->nama_sesi,
            'waktu_mulai' => $request->waktu_mulai ?? now(),
            'status'      => 'nonaktif',
        ]);

        return back()->with('sukses', 'Sesi tes baru berhasil dibuat.');
    }

    public function toggleSesi(SesiTes $sesi)
    {
        if ($sesi->status === 'nonaktif') {
            SesiTes::where('status', 'aktif')->update(['status' => 'nonaktif', 'waktu_selesai' => now()]);
            $sesi->update(['status' => 'aktif', 'waktu_mulai' => now(), 'waktu_selesai' => null]);
        } else {
            $sesi->update(['status' => 'nonaktif', 'waktu_selesai' => now()]);
        }
        return back()->with('sukses', 'Status sesi tes berhasil diubah.');
    }

    public function hapusSesi(SesiTes $sesi)
    {
        $hasilIds = HasilTes::where('sesi_id', $sesi->id)->pluck('id');
        if ($hasilIds->isNotEmpty()) {
            Respons::whereIn('hasil_tes_id', $hasilIds)->delete();
            HasilTes::whereIn('id', $hasilIds)->delete();
        }

        $nama = $sesi->nama_sesi;
        $sesi->delete();

        return back()->with('sukses', "Sesi '{$nama}' beserta data hasil tesnya berhasil dihapus.");
    }

    // ==========================================
    // 4. HALAMAN HASIL TES & GRAFIK SEBARAN THETA
    // ==========================================
    public function hasil(Request $request)
    {
        $daftarSesi = SesiTes::latest()->get();
        $sesiId = $request->get('sesi_id') ?? SesiTes::where('status', 'aktif')->value('id') ?? $daftarSesi->first()?->id;

        $baseQuery = HasilTes::with(['siswa', 'respons.butirSoal'])
            ->when($sesiId, fn($q) => $q->where('sesi_id', $sesiId));

        $semuaHasilSesi = (clone $baseQuery)->where('status_tes', 'selesai')->get();

        $totalTerdaftar = Siswa::count();
        $pesertaSelesai = $semuaHasilSesi->count();
        $persenSelesai  = $totalTerdaftar > 0 ? round(($pesertaSelesai / $totalTerdaftar) * 100) : 0;
        $rerataTheta    = $pesertaSelesai > 0 ? $semuaHasilSesi->avg('theta_akhir') : 0;
        $rerataSem      = $pesertaSelesai > 0 ? $semuaHasilSesi->avg('sem_akhir') : 0;
        $rerataButir    = $pesertaSelesai > 0 ? round($semuaHasilSesi->avg('jumlah_butir')) : 0;

        // Histogram distribusi Theta dalam 9 bin: <-2.0, -1.5, -1.0, -0.5, 0.0, +0.5, +1.0, +1.5, >+2.0
        $bins = [
            '<-2.0' => 0,
            '-1.5' => 0,
            '-1.0' => 0,
            '-0.5' => 0,
            '0.0'   => 0,
            '+0.5' => 0,
            '+1.0' => 0,
            '+1.5' => 0,
            '>+2.0' => 0,
        ];
        foreach ($semuaHasilSesi as $h) {
            $t = (float)$h->theta_akhir;
            if ($t < -1.75) $bins['<-2.0']++;
            elseif ($t < -1.25) $bins['-1.5']++;
            elseif ($t < -0.75) $bins['-1.0']++;
            elseif ($t < -0.25) $bins['-0.5']++;
            elseif ($t < 0.25) $bins['0.0']++;
            elseif ($t < 0.75) $bins['+0.5']++;
            elseif ($t < 1.25) $bins['+1.0']++;
            elseif ($t < 1.75) $bins['+1.5']++;
            else $bins['>+2.0']++;
        }

        // Tabel Individual Siswa
        $tableQuery = (clone $baseQuery)->latest();
        if ($request->filled('q')) {
            $cari = $request->q;
            $tableQuery->whereHas('siswa', function ($q) use ($cari) {
                $q->where('kode_peserta', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%");
            });
        }
        $daftarHasil = $tableQuery->paginate(10)->withQueryString();

        return view('admin.hasil', compact(
            'daftarSesi',
            'sesiId',
            'daftarHasil',
            'totalTerdaftar',
            'pesertaSelesai',
            'persenSelesai',
            'rerataTheta',
            'rerataSem',
            'rerataButir',
            'bins'
        ));
    }

    public function hapusHasil(HasilTes $hasil)
    {
        $kode = $hasil->siswa?->kode_peserta ?? '-';
        $hasil->respons()->delete();
        $hasil->delete();

        return back()->with('sukses', "Data hasil tes peserta {$kode} berhasil dihapus.");
    }

    // Ekspor 1: Ringkasan per Siswa (Google Colab)
    public function exportRingkasanCsv(Request $request)
    {
        $hasil = HasilTes::with('siswa')
            ->where('status_tes', 'selesai')
            ->when($request->sesi_id, fn($q) => $q->where('sesi_id', $request->sesi_id))
            ->get();

        return response()->streamDownload(function () use ($hasil) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['kode_peserta', 'theta_akhir', 'sem_akhir', 'jumlah_butir', 'alasan_berhenti', 'waktu_mulai', 'waktu_selesai']);
            foreach ($hasil as $h) {
                fputcsv($out, [
                    $h->siswa->kode_peserta,
                    $h->theta_akhir,
                    $h->sem_akhir,
                    $h->jumlah_butir,
                    $h->alasan_berhenti,
                    $h->waktu_mulai,
                    $h->waktu_selesai,
                ]);
            }
            fclose($out);
        }, 'ringkasan_hasil_cat_' . date('Ymd_His') . '.csv');
    }

    // Ekspor 2: Respons per Butir (Google Colab)
    public function exportResponsCsv(Request $request)
    {
        $respons = Respons::with(['hasilTes.siswa', 'butirSoal'])
            ->whereHas('hasilTes', function ($q) use ($request) {
                $q->where('status_tes', 'selesai')
                    ->when($request->sesi_id, fn($sub) => $sub->where('sesi_id', $request->sesi_id));
            })
            ->orderBy('hasil_tes_id')
            ->orderBy('urutan')
            ->get();

        return response()->streamDownload(function () use ($respons) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['kode_peserta', 'urutan', 'nomor_butir', 'indikator', 'ukuran_b', 'jawaban_dipilih', 'skor', 'theta_setelah', 'sem_setelah']);
            foreach ($respons as $r) {
                fputcsv($out, [
                    $r->hasilTes->siswa->kode_peserta,
                    $r->urutan,
                    $r->butirSoal->nomor_butir,
                    $r->butirSoal->indikator,
                    $r->butirSoal->ukuran_b,
                    $r->jawaban_dipilih,
                    $r->skor,
                    $r->theta_setelah,
                    $r->sem_setelah,
                ]);
            }
            fclose($out);
        }, 'respons_butir_cat_' . date('Ymd_His') . '.csv');
    }

    // Tambahkan fungsi private ini di dalam AdminController
    private function extractPrefixKelas(string $kelas): string
    {
        $k = strtoupper(trim($kelas));

        // Jika berpola MPK (contoh: "X MPK 1" -> "MPK1", "X MPK 2" -> "MPK2", "MPK 3" -> "MPK3")
        if (preg_match('/MPK\s*(\d+)/', $k, $match)) {
            return 'MPK' . $match[1];
        }

        // Fallback jika memakai nama kelas lain (contoh: "X RPL 1" -> "RPL1")
        $tanpaTingkat = preg_replace('/^(XII|XI|X)\s+/', '', $k);
        $prefix = preg_replace('/[^A-Z0-9]/', '', $tanpaTingkat);

        return $prefix !== '' ? $prefix : 'MPK1';
    }

    private function generateKodePesertaBerikutnya(string $kelas = 'X MPK 1'): string
    {
        $prefix        = $this->extractPrefixKelas($kelas);
        $prefixPattern = $prefix . '-';

        $kodeList = Siswa::where('kode_peserta', 'like', $prefixPattern . '%')
            ->pluck('kode_peserta');

        $maxNomor = 0;
        foreach ($kodeList as $kode) {
            $bagianAngka = substr($kode, strlen($prefixPattern));
            if (is_numeric($bagianAngka)) {
                $angka = (int) $bagianAngka;
                if ($angka > $maxNomor) {
                    $maxNomor = $angka;
                }
            }
        }

        return $prefixPattern . str_pad($maxNomor + 1, 3, '0', STR_PAD_LEFT);
    }
}
