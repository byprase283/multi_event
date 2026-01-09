<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Create Sample Events
        Event::create([
            'nama' => 'Fun Run 2024',
            'deskripsi' => 'Event lari santai untuk seluruh masyarakat. Jarak tempuh 5K dan 10K tersedia.',
            'tanggal_event' => now()->addDays(30),
            'lokasi' => 'Lapangan Merdeka, Jakarta',
            'harga' => 150000,
            'kuota' => 100,
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
            'bank_holder' => 'Panitia Fun Run 2024',
            'is_active' => true,
        ]);

        Event::create([
            'nama' => 'Color Run Festival',
            'deskripsi' => 'Lari berwarna dengan bubuk warna-warni. Seru dan menyenangkan!',
            'tanggal_event' => now()->addDays(45),
            'lokasi' => 'GBK, Senayan',
            'harga' => 200000,
            'kuota' => 200,
            'bank_name' => 'Mandiri',
            'bank_account' => '0987654321',
            'bank_holder' => 'Color Run Indonesia',
            'is_active' => true,
        ]);

        Event::create([
            'nama' => 'Night Marathon',
            'deskripsi' => 'Maraton malam hari dengan pemandangan kota yang indah.',
            'tanggal_event' => now()->addDays(60),
            'lokasi' => 'Bundaran HI, Jakarta',
            'harga' => 250000,
            'kuota' => 150,
            'bank_name' => 'BRI',
            'bank_account' => '5678901234',
            'bank_holder' => 'Night Marathon Event',
            'is_active' => true,
        ]);

        // Create Sample Vouchers
        Voucher::create([
            'kode' => 'DISKON50',
            'nominal' => 50000,
            'kuota' => 20,
            'terpakai' => 0,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        Voucher::create([
            'kode' => 'MAHASISWA',
            'nominal' => 75000,
            'kuota' => 50,
            'terpakai' => 0,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(60),
        ]);

        Voucher::create([
            'kode' => 'GRATIS100',
            'nominal' => 100000,
            'kuota' => 10,
            'terpakai' => 0,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(15),
        ]);
    }
}
