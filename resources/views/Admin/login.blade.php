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

<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white rounded-3xl p-7 shadow-sm border border-slate-100">
        <div class="text-center mb-6">
            <div
                class="w-12 h-12 rounded-2xl bg-[#EEF2FF] text-[#1456E8] mx-auto mb-3 flex items-center justify-center font-extrabold">
                CAT</div>
            <h1 class="text-xl font-extrabold text-slate-900">Panel Admin CAT</h1>
            <p class="text-xs text-slate-400 mt-1">SMKN 1 Pogalan • Administrasi Penelitian</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 text-xs font-semibold p-3 rounded-xl mb-4">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Administrator</label>
                <input type="email" name="email" value="admin@cat.test" required
                    class="w-full bg-[#F1F5F9] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#1456E8]">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                <input type="password" name="password" placeholder="••••••••" required
                    class="w-full bg-[#F1F5F9] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#1456E8]">
            </div>
            <button type="submit"
                class="w-full bg-[#1456E8] hover:bg-[#0F46C4] text-white font-bold py-3 rounded-xl text-sm transition">
                Masuk ke Dashboard
            </button>
        </form>
    </div>
</body>

</html>
