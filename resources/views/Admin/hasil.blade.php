@extends('layouts.admin')
@section('content')
    <!-- Header & Selector Sesi + Ekspor CSV -->
    <div
        class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-5">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">EVALUASI PSIKOMETRIK CAT</p>
                <h1 class="text-2xl font-extrabold text-slate-900">Hasil Tes</h1>
            </div>
            <div class="h-10 w-[1px] bg-slate-200 hidden sm:block"></div>
            <form method="GET" action="{{ route('admin.hasil') }}">
                <label class="block text-[10px] font-bold text-slate-400 mb-1">Pilih Sesi Tes</label>
                <select name="sesi_id" onchange="this.form.submit()"
                    class="bg-[#F1F5F9] text-xs font-bold text-slate-800 rounded-xl px-4 py-2.5 outline-none">
                    @foreach ($daftarSesi as $s)
                        <option value="{{ $s->id }}" {{ $sesiId == $s->id ? 'selected' : '' }}>{{ $s->nama_sesi }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.hasil', ['sesi_id' => $sesiId]) }}"
                class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition">↻
                Sinkronisasi</a>
            <a href="{{ route('admin.export.ringkasan', ['sesi_id' => $sesiId]) }}"
                class="bg-[#1456E8] hover:bg-[#0F46C4] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">↓
                Ekspor Ringkasan (CSV)</a>
            <a href="{{ route('admin.export.respons', ['sesi_id' => $sesiId]) }}"
                class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">↓
                Ekspor Respons (CSV)</a>
        </div>
    </div>

    <!-- 4 Kartu KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-xs text-slate-500 font-semibold">Peserta Selesai</p>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-2xl font-extrabold text-slate-900">{{ $pesertaSelesai }}</span>
                <span class="text-[11px] text-slate-400">dari {{ $totalTerdaftar }} terdaftar ({{ $persenSelesai }}%)</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-[#1456E8]" style="width: {{ $persenSelesai }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-xs text-slate-500 font-semibold">Rerata θ (Theta)</p>
            <div class="flex items-baseline gap-2 mt-2">
                <span
                    class="text-2xl font-extrabold text-slate-900">{{ ($rerataTheta >= 0 ? '+' : '') . number_format($rerataTheta, 2) }}</span>
                <span class="text-[11px] font-mono-code text-slate-400">Skala [-4, +4]</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-[#1456E8] mx-auto" style="width: 30%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-xs text-slate-500 font-semibold">Rerata SEM</p>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-2xl font-extrabold text-slate-900">{{ number_format($rerataSem, 2) }}</span>
                <span class="text-[11px] text-slate-400">Ambang target ≤ {{ config('cat.ambang_sem') }}</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-[#1456E8]" style="width: 75%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-xs text-slate-500 font-semibold">Rerata Jumlah Soal</p>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-2xl font-extrabold text-slate-900">{{ $rerataButir }}</span>
                <span class="text-[11px] text-slate-400">Maksimum: {{ config('cat.maks_butir') }} Butir</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-[#1456E8]"
                    style="width: {{ min(100, round(($rerataButir / config('cat.maks_butir')) * 100)) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Grafik Batang Sebaran Theta Siswa -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xs">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900">Sebaran θ Siswa</h2>
                <p class="text-xs text-slate-400">Distribusi estimasi kemampuan laten (Latent Trait Ability Distribution)
                </p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-xs bg-[#0044CC]"></span>
                    Frekuensi Populasi</span>
                <span class="inline-flex items-center gap-1.5 text-red-600">— Mean (θ =
                    {{ number_format($rerataTheta, 2) }})</span>
            </div>
        </div>

        @php $maxBin = max(1, max($bins)); @endphp
        <div class="bg-[#F1F5F9]/70 rounded-2xl p-6">
            <div class="h-44 flex items-end justify-around gap-3 border-b border-slate-200 pb-2 px-4">
                @foreach ($bins as $label => $jumlah)
                    @php $tinggiPersen = round(($jumlah / $maxBin) * 100); @endphp
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <span class="text-[10px] font-bold text-slate-500 mb-1">{{ $jumlah > 0 ? $jumlah : '' }}</span>
                        <div class="w-full max-w-[44px] bg-[#0044CC] rounded-t-md transition-all"
                            style="height: {{ max(6, $tinggiPersen) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-around gap-3 pt-2 px-4 text-[11px] font-mono-code text-slate-500">
                @foreach (array_keys($bins) as $label)
                    <span class="flex-1 text-center">{{ $label }}</span>
                @endforeach
            </div>
            <div class="flex justify-between items-center text-[11px] text-slate-400 mt-4 px-2">
                <span>Rentang Kemampuan Rendah (-4.0 logit)</span>
                <span class="font-bold text-slate-700">SKALA KEMAMPUAN LOGIT (θ)</span>
                <span>Rentang Kemampuan Tinggi (+4.0 logit)</span>
            </div>
        </div>
    </div>

    <!-- Tabel Data Hasil Individual Siswa -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900">Data Hasil Individual Siswa</h2>
                <p class="text-xs text-slate-400">Rincian parameter psikometrik Rasch 1-PL berbasis respons adaptif</p>
            </div>
            <form method="GET" action="{{ route('admin.hasil') }}">
                <input type="hidden" name="sesi_id" value="{{ $sesiId }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Kode / Nama..."
                    class="bg-[#F1F5F9] text-xs rounded-xl px-4 py-2 outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-slate-100 text-[10px] font-bold tracking-wider text-slate-400 uppercase bg-slate-50/50">
                        <th class="py-3.5 px-5">KODE PESERTA</th>
                        <th class="py-3.5 px-4">θ AKHIR (LOGIT)</th>
                        <th class="py-3.5 px-4">SEM</th>
                        <th class="py-3.5 px-4">JUMLAH SOAL</th>
                        <th class="py-3.5 px-4">ALASAN BERHENTI</th>
                        <th class="py-3.5 px-5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($daftarHasil as $h)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3.5 px-5">
                                <p class="font-mono-code font-bold text-slate-900">{{ $h->siswa->kode_peserta }}</p>
                                <p class="text-[11px] text-slate-400">{{ $h->siswa->nama }}</p>
                            </td>
                            <td
                                class="py-3.5 px-4 font-mono-code font-bold {{ $h->theta_akhir >= 0 ? 'text-[#1456E8]' : 'text-red-600' }}">
                                {{ ($h->theta_akhir >= 0 ? '+' : '') . number_format($h->theta_akhir, 2) }}
                            </td>
                            <td class="py-3.5 px-4 font-mono-code text-slate-600">
                                {{ $h->sem_akhir !== null ? number_format($h->sem_akhir, 2) : '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $h->jumlah_butir }}</td>
                            <td class="py-3.5 px-4">
                                @if ($h->alasan_berhenti === 'SEM_TERPENUHI')
                                    <span
                                        class="bg-[#EEF2FF] text-slate-700 font-semibold px-3 py-1 rounded-full text-[11px]">SEM
                                        terpenuhi</span>
                                @elseif($h->alasan_berhenti === 'MAKS_BUTIR')
                                    <span
                                        class="bg-red-50 text-red-700 font-semibold px-3 py-1 rounded-full text-[11px]">Batas
                                        soal maksimum</span>
                                @else
                                    <span
                                        class="bg-amber-50 text-amber-700 font-semibold px-3 py-1 rounded-full text-[11px]">{{ $h->status_tes }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="inline-flex items-center justify-end gap-3">
                                    <button onclick='lihatRiwayat(@json($h->respons))'
                                        class="text-[#1456E8] hover:underline font-bold">Lihat Riwayat Respons →</button>
                                    <span class="text-slate-200">|</span>
                                    <form action="{{ route('admin.hasil.hapus', $h) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus data hasil tes milik {{ $h->siswa->kode_peserta }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-bold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada hasil tes pada sesi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Menampilkan <strong>{{ $daftarHasil->firstItem() ?? 0 }}-{{ $daftarHasil->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $daftarHasil->total() }}</strong> peserta</span>
            <div>{{ $daftarHasil->links('pagination::tailwind') }}</div>
        </div>
    </div>

    <!-- Modal Riwayat Respons Per Butir -->
    <div id="modalRiwayat" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl text-xs max-h-[85vh] flex flex-col">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-base font-extrabold text-slate-900">Jejak Adaptif Respons Siswa</h3>
                <button onclick="document.getElementById('modalRiwayat').classList.add('hidden')"
                    class="font-bold text-slate-400 hover:text-slate-700">✕</button>
            </div>
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-[10px] uppercase text-slate-400">
                            <th class="py-2">Urutan</th>
                            <th>Butir</th>
                            <th>Indikator</th>
                            <th>b</th>
                            <th>Jawab</th>
                            <th>Skor</th>
                            <th>θ Setelah</th>
                            <th>SEM</th>
                        </tr>
                    </thead>
                    <tbody id="isiRiwayat" class="divide-y"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function lihatRiwayat(respons) {
            const tbody = document.getElementById('isiRiwayat');
            tbody.innerHTML = respons.map(r => `
        <tr>
            <td class="py-2 font-mono-code">#${r.urutan}</td>
            <td class="py-2 font-bold">Soal ${r.butir_soal?.nomor_butir ?? r.butir_soal_id}</td>
            <td class="py-2">${r.butir_soal?.indikator ?? '-'}</td>
            <td class="py-2 font-mono-code">${r.butir_soal?.ukuran_b ?? '-'}</td>
            <td class="py-2 font-bold">${r.jawaban_dipilih}</td>
            <td class="py-2 font-bold ${r.skor == 1 ? 'text-emerald-600' : 'text-red-600'}">${r.skor}</td>
            <td class="py-2 font-mono-code">${Number(r.theta_setelah).toFixed(2)}</td>
            <td class="py-2 font-mono-code">${r.sem_setelah ? Number(r.sem_setelah).toFixed(2) : '-'}</td>
        </tr>
    `).join('');
            document.getElementById('modalRiwayat').classList.remove('hidden');
        }
    </script>
@endsection
