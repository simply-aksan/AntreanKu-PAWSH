<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class CheckTicket extends Component
{
    public string $query = '';

    public bool $hasSearched = false;

    public function search(): void
    {
        $throttleKey = 'check-ticket:'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 15)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('query', 'Terlalu banyak pencarian. Silakan coba lagi dalam '.$seconds.' detik.');

            return;
        }

        $this->validate([
            'query' => 'required|string|min:5|max:40',
        ], [
            'query.required' => 'Masukkan nomor WhatsApp lengkap atau kode lacak tiket.',
            'query.min' => 'Masukkan minimal 5 karakter (nomor WA lengkap atau kode lacak/tiket).',
        ]);

        RateLimiter::hit($throttleKey, 60);
        $this->hasSearched = true;
    }

    public function render()
    {
        $appointments = collect();

        if ($this->hasSearched && strlen(trim($this->query)) >= 5) {
            $q = trim($this->query);
            // Exact match prevents wildcard PII enumeration across other customers
            $appointments = Appointment::with(['service', 'staff'])
                ->where(function ($builder) use ($q): void {
                    $builder->where('customer_phone', $q)
                        ->orWhere('tracking_code', strtoupper($q))
                        ->orWhere('ticket_number', strtoupper($q));
                })
                ->latest()
                ->take(10)
                ->get();
        }

        return view('livewire.check-ticket', [
            'appointments' => $appointments,
        ])->layout('layouts.app', ['title' => 'Cek Tiket Reservasi - PAWSH!']);
    }
}
