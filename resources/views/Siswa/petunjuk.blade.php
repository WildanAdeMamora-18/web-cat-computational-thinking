@extends('layouts.siswa')
@section('content')
    <!-- Top App Bar -->
    <div class="bg-white border-b border-slate-100 px-4 py-3 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.login') }}" class="text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-tight">Lembar Ujian Aktif</p>
                <p class="text-[11px] text-slate-400">Sesi Ujian Adaptif</p>
            </div>
        </div>
        <div class="w-9 h-9 rounded-full bg-[#1456E8] text-white flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" />
            </svg>
        </div>
    </div>

    <div class="p-4 space-y-4">
        <!-- Kartu Identitas Peserta -->
        <div class="bg-[#EEF2FF]/70 border border-blue-100 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">IDENTITAS PESERTA</span>
                <span class="bg-slate-200/70 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">CAT SMK</span>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="16" rx="2" />
                        <circle cx="9" cy="10" r="2" />
                        <path d="M15 8h2m-2 4h2M7 16h10" />
                    </svg>
                    <span class="text-slate-600">Kode Peserta: <strong
                            class="font-mono-code text-[#1456E8] font-bold">{{ $hasilTes->siswa->kode_peserta }}</strong></span>
                </div>
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    <span class="text-slate-600">Mata Pelajaran: <strong class="text-slate-800">Informatika – Berpikir
                            Komputasional</strong></span>
                </div>
            </div>
        </div>

        <!-- Kartu Petunjuk Pengerjaan -->
        <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1456E8] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Petunjuk Pengerjaan</h2>
                    <p class="text-xs text-slate-400">Harap baca seksama tata tertib evaluasi</p>
                </div>
            </div>

            @php
                $aturan = [
                    'Baca setiap soal dengan teliti sebelum menentukan jawaban.',
                    'Pilih satu jawaban yang paling tepat pada opsi yang tersedia.',
                    'Soal berikutnya menyesuaikan jawabanmu, jadi jumlah soal setiap peserta bisa berbeda.',
                    'Jawaban tidak dapat diubah setelah dikirim ke server evaluasi.',
                    'Kerjakan secara mandiri dan jujur tanpa bantuan alat eksternal.',
                ];
            @endphp

            <div class="space-y-2.5">
                @foreach ($aturan as $idx => $teks)
                    <div class="bg-[#F8FAFC] rounded-xl p-3.5 flex items-start gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-blue-100/80 text-[#1456E8] text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                            {{ $idx + 1 }}
                        </span>
                        <p class="text-[13px] text-slate-700 leading-relaxed">{{ $teks }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Kotak Konfirmasi & Tombol Mulai -->
        <div class="bg-white rounded-3xl p-4 shadow-xs border border-slate-100">
            <label class="flex items-center gap-3 cursor-pointer mb-3.5 select-none">
                <input type="checkbox" id="cekSetuju"
                    class="w-5 h-5 rounded-md border-slate-300 text-[#1456E8] focus:ring-0">
                <span class="text-xs font-semibold text-slate-700">Saya sudah membaca dan memahami petunjuk</span>
            </label>

            <a href="{{ route('ujian.tes') }}" id="btnMulai"
                class="pointer-events-none w-full bg-[#E2E8F0] text-slate-400 font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 transition">
                <span>Mulai Tes</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <p class="text-center text-[11px] text-slate-400 flex items-center justify-center gap-1.5 pb-4">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="5" y="11" width="14" height="10" rx="2" />
                <path d="M8 11V7a4 4 0 018 0v4" />
            </svg>
            Sesi terenkripsi & terpantau otomatis
        </p>
    </div>

    <script>
        const cek = document.getElementById('cekSetuju');
        const btn = document.getElementById('btnMulai');
        cek.addEventListener('change', function() {
            if (this.checked) {
                btn.classList.remove('pointer-events-none', 'bg-[#E2E8F0]', 'text-slate-400');
                btn.classList.add('bg-[#1456E8]', 'hover:bg-[#0F46C4]', 'text-white', 'shadow-sm');
            } else {
                btn.classList.add('pointer-events-none', 'bg-[#E2E8F0]', 'text-slate-400');
                btn.classList.remove('bg-[#1456E8]', 'hover:bg-[#0F46C4]', 'text-white', 'shadow-sm');
            }
        });
    </script>
@endsection
