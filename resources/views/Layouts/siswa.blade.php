<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CAT Informatika - Berpikir Komputasional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(#CBD5E1 0.85px, transparent 0.85px);
            background-size: 22px 22px;
            color: #0F172A;
        }

        .font-mono-code {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
        }

        .teks-soal {
            white-space: pre-line;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-masuk {
            animation: fadeSlideUp 0.28s ease-out forwards;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-start sm:justify-center bg-[#F8FAFC]">
    <div class="w-full max-w-[440px] min-h-screen sm:min-h-0 bg-[#F8FAFC] flex flex-col">
        @yield('content')
    </div>
</body>

</html>
