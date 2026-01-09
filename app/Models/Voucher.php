<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nominal',
        'kuota',
        'terpakai',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if voucher is valid and can be used
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check quota
        if ($this->terpakai >= $this->kuota) {
            return false;
        }

        // Check date range
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining quota
     */
    public function remainingQuota(): int
    {
        return max(0, $this->kuota - $this->terpakai);
    }

    /**
     * Use voucher (increment terpakai)
     */
    public function use(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->increment('terpakai');
        return true;
    }
}
