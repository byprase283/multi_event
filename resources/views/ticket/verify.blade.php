<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tiket - {{ $participant->event->nama }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .ticket-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Ticket Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg ticket-pattern text-white p-6 text-center">
                <h1 class="text-2xl font-bold mb-1">🎫 E-TICKET</h1>
                <p class="opacity-90">{{ $participant->event->nama }}</p>
            </div>

            <!-- Status Badge -->
            <div class="flex justify-center -mt-4">
                @if($participant->status_verifikasi === 'Valid')
                    <span
                        class="bg-green-500 text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                        ✅ VALID
                    </span>
                @elseif($participant->status_verifikasi === 'Redeem')
                    <span class="bg-blue-500 text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                        🎉 SUDAH DIGUNAKAN
                    </span>
                @elseif($participant->status_verifikasi === 'Pending')
                    <span
                        class="bg-yellow-500 text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                        ⏳ MENUNGGU VALIDASI
                    </span>
                @else
                    <span class="bg-red-500 text-white px-6 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                        ❌ TIDAK VALID
                    </span>
                @endif
            </div>

            <!-- Ticket Info -->
            <div class="p-6">
                <!-- Registration Code -->
                @if($participant->kode_registrasi)
                    <div class="bg-gray-50 rounded-xl p-4 mb-6 text-center">
                        <p class="text-sm text-gray-500 mb-1">Kode Registrasi</p>
                        <p class="text-2xl font-bold text-gray-800 font-mono">{{ $participant->kode_registrasi }}</p>
                    </div>
                @endif

                <!-- Participant Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-2xl">👤</span>
                        <div>
                            <p class="text-xs text-gray-500">Nama Peserta</p>
                            <p class="font-semibold">{{ $participant->nama }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-2xl">📅</span>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Event</p>
                            <p class="font-semibold">{{ $participant->event->tanggal_event->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <span class="text-2xl">📍</span>
                        <div>
                            <p class="text-xs text-gray-500">Lokasi</p>
                            <p class="font-semibold">{{ $participant->event->lokasi ?? 'TBA' }}</p>
                        </div>
                    </div>

                    @if($participant->ukuran_jersey)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <span class="text-2xl">👕</span>
                            <div>
                                <p class="text-xs text-gray-500">Ukuran Jersey</p>
                                <p class="font-semibold">{{ $participant->ukuran_jersey }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dashed Separator -->
                <div class="my-6 border-t-2 border-dashed border-gray-200 relative">
                    <div class="absolute -left-6 -top-3 w-6 h-6 bg-gray-100 rounded-full"></div>
                    <div class="absolute -right-6 -top-3 w-6 h-6 bg-gray-100 rounded-full"></div>
                </div>

                <!-- QR Code -->
                <div class="text-center">
                    @if($participant->token_hash && $participant->status_verifikasi === 'Valid')
                        <div class="inline-block p-4 bg-gray-100 rounded-xl">
                            <div id="qrcode" class="bg-white p-2 rounded-lg"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Scan QR code ini saat registrasi ulang</p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">{{ substr($participant->token_hash, 0, 16) }}...</p>
                    @elseif($participant->status_verifikasi === 'Redeem')
                        <div class="inline-block p-4 bg-blue-100 rounded-xl">
                            <div class="w-32 h-32 flex items-center justify-center">
                                <span class="text-5xl">🎉</span>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2 font-semibold">Tiket sudah digunakan</p>
                    @else
                        <div class="inline-block p-4 bg-gray-100 rounded-xl">
                            <div
                                class="w-32 h-32 bg-white flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                                <span class="text-3xl">⏳</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">QR Code akan muncul setelah validasi</p>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-4 text-center">
                <p class="text-xs text-gray-500">
                    Validated at:
                    {{ $participant->tgl_validasi ? $participant->tgl_validasi->format('d M Y H:i') : '-' }}
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                ← Kembali ke beranda
            </a>
        </div>
    </div>
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        @if($participant->token_hash && $participant->status_verifikasi === 'Valid')
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $participant->token_hash }}",
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        @endif
    </script>
</body>

</html>