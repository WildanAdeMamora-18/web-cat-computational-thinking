@extends('layouts.admin')
@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold tracking-wider uppercase text-slate-400">MANAJEMEN EVALUASI › <span
                    class="text-[#1456E8]">SESI UJIAN CAT</span></p>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Sesi Tes</h1>
            <p class="text-xs text-slate-500 mt-0.5">Konfigurasi jadwal, token aktivasi, dan parameter adaptif (Item Response
                Theory / CAT).</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.sesi') }}"
                class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition">↻
                Muat Ulang</a>
            <a href="#formBuatSesi"
                class="bg-[#1456E8] hover:bg-[#0F46C4] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">+
                Buat Sesi Baru</a>
        </div>
    </div>

    <!-- 4 Kartu KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">TOTAL SESI</p>
            <div class="flex items-baseline gap-3 mt-1.5">
                <p class="text-2xl font-extrabold text-slate-900">{{ $totalSesi }}</p>
                <span class="text-xs text-slate-400">Tersimpan</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">SESI BERJALAN</p>
            <div class="flex items-baseline gap-3 mt-1.5">
                <p class="text-2xl font-extrabold text-[#1456E8]">{{ $sesiBerjalan }}</p>
                <span class="text-xs text-slate-400">{{ $kandidatLogin }} Kandidat Login</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">PESERTA TERDAFTAR</p>
            <div class="flex items-baseline gap-3 mt-1.5">
                <p class="text-2xl font-extrabold text-slate-900">{{ $totalPeserta }}</p>
                <span class="text-xs text-slate-400">Rombel Aktif</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
            <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">METODE PENGUJIAN</p>
            <div class="flex items-baseline gap-2 mt-1.5">
                <p class="text-lg font-extrabold text-slate-900">CAT / 1-PL</p>
                <span class="text-[11px] text-slate-400">Model Rasch</span>
            </div>
        </div>
    </div>

    <!-- Daftar Jadwal Sesi -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900">Daftar Jadwal Sesi</h2>
                <p class="text-xs text-slate-400">Monitoring status berlangsung dan kontrol akses ruang CAT.</p>
            </div>
            <form method="GET" action="{{ route('admin.sesi') }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sesi..."
                    class="bg-[#F1F5F9] text-xs rounded-xl px-3.5 py-2 outline-none">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-slate-100 text-[10px] font-bold tracking-wider text-slate-400 uppercase bg-slate-50/50">
                        <th class="py-3.5 px-5">NAMA SESI</th>
                        <th class="py-3.5 px-4">KELAS</th>
                        <th class="py-3.5 px-4">WAKTU MULAI</th>
                        <th class="py-3.5 px-4">STATUS</th>
                        <th class="py-3.5 px-4">PESERTA SELESAI</th>
                        <th class="py-3.5 px-5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach ($daftarSesi as $s)
                        @php $persen = $totalPeserta > 0 ? round(($s->selesai_count / $totalPeserta) * 100) : 0; @endphp
                        <tr>
                            <td class="py-4 px-5">
                                <p class="font-bold text-slate-900">{{ $s->nama_sesi }}</p>
                                <p class="text-[11px] font-mono-code text-slate-400 mt-0.5">
                                    SESI-{{ str_pad($s->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </td>
                            <td class="py-4 px-4"><span
                                    class="bg-[#EEF2FF] text-[#1456E8] font-bold px-2.5 py-1 rounded-md text-[11px]">X MPK
                                    1</span></td>
                            <td class="py-4 px-4 text-slate-600">{{ $s->waktu_mulai?->format('d M Y, H:i') ?? '-' }} WIB
                            </td>
                            <td class="py-4 px-4">
                                @if ($s->status === 'aktif')
                                    <span class="bg-[#EEF2FF] text-[#1456E8] font-bold px-3 py-1 rounded-full text-[11px]">•
                                        Aktif</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full text-[11px]">•
                                        Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="font-bold text-slate-700">{{ $s->selesai_count }}/{{ $totalPeserta }}</span>
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#1456E8]" style="width: {{ $persen }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $persen }}%</span>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <form action="{{ route('admin.sesi.toggle', $s) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="border border-slate-200 hover:border-[#1456E8] {{ $s->status === 'aktif' ? 'text-red-600' : 'text-[#1456E8]' }} font-bold px-3 py-1.5 rounded-lg text-[11px] transition">
                                        {{ $s->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kartu Pengaturan Sesi & Aturan CAT -->
    <div id="formBuatSesi" class="bg-white rounded-2xl border border-slate-100 shadow-2xs p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <h2 class="text-sm font-extrabold text-slate-900">Pengaturan Sesi & Parameter CAT</h2>
                <p class="text-xs text-slate-400">Buat jadwal sesi baru dan tinjau konstanta algoritma penghentian adaptif.
                </p>
            </div>
            <span class="bg-slate-100 text-slate-600 font-mono-code font-bold text-[10px] px-2.5 py-1 rounded-md">MODE:
                RASCH-CAT (1PL)</span>
        </div>

        <form action="{{ route('admin.sesi.simpan') }}" method="POST" class="space-y-5 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-bold text-slate-700 block mb-1.5">Nama Sesi *</label>
                    <input type="text" name="nama_sesi" placeholder="Tes Kemampuan Berpikir Komputasional" required
                        class="w-full bg-[#F1F5F9] rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#1456E8]">
                </div>
                <div>
                    <label class="font-bold text-slate-700 block mb-1.5">Tanggal & Jam Mulai</label>
                    <input type="datetime-local" name="waktu_mulai"
                        class="w-full bg-[#F1F5F9] rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#1456E8]">
                </div>
            </div>

            <!-- Kotak Parameter CAT (Read-only sesuai config/cat.php) -->
            <div class="bg-[#F8FAFC] border border-slate-200/70 rounded-2xl p-4">
                <p class="font-bold text-[#1456E8] mb-1">✦ Aturan CAT (Computerized Adaptive Testing)</p>
                <p class="text-[11px] text-slate-500 mb-3">Konfigurasi motor adaptif Rasch 1PL menentukan tingkat kesulitan
                    soal berikutnya berdasarkan probabilitas respons benar peserta.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="bg-white border border-slate-200/80 rounded-xl p-3">
                        <span class="text-[11px] font-bold text-slate-600">Estimasi Awal θ (Theta)</span>
                        <p class="font-mono-code text-base font-extrabold text-slate-900 mt-1">
                            {{ number_format(config('cat.theta_awal'), 2) }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Nilai awal kapabilitas siswa sebelum butir pertama
                            disajikan.</p>
                    </div>
                    <div class="bg-white border border-slate-200/80 rounded-xl p-3">
                        <span class="text-[11px] font-bold text-slate-600">Batas SEM Berhenti</span>
                        <p class="font-mono-code text-base font-extrabold text-slate-900 mt-1">
                            {{ number_format(config('cat.ambang_sem'), 2) }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Ujian selesai otomatis jika SEM ≤ batas ini (setelah
                            respons bervariasi).</p>
                    </div>
                    <div class="bg-white border border-slate-200/80 rounded-xl p-3">
                        <span class="text-[11px] font-bold text-slate-600">Jumlah Soal Maksimum</span>
                        <p class="font-mono-code text-base font-extrabold text-slate-900 mt-1">
                            {{ config('cat.maks_butir') }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Batas maksimal pertanyaan yang disajikan jika batas SEM
                            belum tercapai.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-[#1456E8] hover:bg-[#0F46C4] text-white font-bold px-5 py-2.5 rounded-xl shadow-xs transition">
                    Simpan & Buat Sesi Baru
                </button>
            </div>
        </form>
    </div>
@endsection
