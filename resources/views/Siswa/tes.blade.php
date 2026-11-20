@extends('layouts.siswa')
@section('content')
    <!-- Top App Bar -->
    <div class="bg-white border-b border-slate-100 px-4 py-3 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <div class="text-slate-400 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </div>
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
        <!-- Bar Nomor Soal & Timer -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#1456E8]"></span>
                <h2 class="text-lg font-extrabold text-slate-900">Soal ke-{{ $nomorUrut }}</h2>
            </div>
            <div
                class="bg-[#EEF2FF] text-[#1456E8] px-3 py-1.5 rounded-full flex items-center gap-1.5 font-mono-code text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" d="M12 7v5l3 2" />
                </svg>
                <span id="timerTeks">60 : 00</span>
            </div>
        </div>

        <!-- Kartu Stimulus Soal -->
        <div class="bg-white rounded-3xl p-5 shadow-xs border border-slate-100">
            <div class="flex items-center gap-2 text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-3">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $soal->indikator }}</span>
            </div>

            <div class="teks-soal text-[14.5px] text-slate-800 leading-relaxed">{{ $soal->teks_soal }}</div>

            @if ($soal->gambar)
                <div class="mt-4 bg-[#F1F5F9] rounded-2xl p-3 border border-slate-200/70 text-center">
                    <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Diagram Soal"
                        class="max-h-56 mx-auto rounded-lg">
                </div>
            @endif
        </div>

        <!-- Opsi Jawaban A - E -->
        <form action="{{ route('ujian.submit') }}" method="POST" id="formJawaban">
            @csrf
            <div class="flex items-center justify-between mb-2.5 px-1">
                <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">OPSI JAWABAN</span>
                <span class="text-xs text-slate-400">Pilih 1 jawaban tepat</span>
            </div>

            <div class="space-y-2.5" id="opsiContainer">
                @foreach (['A', 'B', 'C', 'D', 'E'] as $huruf)
                    @php $kolom = 'opsi_' . strtolower($huruf); @endphp
                    <label
                        class="opsi-card cursor-pointer bg-white border border-slate-200/80 rounded-2xl p-3.5 flex items-center justify-between gap-3 transition select-none">
                        <input type="radio" name="jawaban" value="{{ $huruf }}" class="hidden" required>
                        <div class="flex items-start gap-3">
                            <span
                                class="badge-huruf w-8 h-8 rounded-lg bg-[#EEF2FF] text-slate-800 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 transition">
                                {{ $huruf }}
                            </span>
                            <span
                                class="teks-opsi text-[13.5px] text-slate-700 leading-snug pt-1">{{ $soal->$kolom }}</span>
                        </div>
                        <span
                            class="radio-dot w-5 h-5 rounded-full bg-[#EEF2FF] border border-blue-200 flex items-center justify-center shrink-0 transition">
                            <span class="dot-inner w-2.5 h-2.5 rounded-full bg-white hidden"></span>
                        </span>
                    </label>
                @endforeach
            </div>

            <button type="submit" id="btnKirim"
                class="mt-5 w-full bg-[#1456E8] hover:bg-[#0F46C4] text-white font-bold py-4 rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
                <span>Kirim Jawaban</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>
    </div>

    <script>
        // Efek highlight biru saat opsi dipilih (sesuai mockup)
        document.querySelectorAll('.opsi-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.opsi-card').forEach(c => {
                    c.classList.remove('bg-[#1A5CE5]', 'border-[#1A5CE5]', 'shadow-sm');
                    c.classList.add('bg-white', 'border-slate-200/80');
                    c.querySelector('.badge-huruf').classList.remove('bg-white', 'text-[#1A5CE5]');
                    c.querySelector('.badge-huruf').classList.add('bg-[#EEF2FF]', 'text-slate-800');
                    c.querySelector('.teks-opsi').classList.remove('text-white', 'font-medium');
                    c.querySelector('.teks-opsi').classList.add('text-slate-700');
                    c.querySelector('.radio-dot').classList.remove('bg-white', 'border-white');
                    c.querySelector('.radio-dot').classList.add('bg-[#EEF2FF]', 'border-blue-200');
                    c.querySelector('.dot-inner').classList.add('hidden');
                });

                card.classList.remove('bg-white', 'border-slate-200/80');
                card.classList.add('bg-[#1A5CE5]', 'border-[#1A5CE5]', 'shadow-sm');
                card.querySelector('.badge-huruf').classList.remove('bg-[#EEF2FF]', 'text-slate-800');
                card.querySelector('.badge-huruf').classList.add('bg-white', 'text-[#1A5CE5]');
                card.querySelector('.teks-opsi').classList.remove('text-slate-700');
                card.querySelector('.teks-opsi').classList.add('text-white', 'font-medium');
                card.querySelector('.radio-dot').classList.remove('bg-[#EEF2FF]', 'border-blue-200');
                card.querySelector('.radio-dot').classList.add('bg-white', 'border-white');
                card.querySelector('.dot-inner').classList.remove('hidden');
                card.querySelector('.dot-inner').classList.add('bg-[#1A5CE5]');
            });
        });

        // Timer sederhana 60 menit tersimpan di sessionStorage
        let sisaDetik = parseInt(sessionStorage.getItem('cat_sisa_detik') || 3600);
        const timerEl = document.getElementById('timerTeks');
        setInterval(() => {
            if (sisaDetik > 0) sisaDetik--;
            sessionStorage.setItem('cat_sisa_detik', sisaDetik);
            const m = String(Math.floor(sisaDetik / 60)).padStart(2, '0');
            const s = String(sisaDetik % 60).padStart(2, '0');
            timerEl.textContent = `${m} : ${s}`;
        }, 1000);

        document.getElementById('formJawaban').addEventListener('submit', function() {
            const btn = document.getElementById('btnKirim');
            btn.disabled = true;
            btn.innerHTML = '<span>Menyimpan Respons...</span>';
        });

        history.pushState(null, null, location.href);
        window.onpopstate = () => history.go(1);
    </script>
@endsection
