<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-checkmark {
            animation: checkmark 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <!-- Success Icon -->
            <div
                class="w-24 h-24 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6 animate-checkmark">
                <span class="text-5xl text-white">✓</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-gray-600 mb-8">
                Terima kasih telah mendaftar event <strong>{{ $participant->event->nama }}</strong>
            </p>

            <!-- Registration Details -->
            <div class="bg-gray-50 rounded-2xl p-6 text-left mb-6">
                <h3 class="font-semibold text-gray-700 mb-4">📋 Detail Pendaftaran</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama:</span>
                        <span class="font-medium">{{ $participant->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Event:</span>
                        <span class="font-medium">{{ $participant->event->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Event:</span>
                        <span class="font-medium">{{ $participant->event->tanggal_event->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status:</span>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                            ⏳ Menunggu Validasi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-2xl p-6 text-left mb-6">
                <h3 class="font-semibold text-blue-800 mb-3">📱 Langkah Selanjutnya:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-blue-700">
                    <li>Admin akan memverifikasi bukti pembayaran Anda</li>
                    <li>Tiket digital akan dikirim via WhatsApp</li>
                    <li>Simpan tiket untuk ditunjukkan saat event</li>
                </ol>
            </div>

            <!-- Contact Info -->
            <p class="text-sm text-gray-500 mb-6">
                Pertanyaan? Hubungi admin via WhatsApp
            </p>

            <a href="{{ route('home') }}"
                class="inline-block w-full py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition">
                🏠 Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>