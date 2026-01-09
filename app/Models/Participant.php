<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'kode_registrasi',
        'kode_voucher',
        'token_hash',
        'nama',
        'jenis_kelamin',
        'usia',
        'whatsapp',
        'alamat',
        'ukuran_jersey',
        'bukti_bayar',
        'waktu_daftar',
        'tgl_validasi',
        'status_verifikasi',
    ];

    protected $casts = [
        'waktu_daftar' => 'datetime',
        'tgl_validasi' => 'datetime',
    ];

    /**
     * Get event for this participant
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Generate unique registration code
     * Format: EVT-YYYYMMDD-XXXXX
     */
    public static function generateKodeRegistrasi(int $eventId): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(5));
        return "EVT{$eventId}-{$date}-{$random}";
    }

    /**
     * Generate unique token hash for ticket verification
     */
    public static function generateTokenHash(): string
    {
        return hash('sha256', Str::uuid()->toString() . time() . Str::random(32));
    }

    /**
     * Validate participant and generate codes
     */
    public function validate(): bool
    {
        if ($this->status_verifikasi !== 'Pending') {
            return false;
        }

        $this->kode_registrasi = self::generateKodeRegistrasi($this->event_id);
        $this->token_hash = self::generateTokenHash();
        $this->tgl_validasi = now();
        $this->status_verifikasi = 'Valid';
        $this->save();

        // Increment event terisi count
        $this->event->increment('terisi');

        return true;
    }

    /**
     * Invalidate participant
     */
    public function invalidate(): bool
    {
        $this->status_verifikasi = 'Invalid';
        $this->save();
        return true;
    }

    /**
     * Mark ticket as redeemed
     */
    public function redeem(): bool
    {
        if ($this->status_verifikasi !== 'Valid') {
            return false;
        }

        $this->status_verifikasi = 'Redeem';
        $this->save();
        return true;
    }

    /**
     * Get WhatsApp link for sending ticket
     */
    public function getWhatsAppLink(string $ticketUrl): string
    {
        $message = urlencode(
            "Halo {$this->nama}!\n\n" .
            "Tiket Anda untuk event *{$this->event->nama}* sudah VALID.\n\n" .
            "📋 Kode Registrasi: {$this->kode_registrasi}\n" .
            "📅 Tanggal: {$this->event->tanggal_event->format('d M Y H:i')}\n" .
            "📍 Lokasi: {$this->event->lokasi}\n\n" .
            "🎟️ Link Tiket:\n{$ticketUrl}\n\n" .
            "Simpan tiket ini dan tunjukkan saat registrasi ulang di lokasi.\n\n" .
            "Terima kasih!"
        );

        $phone = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        return "https://wa.me/{$phone}?text={$message}";
    }
}
