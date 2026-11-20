<?php

namespace App\Services;

use App\Models\ButirSoal;
use App\Models\HasilTes;
use Illuminate\Support\Collection;

class CatEngineService
{
    public function hitungPeluang(float $theta, float $b): float
    {
        return 1.0 / (1.0 + exp(- ($theta - $b)));
    }

    public function hitungInformasi(float $theta, float $b): float
    {
        $p = $this->hitungPeluang($theta, $b);
        return $p * (1.0 - $p);
    }

    public function hitungSEM(float $theta, array $daftarB): ?float
    {
        $totalInfo = 0.0;
        foreach ($daftarB as $b) {
            $totalInfo += $this->hitungInformasi($theta, (float) $b);
        }

        return $totalInfo > 0 ? round(1.0 / sqrt($totalInfo), 4) : null;
    }

    public function estimasiTheta(Collection $riwayatRespons, float $thetaSebelumnya): float
    {
        $jumlahSoal = $riwayatRespons->count();
        $totalSkor  = $riwayatRespons->sum('skor');

        $langkahTetap = config('cat.langkah_tetap');
        $minTheta     = config('cat.batas_theta_min');
        $maxTheta     = config('cat.batas_theta_max');

        if ($totalSkor === $jumlahSoal) {
            return min($maxTheta, round($thetaSebelumnya + $langkahTetap, 4));
        }

        if ($totalSkor === 0) {
            return max($minTheta, round($thetaSebelumnya - $langkahTetap, 4));
        }

        $theta = $thetaSebelumnya;
        for ($i = 0; $i < 25; $i++) {
            $sumP    = 0.0;
            $sumInfo = 0.0;

            foreach ($riwayatRespons as $item) {
                $b = (float) $item->butirSoal->ukuran_b;
                $p = $this->hitungPeluang($theta, $b);
                $sumP += $p;
                $sumInfo += $p * (1.0 - $p);
            }

            if ($sumInfo < 1e-6) {
                break;
            }

            $delta = ($totalSkor - $sumP) / $sumInfo;
            $theta = max($minTheta, min($maxTheta, $theta + $delta));

            if (abs($delta) < 0.0001) {
                break;
            }
        }

        return round($theta, 4);
    }

    public function pilihButirBerikutnya(HasilTes $hasilTes): ?ButirSoal
    {
        $sudahDijawabIds = $hasilTes->respons()->pluck('butir_soal_id')->toArray();

        $kandidat = ButirSoal::where('status_fit', true)
            ->whereNotIn('id', $sudahDijawabIds)
            ->orderBy('nomor_butir', 'asc')
            ->get();

        if ($kandidat->isEmpty()) {
            return null;
        }

        $jumlahDijawab = count($sudahDijawabIds);
        $jumlahAwal    = config('cat.jumlah_butir_awal');

        // 3 butir awal: tingkat kesukaran sedang (|b| mendekati 0)
        if ($jumlahDijawab < $jumlahAwal) {
            $terurutAwal = $kandidat->sortBy(fn($soal) => abs($soal->ukuran_b))->values();
            $poolAwal    = $terurutAwal->take(max(1, $jumlahAwal - $jumlahDijawab));
            return $poolAwal->random();
        }

        // Butir ke-4 dst: Informasi Fisher Maksimum pada theta sementara
        $thetaSekarang = (float) $hasilTes->theta_akhir;
        return $kandidat->sortByDesc(function ($soal) use ($thetaSekarang) {
            return $this->hitungInformasi($thetaSekarang, $soal->ukuran_b);
        })->first();
    }

    public function cekPenghentian(Collection $riwayatRespons, ?float $sem, bool $adaSoalTersisa): array
    {
        $jumlahButir = $riwayatRespons->count();
        $totalSkor   = $riwayatRespons->sum('skor');
        $bervariasi  = ($totalSkor > 0 && $totalSkor < $jumlahButir);

        if ($jumlahButir >= config('cat.maks_butir')) {
            return ['berhenti' => true, 'alasan' => 'MAKS_BUTIR'];
        }

        if (!$adaSoalTersisa) {
            return ['berhenti' => true, 'alasan' => 'SOAL_HABIS'];
        }

        if ($bervariasi && $sem !== null && $sem <= config('cat.ambang_sem')) {
            return ['berhenti' => true, 'alasan' => 'SEM_TERPENUHI'];
        }

        return ['berhenti' => false, 'alasan' => null];
    }
}
