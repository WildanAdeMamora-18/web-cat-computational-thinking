@extends('layouts.siswa')
@section('content')
    @php
        $menitAwal = str_pad(floor($sisaDetik / 60), 2, '0', STR_PAD_LEFT);
        $detikAwal = str_pad($sisaDetik % 60, 2, '0', STR_PAD_LEFT);
    @endphp

    <!-- Top App Bar dengan Logo UNESA -->
    <div
        class="bg-white/95 backdrop-blur-xs border-b border-slate-100 px-4 py-3 flex items-center justify-between sticky top-0 z-10 select-none">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#0F172A] p-1.5 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/logo-unesa.png') }}" alt="Logo UNESA" class="w-full h-full object-contain">
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-tight">Lembar Ujian Aktif</p>
                <p class="text-[11px] text-slate-400">Sesi Ujian Adaptif CAT</p>
            </div>
        </div>
        <div class="bg-blue-50 text-[#1456E8] text-[11px] font-bold px-2.5 py-1 rounded-lg border border-blue-100">
            Terpantau
        </div>
    </div>

    <!-- Area Soal Anti-Salin -->
    <div class="p-4 space-y-4 select-none" oncontextmenu="return false;" oncopy="return false;" oncut="return false;"
        ondragstart="return false;">
        <!-- Bar Nomor Soal & Timer Real-Time dari Server -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#1456E8] animate-pulse"></span>
                <h2 class="text-lg font-extrabold text-slate-900">Soal ke-{{ $nomorUrut }}</h2>
            </div>
            <div id="badgeTimer"
                class="bg-[#EEF2FF] text-[#1456E8] border border-blue-100 px-3.5 py-1.5 rounded-full flex items-center gap-1.5 font-mono-code text-xs font-bold shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" d="M12 7v5l3 2" />
                </svg>
                <span id="timerTeks">{{ $menitAwal }} : {{ $detikAwal }}</span>
            </div>
        </div>

        <!-- Kartu Stimulus Soal -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div
                class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200/70 rounded-lg px-2.5 py-1 text-[10px] font-bold tracking-wider text-slate-600 uppercase mb-3.5">
                <svg class="w-3.5 h-3.5 text-[#1456E8]" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $soal->indikator }}</span>
            </div>

            <div class="teks-soal text-[14.5px] text-slate-800 leading-relaxed">{{ $soal->teks_soal }}</div>

            @if ($soal->gambar)
                <div class="mt-4 bg-[#F8FAFC] rounded-2xl p-3 border border-slate-200/70 text-center">
                    <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Diagram Soal"
                        class="max-h-56 mx-auto rounded-lg pointer-events-none">
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
                        class="opsi-card cursor-pointer bg-white hover:border-blue-300 border border-slate-200/90 rounded-2xl p-3.5 flex items-center justify-between gap-3 transition select-none shadow-2xs">
                        <input type="radio" name="jawaban" value="{{ $huruf }}" class="hidden" required>
                        <div class="flex items-start gap-3">
                            <span
                                class="badge-huruf w-8 h-8 rounded-xl bg-[#EEF2FF] text-slate-800 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 transition">
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
                class="mt-5 w-full bg-gradient-to-r from-[#1456E8] to-[#1D4ED8] hover:from-[#0F46C4] hover:to-[#1E40AF] text-white font-bold py-4 rounded-2xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                <span>Kirim Jawaban</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>
    </div>

    <script>
        // 1. Efek highlight biru saat opsi dipilih
        document.querySelectorAll('.opsi-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.opsi-card').forEach(c => {
                    c.classList.remove('bg-[#1A5CE5]', 'border-[#1A5CE5]', 'shadow-md');
                    c.classList.add('bg-white', 'border-slate-200/90');
                    c.querySelector('.badge-huruf').classList.remove('bg-white', 'text-[#1A5CE5]');
                    c.querySelector('.badge-huruf').classList.add('bg-[#EEF2FF]', 'text-slate-800');
                    c.querySelector('.teks-opsi').classList.remove('text-white', 'font-medium');
                    c.querySelector('.teks-opsi').classList.add('text-slate-700');
                    c.querySelector('.radio-dot').classList.remove('bg-white', 'border-white');
                    c.querySelector('.radio-dot').classList.add('bg-[#EEF2FF]', 'border-blue-200');
                    c.querySelector('.dot-inner').classList.add('hidden');
                });

                card.classList.remove('bg-white', 'border-slate-200/90');
                card.classList.add('bg-[#1A5CE5]', 'border-[#1A5CE5]', 'shadow-md');
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

        // 2. Timer presisi berbasis timestamp target (anti-delay & sinkron server)
        let sisaDetik = {{ $sisaDetik }};
        const waktuTarget = Date.now() + (sisaDetik * 1000);
        const timerEl = document.getElementById('timerTeks');
        const badgeTimer = document.getElementById('badgeTimer');

        const intervalTimer = setInterval(() => {
            const selisih = Math.max(0, Math.floor((waktuTarget - Date.now()) / 1000));
            const m = String(Math.floor(selisih / 60)).padStart(2, '0');
            const s = String(selisih % 60).padStart(2, '0');
            timerEl.textContent = `${m} : ${s}`;

            if (selisih <= 300) {
                badgeTimer.classList.remove('bg-[#EEF2FF]', 'text-[#1456E8]');
                badgeTimer.classList.add('bg-red-50', 'text-red-600', 'border-red-200');
            }

            if (selisih <= 0) {
                clearInterval(intervalTimer);
                window.location.reload(); // Server otomatis menutup sesi karena waktu habis
            }
        }, 500);

        // 3. Blokir shortcut copy/select/inspectmpilsoal
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && ['c', 'x', 'a', 'u', 's', 'p'].includes(e.key.toLowerCase())) {
                e.preventDefault();
            }
        });

        // 4. Deteksi siswa keluar tab / pindah aplikasi & laporkan ke Admin
        let sedangSubmit = false;
        document.getElementById('formJawaban').addEventListener('submit', function() {
            sedangSubmit = true;
            const btn = document.getElementById('btnKirim');
            btn.disabled = true;
            btn.innerHTML = '<span>Menyimpan Respons...</span>';
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden && !sedangSubmit) {
                fetch("{{ route('ujian.keluar_tab') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    keepalive: true
                });
            }
        });

        // 5. Cegah tombol Back browser
        history.pushState(null, null, location.href);
        window.onpopstate = () => history.go(1);
    </script>
@endsection
