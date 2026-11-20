<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAT Informatika - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }

        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>

<body class="min-h-screen flex bg-[#F8FAFC]">
    <!-- Sidebar Kiri -->
    <aside
        class="w-64 bg-white border-r border-slate-200/80 flex flex-col justify-between shrink-0 sticky top-0 h-screen">
        <div>
            <!-- Logo Brand -->
            <div class="px-5 py-5 border-b border-slate-100 flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-extrabold tracking-tight text-slate-900 leading-none">CAT INFORMATIKA</p>
                    <p class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase mt-1">PANEL ADMIN</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="p-3 space-y-1">
                <a href="{{ route('admin.siswa') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition {{ request()->routeIs('admin.siswa') ? 'bg-[#E8EEF9] text-[#0F172A] border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.siswa') ? 'text-[#1456E8]' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Data Siswa</span>
                </a>

                <a href="{{ route('admin.soal') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition {{ request()->routeIs('admin.soal') ? 'bg-[#E8EEF9] text-[#0F172A] border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.soal') ? 'text-[#1456E8]' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Bank Soal</span>
                </a>

                <a href="{{ route('admin.sesi') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition {{ request()->routeIs('admin.sesi') ? 'bg-[#E8EEF9] text-[#0F172A] border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.sesi') ? 'text-[#1456E8]' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l3 2" />
                    </svg>
                    <span>Sesi Tes</span>
                </a>

                <a href="{{ route('admin.hasil') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition {{ request()->routeIs('admin.hasil') ? 'bg-[#E8EEF9] text-[#0F172A] border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.hasil') ? 'text-[#1456E8]' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Hasil Tes</span>
                </a>
            </nav>
        </div>

        <!-- Tombol Keluar Bawah -->
        <div class="p-4 border-t border-slate-100">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2.5 px-3 py-2 text-[13px] font-semibold text-red-600 hover:bg-red-50 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Header -->
        <header
            class="h-16 bg-white border-b border-slate-200/80 px-7 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-2 text-[11px] font-bold tracking-wider uppercase">
                <span class="text-slate-400">SISTEM</span>
                <span class="text-slate-300">›</span>
                <span class="text-slate-800">ADMINISTRASI CAT</span>
            </div>

            <div class="flex items-center gap-5">
                <div
                    class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200/80 rounded-full px-3 py-1">
                    <span class="w-2 h-2 rounded-full bg-[#1456E8]"></span>
                    <span class="text-[11px] font-bold tracking-wide text-slate-700">SERVER ONLINE</span>
                </div>

                <div class="h-6 w-[1px] bg-slate-200"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-900 leading-none">Administrator</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Proktor Pusat</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-[#1456E8] text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" />
                        </svg>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten Halaman -->
        <main class="p-7 max-w-[1200px] w-full mx-auto space-y-6">
            @if (session('sukses'))
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl flex items-center justify-between">
                    <span>{{ session('sukses') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">✕</button>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-semibold px-4 py-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
