<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TicketStatus extends Component
{
    public string $tracking_code = '';

    public bool $showCancelModal = false;

    public function mount(string $tracking_code): void
    {
        $this->tracking_code = trim($tracking_code);
    }

    #[Computed]
    public function appointment(): ?Appointment
    {
        return Appointment::with(['service', 'staff', 'transaction'])
            ->where('tracking_code', $this->tracking_code)
            ->first();
    }

    /**
     * Count how many appointments are ahead of this ticket today.
     */
    #[Computed]
    public function queuesAheadCount(): int
    {
        $app = $this->appointment;
        if (! $app || ! in_array($app->status, ['pending', 'serving'], true)) {
            return 0;
        }

        return Appointment::whereDate('booking_date', $app->booking_date)
            ->where('status', 'pending')
            ->where('time_slot', '<', $app->time_slot)
            ->count();
    }

    public function cancelAppointment(): void
    {
        $app = $this->appointment;
        if ($app && $app->status === 'pending') {
            $app->update([
                'status' => 'cancelled',
            ]);
            unset($this->appointment, $this->queuesAheadCount);
            $this->showCancelModal = false;
            session()->flash('success', 'Reservasi tiket #'.$app->ticket_number.' telah berhasil dibatalkan. Slot jam kini terbuka kembali.');
        }
    }

    public function render()
    {
        $appointment = $this->appointment;

        if (! $appointment) {
            abort(404, 'Tiket reservasi tidak ditemukan.');
        }

        return view('livewire.ticket-status', [
            'appointment' => $appointment,
            'queuesAhead' => $this->queuesAheadCount,
        ])->layout('layouts.app', ['title' => 'Tiket #'.$appointment->ticket_number.' - PAWSH!']);
    }
}
