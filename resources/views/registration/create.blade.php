<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - {{ $event->nama }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white py-8">
        <div class="container mx-auto px-4">
            <a href="{{ route('home') }}" class="inline-flex items-center text-white/80 hover:text-white mb-4">
                <span class="mr-2">←</span> Kembali
            </a>
            <h1 class="text-3xl font-bold">📝 Form Pendaftaran</h1>
            <p class="opacity-90">{{ $event->nama }}</p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
                        id="registrationForm">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <!-- Data Pribadi -->
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span>👤</span> Data Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('nama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                                <select name="jenis_kelamin" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="">Pilih</option>
                                    <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria
                                    </option>
                                    <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Usia *</label>
                                <input type="number" name="usia" value="{{ old('usia') }}" min="1" max="120" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    placeholder="Masukkan usia">
                                @error('usia')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp *</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    placeholder="08xxxxxxxxxx">
                                @error('whatsapp')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                            <textarea name="alamat" rows="3" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Jersey</label>
                            <select name="ukuran_jersey"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih ukuran</option>
                                <option value="XS" {{ old('ukuran_jersey') == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('ukuran_jersey') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('ukuran_jersey') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('ukuran_jersey') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('ukuran_jersey') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('ukuran_jersey') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                        </div>

                        <!-- Voucher -->
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span>🎫</span> Kode Voucher
                        </h3>

                        <div class="mb-8">
                            <div class="flex gap-4">
                                <input type="text" name="kode_voucher" id="voucherInput"
                                    value="{{ old('kode_voucher') }}"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    placeholder="Masukkan kode voucher (opsional)">
                                <button type="button" id="checkVoucherBtn"
                                    class="px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium">
                                    Cek
                                </button>
                            </div>
                            <div id="voucherResult" class="mt-2"></div>
                        </div>

                        <!-- Pembayaran -->
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span>💳</span> Pembayaran
                        </h3>

                        @if($event->bank_account)
                            <!-- Bank Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                                <h4 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                                    <span>🏦</span> Transfer ke Rekening Berikut:
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Bank:</span>
                                        <span class="font-semibold text-gray-800">{{ $event->bank_name ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">No. Rekening:</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-blue-700 font-mono"
                                                id="bankAccount">{{ $event->bank_account }}</span>
                                            <button type="button" onclick="copyBankAccount()"
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                                                <span id="copyIcon">📋</span>
                                                <span id="copyText">Salin</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Atas Nama:</span>
                                        <span class="font-semibold text-gray-800">{{ $event->bank_holder ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Biaya Pendaftaran:</span>
                                <span class="font-medium" id="hargaAsli">Rp
                                    {{ number_format($event->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2 text-green-600" id="diskonRow"
                                style="display: none;">
                                <span>Diskon Voucher:</span>
                                <span id="diskonNominal">- Rp 0</span>
                            </div>
                            <hr class="my-3">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total Bayar:</span>
                                <span class="text-primary-600" id="totalBayar">Rp
                                    {{ number_format($event->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Bayar *</label>
                            <input type="file" name="bukti_bayar" accept="image/*" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                            @error('bukti_bayar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-bold text-lg hover:from-primary-600 hover:to-primary-700 transition shadow-lg">
                            ✅ Kirim Pendaftaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Event Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                    <div
                        class="h-40 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl mb-4 flex items-center justify-center">
                        @if($event->gambar)
                            <img src="{{ asset('storage/' . $event->gambar) }}"
                                class="w-full h-full object-cover rounded-xl">
                        @else
                            <span class="text-5xl">🎪</span>
                        @endif
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $event->nama }}</h3>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">📅</span>
                            <span>{{ $event->tanggal_event->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-lg">📍</span>
                            <span>{{ $event->lokasi ?? 'TBA' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-lg">👥</span>
                            <span>Sisa {{ $event->remainingSlots() }} slot</span>
                        </div>
                    </div>

                    @if($event->deskripsi)
                        <hr class="my-4">
                        <p class="text-sm text-gray-600">{{ $event->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const eventHarga = {{ $event->harga }};
        let currentDiskon = 0;

        // Check voucher
        document.getElementById('checkVoucherBtn').addEventListener('click', function () {
            const kode = document.getElementById('voucherInput').value;
            const resultDiv = document.getElementById('voucherResult');

            if (!kode) {
                resultDiv.innerHTML = '<p class="text-red-500">Masukkan kode voucher</p>';
                return;
            }

            fetch('{{ route("api.check-voucher") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    kode: kode,
                    event_id: {{ $event->id }}
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.valid) {
                        resultDiv.innerHTML = '<p class="text-green-600">✅ ' + data.message + '</p>';
                        currentDiskon = data.nominal;
                        updateHarga();
                    } else {
                        resultDiv.innerHTML = '<p class="text-red-500">❌ ' + data.message + '</p>';
                        currentDiskon = 0;
                        updateHarga();
                    }
                });
        });

        function updateHarga() {
            const total = Math.max(0, eventHarga - currentDiskon);
            document.getElementById('totalBayar').textContent = 'Rp ' + total.toLocaleString('id-ID');

            if (currentDiskon > 0) {
                document.getElementById('diskonRow').style.display = 'flex';
                document.getElementById('diskonNominal').textContent = '- Rp ' + currentDiskon.toLocaleString('id-ID');
            } else {
                document.getElementById('diskonRow').style.display = 'none';
            }
        }

        // Copy bank account to clipboard
        function copyBankAccount() {
            const bankAccount = document.getElementById('bankAccount').textContent;
            navigator.clipboard.writeText(bankAccount).then(() => {
                // Change button to show success
                document.getElementById('copyIcon').textContent = '✅';
                document.getElementById('copyText').textContent = 'Tersalin!';

                // Reset after 2 seconds
                setTimeout(() => {
                    document.getElementById('copyIcon').textContent = '📋';
                    document.getElementById('copyText').textContent = 'Salin';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin: ' + err);
            });
        }
    </script>
</body>

</html>