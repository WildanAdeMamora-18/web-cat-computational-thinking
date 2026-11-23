@extends('layouts.siswa')
@section('content')
    <div class="flex-1 flex flex-col justify-between px-5 py-8">
        <!-- Header Logo UNESA & Judul -->
        <div class="text-center mt-4">
            <div
                class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-[#0F172A] p-2.5 flex items-center justify-center shadow-md ring-4 ring-blue-50">
                <img src="{{ asset('images/logo-unesa.png') }}" alt="Logo UNESA"
                    class="w-full h-full object-contain rounded-lg">
            </div>
            <span
                class="inline-block bg-blue-50 text-[#1456E8] text-[10px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-full mb-2">
                Universitas Negeri Surabaya
            </span>
            <h1 class="text-[25px] font-extrabold tracking-tight text-slate-900">CAT Informatika</h1>
            <p class="text-sm text-slate-500 mt-1">Berpikir Komputasional – Kelas X</p>
        </div>

        <!-- Card Form Login -->
        <div class="bg-white rounded-3xl p-6 shadow-md shadow-slate-200/50 border border-slate-100 my-6">
            <div class="flex items-center justify-between mb-5">
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">IDENTIFIKASI PESERTA</span>
                <span
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Siap
                </span>
            </div>

            <form action="{{ route('siswa.login.post') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-bold text-slate-800">Kode Peserta</label>
                    <span class="text-xs text-slate-400">Format 3 digit</span>
                </div>

                <div class="relative mb-4">
                    <input type="text" id="inputKode" name="kode_peserta" value="{{ old('kode_peserta') }}"
                        placeholder="Contoh: MPK1-001" required autofocus
                        class="w-full bg-[#F1F5F9] border {{ $errors->any() ? 'border-red-300' : 'border-slate-200/60 focus:border-[#1456E8] focus:bg-white' }} rounded-xl px-4 py-3.5 pr-10 font-mono-code text-sm font-bold text-slate-800 uppercase outline-none transition">
                    <button type="button"
                        onclick="document.getElementById('inputKode').value=''; document.getElementById('inputKode').focus();"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" d="M15 9l-6 6m0-6l6 6" />
                        </svg>
                    </button>
                </div>

                @if ($errors->any())
                    <div class="bg-[#FEF2F2] border border-red-100 rounded-xl p-3.5 mb-4 flex items-start gap-3">
                        <div
                            class="w-5 h-5 rounded-full bg-[#DC2626] text-white flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                            !</div>
                        <div>
                            <p class="text-xs font-bold text-[#B91C1C]">Kode tidak valid</p>
                            <p class="text-xs text-[#B91C1C]/90 mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#1456E8] to-[#1D4ED8] hover:from-[#0F46C4] hover:to-[#1E40AF] active:scale-[0.99] text-white font-bold py-3.5 rounded-xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                    <span>Masuk ke Ujian</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-4 bg-[#F8FAFC] border border-slate-100 rounded-xl px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-slate-500 text-xs font-medium">
                    <svg class="w-4 h-4 text-[#1456E8]" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l3 2" />
                    </svg>
                    <span>Durasi Sesi</span>
                </div>
                <span class="text-xs font-bold text-slate-800">60 Menit</span>
            </div>
        </div>

        <!-- Footer Sekolah -->
        <div class="text-center pb-2">
            <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1456E8]"></span>
                <span>SMKN 1 Pogalan</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#1456E8]"></span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Ruang Kelas – Sesi Penilaian Mandiri</p>
        </div>
    </div>
@endsection
