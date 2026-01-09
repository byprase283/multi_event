<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Tidak Valid</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-5xl">❌</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Tiket Tidak Ditemukan</h1>
            <p class="text-gray-600 mb-8">
                Link tiket tidak valid atau sudah tidak berlaku.
            </p>

            <a href="{{ route('home') }}"
                class="inline-block w-full py-4 bg-gray-800 text-white rounded-xl font-bold hover:bg-gray-900 transition">
                🏠 Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>