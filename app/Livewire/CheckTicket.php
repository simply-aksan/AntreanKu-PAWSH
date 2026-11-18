<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;

class CheckTicket extends Component
{
    public string $query = '';

    public bool $hasSearched = false;

    public function search(): void
    {
        $this->validate([
            'query' => 'required|string|min:3',
        ], [
            'query.required' => 'Masukkan nomor WhatsApp atau kode tiket.',
            'query.min' => 'Pencarian minimal 3 karakter.',
        ]);

        $this->hasSearched = true;
    }

    public function render()
    {
        $appointments = collect();

        if ($this->hasSearched && strlen($this->query) >= 3) {
            $appointments = Appointment::with(['service', 'staff'])
                ->where('customer_phone', 'like', '%'.trim($this->query).'%')
                ->orWhere('ticket_number', 'like', '%'.trim($this->query).'%')
                ->orWhere('tracking_code', 'like', '%'.trim($this->query).'%')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('livewire.check-ticket', [
            'appointments' => $appointments,
        ])->layout('layouts.app', ['title' => 'Cek Tiket Reservasi - PAWSH!']);
    }
}
