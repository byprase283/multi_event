<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Multi Event') }} - Pendaftaran Event</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-float"
                style="animation-delay: -3s;"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                    🎉 Multi Event Registration
                </h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Daftarkan diri Anda untuk event-event menarik yang akan datang!
                </p>

                <div class="mt-8 flex justify-center gap-4">
                    <a href="#events"
                        class="bg-white text-primary-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition shadow-lg">
                        Lihat Event
                    </a>
                    <a href="{{ route('admin.login') }}"
                        class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-primary-600 transition">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Events Section -->
    <section id="events" class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">📅 Event Mendatang</h2>
                <p class="text-gray-600 max-w-xl mx-auto">
                    Pilih event yang ingin Anda ikuti dan daftar sekarang!
                </p>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                            <!-- Event Image -->
                            <div class="relative h-48 bg-gradient-to-br from-primary-400 to-primary-600">
                                @if($event->gambar)
                                    <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->nama }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-6xl">🎪</span>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                @if($event->hasAvailableSlots())
                                    <div
                                        class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        TERSEDIA
                                    </div>
                                @else
                                    <div
                                        class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        PENUH
                                    </div>
                                @endif
                            </div>

                            <!-- Event Info -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->nama }}</h3>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center gap-2">
                                        <span>📅</span>
                                        <span>{{ $event->tanggal_event->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>📍</span>
                                        <span>{{ $event->lokasi ?? 'TBA' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>👥</span>
                                        <span>{{ $event->terisi }}/{{ $event->kuota }} peserta</span>
                                    </div>
                                </div>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($event->deskripsi, 100) }}
                                </p>

                                <!-- Price -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($event->harga > 0)
                                            <span class="text-2xl font-bold text-primary-600">
                                                Rp {{ number_format($event->harga, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-2xl font-bold text-green-600">GRATIS</span>
                                        @endif
                                    </div>

                                    @if($event->hasAvailableSlots())
                                        <a href="{{ route('registration.create', $event) }}"
                                            class="bg-primary-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-primary-700 transition">
                                            Daftar
                                        </a>
                                    @else
                                        <button disabled
                                            class="bg-gray-300 text-gray-500 px-6 py-2 rounded-full font-semibold cursor-not-allowed">
                                            Penuh
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Event</h3>
                    <p class="text-gray-500">Event akan segera ditambahkan. Pantau terus halaman ini!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">
                &copy; {{ date('Y') }} Multi Event Registration. Dibuat untuk pembelajaran SMK/SMA.
            </p>
        </div>
    </footer>
</body>

</html>