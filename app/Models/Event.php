<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal_event',
        'lokasi',
        'harga',
        'kuota',
        'terisi',
        'gambar',
        'bank_name',
        'bank_account',
        'bank_holder',
        'is_active',
    ];

    protected $casts = [
        'tanggal_event' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get participants for this event
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Check if event still has available slots
     */
    public function hasAvailableSlots(): bool
    {
        return $this->terisi < $this->kuota;
    }

    /**
     * Get remaining slots
     */
    public function remainingSlots(): int
    {
        return max(0, $this->kuota - $this->terisi);
    }
}
