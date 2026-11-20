@extends('layouts.siswa')
@section('content')
    <!-- Top App Bar -->
    <div class="bg-white border-b border-slate-100 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.login') }}" class="text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-tight">Konfirmasi Selesai</p>
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

    <div class="flex-1 flex flex-col justify-center px-5 py-10 text-center">
        <!-- Ikon Centang -->
        <div class="w-20 h-20 mx-auto mb-6 rounded-3xl bg-[#EEF2FF] flex items-center justify-center">
            <div class="w-14 h-14 rounded-2xl bg-white shadow-xs flex items-center justify-center text-[#1456E8]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900">Tes Selesai</h1>
        <p class="text-sm text-slate-500 mt-2 max-w-[290px] mx-auto leading-relaxed">
            Terima kasih, jawabanmu sudah tersimpan. Hasil akan disampaikan oleh gurumu.
        </p>

        <!-- Kartu Ringkasan Ujian -->
        <div class="bg-[#F1F5F9]/90 border border-slate-200/60 rounded-2xl p-4 mt-7 text-left">
            <div class="flex items-center gap-2 text-[11px] font-bold tracking-wider text-slate-500 uppercase mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="16" rx="2" />
                    <path d="M7 8h10M7 12h6" />
                </svg>
                <span>RINGKASAN UJIAN</span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="bg-white rounded-xl px-3.5 py-2.5 flex items-center justify-between">
                    <span class="text-slate-500">Kode Peserta</span>
                    <span
                        class="font-mono-code font-bold text-slate-900">{{ $hasilTes?->siswa?->kode_peserta ?? '-' }}</span>
                </div>
                <div class="bg-white rounded-xl px-3.5 py-2.5 flex items-center justify-between">
                    <span class="text-slate-500">Jumlah Soal Dikerjakan</span>
                    <span class="font-bold text-slate-900">{{ $hasilTes?->jumlah_butir ?? 0 }} Soal</span>
                </div>
                <div class="bg-white rounded-xl px-3.5 py-2.5 flex items-center justify-between">
                    <span class="text-slate-500">Status Pengiriman</span>
                    <span class="font-bold text-[#1456E8] inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Tersinkronisasi
                    </span>
                </div>
            </div>
        </div>

        <a href="{{ route('siswa.login') }}" onclick="sessionStorage.removeItem('cat_sisa_detik')"
            class="mt-7 w-full bg-[#1456E8] hover:bg-[#0F46C4] text-white font-bold py-3.5 rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
            <span>Keluar</span>
        </a>

        <p class="text-[11px] text-slate-400 mt-3">Sesi Anda telah aman ditutup pada perangkat ini.</p>
    </div>
@endsection
