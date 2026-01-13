# Panduan Lengkap Pengembangan Sistem Registrasi Multi-Event (Zero to Hero)

Dokumen ini adalah panduan langkah demi langkah untuk membangun sistem registrasi event dari nol sampai jadi, menggunakan **Laravel 12**, **Tailwind CSS**, dan **MySQL**.

---

## 📅 Daftar Isi
1. [Persiapan Software (Tools)](#1-persiapan-software-tools)
2. [Instalasi Proyek Laravel](#2-instalasi-proyek-laravel)
3. [Konfigurasi & Database](#3-konfigurasi--database)
4. [Migration, Model, Seeder](#4-migration-model-seeder)
5. [Controller & Route](#5-controller--route)
6. [Setup & Views](#6-setup--views)
7. [Menjalankan Aplikasi](#7-menjalankan-aplikasi)

---

## 1. Persiapan Software (Tools)

Sebelum mulai, pastikan software berikut sudah terinstall di komputer Anda:

1.  **XAMPP** (Untuk PHP & MySQL Database)
    *   Download: [apachefriends.org](https://www.apachefriends.org/download.html)
    *   *Pastikan pilih versi PHP 8.2 ke atas.*
2.  **Composer** (Untuk install Laravel)
    *   Download: [getcomposer.org](https://getcomposer.org/download/)
3.  **Node.js LTS** (Untuk compile CSS/JS Frontend)
    *   Download: [nodejs.org](https://nodejs.org/)
4.  **Git** (Untuk version control)
    *   Download: [git-scm.com](https://git-scm.com/)
5.  **Visual Studio Code** (Text Editor)
    *   Download: [code.visualstudio.com](https://code.visualstudio.com/)

**Cek Instalasi:**
Buka Terminal / CMD, ketik perintah ini satu per satu untuk memastikan:
```bash
php -v
composer -v
node -v
npm -v
```

---

## 2. Instalasi Proyek Laravel

Buka terminal (bisa di VS Code atau CMD), arahkan ke folder tempat Anda ingin menyimpan proyek (misal `D:\Coding`).

1.  **Buat Proyek Baru:**
    ```bash
    composer create-project laravel/laravel:^12.0 multi-event
    ```

2.  **Masuk ke Folder Proyek:**
    ```bash
    cd multi-event
    ```

3.  **Install Dependensi Frontend:**
    ```bash
    npm install
    ```

---

## 3. Konfigurasi & Database

1.  **Buat Database:**
    *   Buka XAMPP Control Panel, nyalakan **Apache** dan **MySQL**.
    *   Buka browser: `http://localhost/phpmyadmin`
    *   Buat database baru dengan nama: `multi_event_db`

2.  **Setting `.env`:**
    Duplikat file `.env.example` menjadi `.env` (jika belum ada), lalu edit bagian database:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=multi_event_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *(Kosongkan password jika default XAMPP memang kosong)*

3.  **Generate Key:**
    ```bash
    php artisan key:generate
    ```

---

## 4. Migration, Model, Seeder

Kita akan membuat tabel untuk Event, Voucher, dan Peserta.

### A. Membuat Model & Migration
Jalankan perintah ini di terminal:

```bash
php artisan make:model Event -m
php artisan make:model Voucher -m
php artisan make:model Participant -m
```
Tambahkan pada Model Participant:
```php

    protected $fillable = [
        'event_id',
        'kode_registrasi',
        'nama',
        'email',
        'whatsapp',
        'bukti_bayar',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get event for this participant
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
```

### B. Edit File Migration
File migration ada di `database/migrations/`.

**1. Tabel `events`**
```php
// xxxx_xx_xx_create_events_table.php
public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->text('deskripsi');
        $table->dateTime('tanggal_event');
        $table->string('lokasi');
        $table->decimal('harga', 10, 2);
        $table->integer('kuota');
        $table->string('gambar')->nullable();
        $table->boolean('is_active')->default(true);
        // Info Bank
        $table->string('bank_name')->default('BCA');
        $table->string('bank_account');
        $table->string('bank_holder');
        $table->timestamps();
    });
}
```

**2. Tabel `vouchers`**
```php
// xxxx_xx_xx_create_vouchers_table.php
public function up(): void
{
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('kode')->unique();
        $table->decimal('nominal', 10, 2);
        $table->integer('kuota');
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

**3. Tabel `participants`**
```php
// xxxx_xx_xx_create_participants_table.php
public function up(): void
{
    Schema::create('participants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->string('nama');
        $table->string('email');
        $table->string('whatsapp');
        $table->string('kode_registrasi')->unique(); // Tiket code
        $table->string('bukti_bayar')->nullable();
        $table->enum('status', ['pending', 'valid', 'invalid', 'redeem'])->default('pending');
        $table->timestamps();
    });
}
```

### C. Jalankan Migration
```bash
php artisan migrate
```

### D. Membuat Seeder (Data Dummy)
Agar tidak kosong, kita isi data awal.

```bash
php artisan make:seeder EventSeeder
```

Edit file `database/seeders/EventSeeder.php`:

```php
use App\Models\Event;

public function run(): void
{
    Event::create([
        'nama' => 'Lomba Lari 5K',
        'deskripsi' => 'Lomba lari seru keliling sekolah.',
        'tanggal_event' => now()->addDays(7),
        'lokasi' => 'Lapangan Utama',
        'harga' => 50000,
        'kuota' => 100,
        'bank_name' => 'BCA',
        'bank_account' => '1234567890',
        'bank_holder' => 'Panitia OSIS'
    ]);
}
```

Jalankan seeder:
```bash
php artisan db:seed --class=EventSeeder
```

---

## 5. Controller & Route

### A. Controller Utama
Kita butuh controller untuk halaman publik dan pendaftaran.

```bash
php artisan make:controller HomeController
php artisan make:controller RegistrationController
```

**1. `HomeController.php`**
Menampilkan daftar event.
```php
namespace App\Http\Controllers;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)->get();
        return view('welcome', compact('events'));
    }
}
```

**2. `RegistrationController.php`**
Menangani form pendaftaran.
```php
namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function create($id)
    {
        $event = Event::findOrFail($id);
        return view('registration.create', compact('event'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'whatsapp' => 'required',
            'bukti_bayar' => 'required|image'
        ]);

        $filePath = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');

        $participant = Participant::create([
            'event_id' => $id,
            'nama' => $request->nama,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'bukti_bayar' => $filePath,
            'kode_registrasi' => 'TKT-' . strtoupper(Str::random(6)),
            'status' => 'pending'
        ]);

        return redirect()->route('registration.success', $participant->id);
    }

    public function success($id)
    {
        $participant = Participant::findOrFail($id);
        return view('registration.success', compact('participant'));
    }
}
```

### B. Routes (`routes/web.php`)
Atur URL agar bisa diakses.

```php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Pendaftaran
Route::get('/event/{id}/register', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/event/{id}/register', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/registration/{id}/success', [RegistrationController::class, 'success'])->name('registration.success');
```

---

## 6. Setup & Views

### A. Setup Environment
Kita menggunakan Tailwind CSS yang sudah terintegrasi di Laravel 12.

1.  Pastikan `vite.config.js` sudah benar (bawaan Laravel sudah OK).
2.  Edit `resources/css/app.css`:
    ```css
    @import "tailwindcss";
    ```
    *(Untuk Tailwind v4, cukup ini saja. Jika v3 gunakan direktif @tailwind).*

### B. Layout Utama (`resources/views/layouts/app.blade.php`)
Buat file baru ini sebagai kerangka desain.

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <nav class="bg-white shadow p-4 mb-6">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">MultiEvent</a>
        </div>
    </nav>

    <main class="container mx-auto px-4 pb-12">
        @yield('content')
    </main>
</body>
</html>
```

### C. Halaman Depan (`resources/views/welcome.blade.php`)
Menampilkan daftar event.

```html
@extends('layouts.app')

@section('content')
<div class="text-center mb-10">
    <h1 class="text-4xl font-bold mb-4">Daftar Event Terbaru</h1>
    <p class="text-gray-600">Pilih event dan daftarkan dirimu sekarang!</p>
</div>

<div class="grid md:grid-cols-3 gap-6">
    @foreach($events as $event)
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <!-- Placeholder Image jika kosong -->
        <div class="h-48 bg-blue-100 flex items-center justify-center text-blue-400">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        
        <div class="p-6">
            <h2 class="text-xl font-bold mb-2">{{ $event->nama }}</h2>
            <p class="text-gray-500 text-sm mb-4">📅 {{ \Carbon\Carbon::parse($event->tanggal_event)->format('d M Y') }}</p>
            <p class="text-blue-600 font-bold text-lg mb-4">Rp {{ number_format($event->harga, 0, ',', '.') }}</p>
            
            <a href="{{ route('registration.create', $event->id) }}" class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg font-semibold hover:bg-blue-700">
                Daftar Sekarang
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
```

### D. Form Pendaftaran (`resources/views/registration/create.blade.php`)
Buat folder `resources/views/registration/` terlebih dahulu.

```html
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6">Form Pendaftaran: {{ $event->nama }}</h2>
    
    <form action="{{ route('registration.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No WhatsApp</label>
            <input type="text" name="whatsapp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="font-bold text-blue-800">Transfer Pembayaran</p>
            <p class="text-sm text-blue-600">Silakan transfer Rp {{ number_format($event->harga, 0, ',', '.') }} ke:</p>
            <p class="text-lg font-mono font-bold mt-1">{{ $event->bank_name }} - {{ $event->bank_account }}</p>
            <p class="text-sm">A.n {{ $event->bank_holder }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Upload Bukti Bayar</label>
            <input type="file" name="bukti_bayar" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
            Kirim Pendaftaran
        </button>
    </form>
</div>
@endsection
```
### E. Page Berhasil Registrasi (`resources/views/registration/success.blade.php`)

```html
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
                            <span class="font-medium">{{ $participant->event->created_at->format('d M Y, H:i') }}</span>
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


```

---

## 7. Menjalankan Aplikasi

Terakhir, hidupkan server agar bisa diakses.

1.  **Jalankan Laravel:**
    ```bash
    php artisan serve
    ```
2.  **Jalankan Vite (untuk CSS/JS):**
    Buka terminal baru, jalankan:
    ```bash
    npm run dev
    ```
3.  **Link Storage (Agar gambar muncul):**
    ```bash
    php artisan storage:link
    ```

Selesai! Buka browser di `http://localhost:8000`, dan sistem registrasi multi-event Anda siap digunakan. 🚀
