<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'ticket_number',
    'tracking_code',
    'customer_name',
    'customer_phone',
    'pet_name',
    'pet_type',
    'staff_id',
    'service_id',
    'booking_date',
    'time_slot',
    'type',
    'status',
    'notes',
])]
class Appointment extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'staff_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Giliran',
            'serving' => 'Sedang Dilayani',
            'completed' => 'Selesai & Siap Dijemput',
            'cancelled' => 'Dibatalkan',
            'no_show' => 'Tidak Hadir (No-Show)',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-[#FFBA08] text-black',
            'serving' => 'bg-[#BEE1E6] text-black',
            'completed' => 'bg-[#A7D7C5] text-black',
            'cancelled' => 'bg-[#F28482] text-black',
            'no_show' => 'bg-stone-200 text-stone-700',
            default => 'bg-white text-black',
        };
    }
}
