# Panduan Lengkap Pengembangan Sistem Registrasi Multi-Event

Dokumen ini berisi langkah-langkah detail untuk membangun sistem registrasi event berbasis Laravel 12 yang telah kita buat. Sistem ini mencakup pendaftaran publik, validasi admin, diskon voucher, tiket QR Code, dan scanner admin.

## 1. Persiapan Lingkungan

Pastikan komputer Anda sudah terinstall:
- **PHP** >= 8.2
- **Composer** (Dependency Manager PHP)
- **Node.js** & **NPM** (Untuk manajemen aset frontend)
- **Database** (SQLite/MySQL)

## 2. Instalasi Proyek Laravel

Buka terminal dan jalankan perintah berikut untuk membuat proyek baru:

```bash
composer create-project laravel/laravel:^12.0 multi-event
cd multi-event
```

## 3. Desain Database (Migration)

Kita membutuhkan 3 tabel utama selain tabel bawaan `users`.

### a. Tabel `events`
Menyimpan data acara seperti nama, tanggal, harga, kuota, dan info rekening.

```bash
php artisan make:migration create_events_table
```

**Schema:**
- `id`, `nama`, `deskripsi`, `tanggal_event`, `lokasi`
- `harga`, `kuota`, `terisi`
- `gambar`, `bank_name`, `bank_account`, `bank_holder`
- `is_active`

### b. Tabel `vouchers`
Menyimpan kode diskon.

```bash
php artisan make:migration create_vouchers_table
```

**Schema:**
- `id`, `kode` (Unique), `nominal`
- `kuota`, `terpakai`
- `start_date`, `end_date`, `is_active`

### c. Tabel `participants`
Menyimpan data pendaftar.

```bash
php artisan make:migration create_participants_table
```

**Schema:**
- `id`, `event_id` (Foreign Key)
- `nama`, `email`, `whatsapp`
- `kode_registrasi` (Ticket ID), `token_hash` (Untuk QR)
- `bukti_bayar`, `status_verifikasi` (Pending/Valid/Invalid/Redeem)

Jalankan migrasi:
```bash
php artisan migrate
```

## 4. Implementasi Backend (Models & Controllers)

### Models
Buat model dan relasinya:
- `Event` hasMany `Participant`
- `Participant` belongsTo `Event`
- `Voucher` (Standalone logic untuk cek validitas)

### Controllers
Kita membagi controller menjadi 2 area:

**A. Public (Frontend User)**
- `HomeController`: Menampilkan daftar event aktif.
- `RegistrationController`:
    - Menampilkan form daftar (`create`).
    - Menyimpan data & upload bukti bayar (`store`).
    - Cek validitas voucher via AJAX (`checkVoucher`).
    - Halaman sukses & tiket (`success`, `verifyTicket`).

**B. Admin (Backend)**
- `AuthController`: Login/Logout admin.
- `DashboardController`: Statistik pendaftar & event.
- `EventController`: CRUD Event + Upload Gambar.
- `VoucherController`: CRUD Voucher.
- `ParticipantController`:
    - List pendaftar (Filter by status/event).
    - Validasi (Approve/Reject).
    - Kirim Tiket (Redirect ke API WhatsApp).
- `ScannerController`: Logika untuk memproses hasil scan QR Code.

## 5. Implementasi Frontend

### Public Area (Tailwind CSS)
Kita menggunakan **Tailwind CSS** (via CDN untuk kemudahan) agar desain terlihat modern.
- **Landing Page**: Menampilkan kartu event dengan desain grid responsive.
- **Form Registrasi**:
    - Validasi input realtime (HTML5).
    - Fitur cek voucher tanpa reload (Fetch API).
    - Kalkulasi total harga otomatis.
    - Fitur "Salin No Rekening".
- **Halaman Tiket**: Desain menyerupai tiket fisik dengan QR Code (menggunakan `qrcode.js`).

### Admin Area (Bootstrap 5)
Kita menggunakan **Bootstrap 5** untuk kecepatan pengembangan dashboard.
- **Sidebar Navigation**: Menu dashboard, events, vouchers, peserta, scan.
- **Voucher Management**: Form tambah/edit voucher.
- **Participant Management**: Tabel data dengan badge status warna-warni & tombol aksi cepat.
- **Scanner**: Halaman khusus yang mengakses kamera device menggunakan `html5-qrcode` untuk scan tiket.

## 6. Fitur Unggulan Detail

### 🎟️ Sistem Validasi & Tiket
1. User daftar -> Status `Pending`.
2. Admin cek bukti bayar -> Klik `Validasi`.
3. Sistem generate `kode_registrasi` (misal: EVT-001) dan `token_hash` unik.
4. Admin klik tombol `WhatsApp` -> Membuka WA dengan template pesan berisi link tiket.
5. User buka link tiket -> Muncul QR Code.

### 📱 QR Code Scanner
1. Admin membuka menu `Scan Tiket`.
2. Halaman meminta izin kamera.
3. Admin scan QR di HP peserta.
4. Sistem mengecek `token_hash` di database:
    - Jika **Valid**: Update status ke `Redeem`, muncul pesan "Berhasil".
    - Jika **Sudah Redeem**: Muncul peringatan "Sudah Digunakan".
    - Jika **Tidak Ditemukan**: Muncul error.

### 💰 Kode Voucher
- Admin membuat voucher (misal: `EARLYBIRD`, diskon 50k, kuota 10).
- User input kode saat daftar.
- Sistem mengecek: Apakah kode ada? Masih aktif? Kuota cukup? Tanggal valid?
- Jika oke, harga total langsung terpotong.

## 7. Deployment & Testing

1. **Jalankan Server**:
   ```bash
   php artisan serve
   ```

2. **Link Storage** (Penting untuk gambar):
   ```bash
   php artisan storage:link
   ```

3. **Akun Admin Default** (dari Seeder):
   - Email: `admin@admin.com`
   - Pass: `password`

## 8. Struktur Folder Utama

```
multi-event/
├── app/
│   ├── Http/Controllers/Admin/  # Logika Admin
│   ├── Models/                  # Logika Database
├── database/migrations/         # Struktur Tabel
├── resources/views/
│   ├── admin/                   # View Admin (Bootstrap)
│   ├── registration/            # View Pendaftaran (Tailwind)
│   ├── ticket/                  # View Tiket
│   └── welcome.blade.php        # Halaman Depan
└── routes/web.php               # Definisi URL
```

Sistem ini sekarang siap digunakan untuk kegiatan sekolah, seminar, atau lomba! 🚀
