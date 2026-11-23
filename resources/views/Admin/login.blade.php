<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CAT Informatika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-slate-100 via-[#F8FAFC] to-blue-50/60 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white rounded-3xl p-7 shadow-lg shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-6">
            <div
                class="w-16 h-16 rounded-2xl bg-[#0F172A] p-2 mx-auto mb-3.5 flex items-center justify-center shadow-md ring-4 ring-blue-50">
                <img src="{{ asset('images/logo-unesa.png') }}" alt="Logo UNESA" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-extrabold text-slate-900">Panel Admin CAT</h1>
            <p class="text-xs text-slate-400 mt-1">SMKN 1 Pogalan • Administrasi Penelitian</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 text-xs font-semibold p-3 rounded-xl mb-4">
                {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Admin</label>
                <input type="email" name="email" value="admin@cat.test" required
                    class="w-full bg-[#F1F5F9] border border-transparent focus:border-[#1456E8] focus:bg-white rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <input type="password" id="adminPassword" name="password" placeholder="••••••••" required
                        class="w-full bg-[#F1F5F9] border border-transparent focus:border-[#1456E8] focus:bg-white rounded-xl px-4 py-3 pr-11 text-sm outline-none transition">
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700"
                        title="Tampilkan/Sembunyikan Sandi">
                        <!-- Ikon Mata Tercoret (Default saat password tersembunyi) -->
                        <svg id="iconMataTutup" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                        <!-- Ikon Mata Terbuka (Muncul saat password terlihat) -->
                        <svg id="iconMataBuka" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>
            <button type="submit"
                class="w-full bg-gradient-to-r from-[#1456E8] to-[#1D4ED8] hover:from-[#0F46C4] hover:to-[#1E40AF] text-white font-bold py-3.5 rounded-xl text-sm shadow-md shadow-blue-500/20 transition">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('adminPassword');
            const mataTutup = document.getElementById('iconMataTutup');
            const mataBuka = document.getElementById('iconMataBuka');

            if (input.type === 'password') {
                input.type = 'text';
                mataTutup.classList.add('hidden');
                mataBuka.classList.remove('hidden');
            } else {
                input.type = 'password';
                mataTutup.classList.remove('hidden');
                mataBuka.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
