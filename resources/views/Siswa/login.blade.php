@extends('layouts.siswa')
@section('content')
    <div class="flex-1 flex flex-col justify-between px-5 py-8">
        <!-- Header Logo & Judul -->
        <div class="text-center mt-4">
            <div
                class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-[#E2E8F0]/70 border border-slate-200 flex items-center justify-center shadow-xs">
                <div
                    class="w-9 h-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-[26px] font-extrabold tracking-tight text-slate-900">CAT Informatika</h1>
            <p class="text-sm text-slate-500 mt-1">Berpikir Komputasional – Kelas X</p>
        </div>

        <!-- Card Form Login -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 my-6">
            <div class="flex items-center justify-between mb-5">
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">IDENTIFIKASI PESERTA</span>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-[#1D4ED8]"></span> Sistem Siap
                </span>
            </div>

            <form action="{{ route('siswa.login.post') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-bold text-slate-800">Kode Peserta</label>
                    <span class="text-xs text-slate-400">Wajib diisi</span>
                </div>

                <div class="relative mb-4">
                    <input type="text" id="inputKode" name="kode_peserta" value="{{ old('kode_peserta') }}"
                        placeholder="Contoh: MPK1-01" required autofocus
                        class="w-full bg-[#F1F5F9] border {{ $errors->any() ? 'border-red-300' : 'border-transparent focus:border-[#1D4ED8]' }} rounded-xl px-4 py-3.5 pr-10 font-mono-code text-sm font-semibold text-slate-800 uppercase outline-none transition">
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
                    class="w-full bg-[#1456E8] hover:bg-[#0F46C4] active:scale-[0.99] text-white font-bold py-3.5 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <span>Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-4 bg-[#F1F5F9]/80 rounded-xl px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-slate-500 text-xs font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                <span>SMKN 1 Pogalan</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Ruang Kelas – Sesi Penilaian Mandiri</p>
        </div>
    </div>
@endsection
