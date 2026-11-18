<?php

namespace App\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomerDashboard extends Component
{
    public string $searchQuery = '';

    public bool $hasSearched = false;

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }
    }

    public function searchTicket(): void
    {
        $this->validate([
            'searchQuery' => 'required|string|min:2',
        ], [
            'searchQuery.required' => 'Masukkan nomor tiket, kode lacak, atau nama anabul.',
            'searchQuery.min' => 'Pencarian minimal 2 karakter.',
        ]);

        $this->hasSearched = true;
    }

    public function resetSearch(): void
    {
        $this->searchQuery = '';
        $this->hasSearched = false;
    }

    public function cancelTicket(int $id): void
    {
        $user = Auth::user();
        $app = Appointment::where('id', $id)
            ->where(function ($query) use ($user) {
                if ($user->phone) {
                    $query->where('customer_phone', $user->phone);
                }
            })
            ->where('status', 'pending')
            ->first();

        if ($app) {
            $app->update(['status' => 'cancelled']);
            session()->flash('success', 'Reservasi tiket #'.$app->ticket_number.' telah dibatalkan.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $userPhone = $user?->phone ?? '';

        $today = Carbon::today()->format('Y-m-d');

        $activeAppointments = collect();
        $pastAppointments = collect();
        $searchResults = collect();

        if ($userPhone) {
            $activeAppointments = Appointment::with(['service', 'staff'])
                ->where('customer_phone', $userPhone)
                ->where('booking_date', '>=', $today)
                ->whereNotIn('status', ['cancelled', 'no_show', 'completed'])
                ->orderBy('booking_date')
                ->orderBy('time_slot')
                ->get();

            $pastAppointments = Appointment::with(['service', 'staff', 'transaction'])
                ->where('customer_phone', $userPhone)
                ->where(function ($query) use ($today) {
                    $query->where('booking_date', '<', $today)
                        ->orWhereIn('status', ['completed', 'cancelled', 'no_show']);
                })
                ->latest()
                ->take(10)
                ->get();
        }

        if ($this->hasSearched && strlen(trim($this->searchQuery)) >= 2) {
            $q = trim($this->searchQuery);
            $searchResults = Appointment::with(['service', 'staff', 'transaction'])
                ->where(function ($query) use ($userPhone, $q) {
                    $query->where(function ($sub) use ($userPhone, $q) {
                        if ($userPhone) {
                            $sub->where('customer_phone', $userPhone);
                        }
                        $sub->where(function ($filter) use ($q) {
                            $filter->where('ticket_number', 'like', '%'.$q.'%')
                                ->orWhere('tracking_code', 'like', '%'.$q.'%')
                                ->orWhere('pet_name', 'like', '%'.$q.'%');
                        });
                    })->orWhere('tracking_code', $q);
                })
                ->latest()
                ->take(10)
                ->get();
        }

        return view('livewire.customer-dashboard', [
            'user' => $user,
            'activeAppointments' => $activeAppointments,
            'pastAppointments' => $pastAppointments,
            'searchResults' => $searchResults,
        ])->layout('layouts.app', ['title' => 'Dashboard Pelanggan - AntreanKu']);
    }
}
