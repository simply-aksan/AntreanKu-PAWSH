<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CashierWorkstation extends Component
{
    public string $selectedDate = '';

    public ?int $filterStaffId = null;

    // Walk-In Modal State
    public bool $showWalkInModal = false;

    public string $walkIn_customer_name = '';

    public string $walkIn_customer_phone = '';

    public string $walkIn_pet_name = '';

    public string $walkIn_pet_type = 'Kucing';

    public ?int $walkIn_service_id = null;

    public ?int $walkIn_staff_id = null;

    public string $walkIn_time_slot = '';

    public string $walkIn_notes = '';

    // Payment Modal State
    public bool $showPaymentModal = false;

    public ?int $payingAppointmentId = null;

    public string $payment_method = 'tunai';

    public string $cashier_notes = '';

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if (! in_array(Auth::user()->role, ['owner', 'staff'], true)) {
            abort(403, 'Akses terbatas untuk staf dan pemilik usaha.');
        }

        $this->selectedDate = Carbon::today()->format('Y-m-d');

        $firstStaff = StaffProfile::where('is_active', true)->first();
        if ($firstStaff) {
            $this->walkIn_staff_id = $firstStaff->id;
        }

        $firstService = Service::where('is_active', true)->first();
        if ($firstService) {
            $this->walkIn_service_id = $firstService->id;
        }

        $this->walkIn_time_slot = Carbon::now()->format('H').':00';
    }

    /**
     * Call next queue / Start serving
     */
    public function startServing(int $id): void
    {
        $app = Appointment::find($id);
        if ($app) {
            $app->update(['status' => 'serving']);
            session()->flash('success', 'Nomor tiket #'.$app->ticket_number.' kini berstatus [SEDANG DILAYANI].');
        }
    }

    /**
     * Mark customer as No-Show
     */
    public function markNoShow(int $id): void
    {
        $app = Appointment::find($id);
        if ($app) {
            $app->update(['status' => 'no_show']);
            session()->flash('error', 'Nomor tiket #'.$app->ticket_number.' ditandai [TIDAK HADIR / NO-SHOW].');
        }
    }

    /**
     * Cancel an appointment
     */
    public function cancelTicket(int $id): void
    {
        $app = Appointment::find($id);
        if ($app) {
            $app->update(['status' => 'cancelled']);
            session()->flash('error', 'Nomor tiket #'.$app->ticket_number.' telah dibatalkan.');
        }
    }

    /**
     * Open Payment Modal for an appointment
     */
    public function openPaymentModal(int $id): void
    {
        $this->payingAppointmentId = $id;
        $this->payment_method = 'tunai';
        $this->showPaymentModal = true;
    }

    /**
     * Complete transaction and mark appointment as completed
     */
    public function confirmPayment(): void
    {
        $app = Appointment::with('service')->find($this->payingAppointmentId);
        if (! $app) {
            return;
        }

        $invoiceNumber = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4));

        Transaction::updateOrCreate(
            ['appointment_id' => $app->id],
            [
                'invoice_number' => $invoiceNumber,
                'amount' => $app->service->price,
                'payment_method' => $this->payment_method,
                'status' => 'paid',
                'paid_at' => Carbon::now(),
                'cashier_name' => 'Kasir Operasional',
            ]
        );

        $app->update(['status' => 'completed']);

        $this->showPaymentModal = false;
        $this->payingAppointmentId = null;

        session()->flash('success', 'Pembayaran tiket #'.$app->ticket_number.' lunas ('.strtoupper($this->payment_method).'). Status: [SELESAI].');
    }

    /**
     * Submit Walk-In customer from Cashier Desk
     */
    public function submitWalkIn(): void
    {
        $this->validate([
            'walkIn_customer_name' => 'required|string|min:3|max:100',
            'walkIn_customer_phone' => 'required|string|min:8|max:20',
            'walkIn_pet_name' => 'required|string|min:2|max:50',
            'walkIn_pet_type' => 'required|string',
            'walkIn_service_id' => 'required|exists:services,id',
            'walkIn_staff_id' => 'required|exists:staff_profiles,id',
            'walkIn_time_slot' => 'required|string',
        ], [
            'walkIn_customer_name.required' => 'Nama pelanggan walk-in wajib diisi.',
            'walkIn_customer_phone.required' => 'Nomor HP wajib diisi.',
            'walkIn_pet_name.required' => 'Nama anabul wajib diisi.',
            'walkIn_service_id.required' => 'Pilih jenis layanan.',
            'walkIn_staff_id.required' => 'Pilih groomer.',
            'walkIn_time_slot.required' => 'Pilih slot jam.',
        ]);

        $todayCount = Appointment::where('booking_date', $this->selectedDate)->count();
        $ticketNumber = 'PAW-'.str_pad((string) ($todayCount + 1), 2, '0', STR_PAD_LEFT);
        $trackingCode = 'TRK-WALK'.strtoupper(Str::random(6));

        Appointment::create([
            'ticket_number' => $ticketNumber,
            'tracking_code' => $trackingCode,
            'customer_name' => $this->walkIn_customer_name,
            'customer_phone' => $this->walkIn_customer_phone,
            'pet_name' => $this->walkIn_pet_name,
            'pet_type' => $this->walkIn_pet_type,
            'staff_id' => $this->walkIn_staff_id,
            'service_id' => $this->walkIn_service_id,
            'booking_date' => $this->selectedDate,
            'time_slot' => $this->walkIn_time_slot,
            'type' => 'walk_in',
            'status' => 'pending',
            'notes' => $this->walkIn_notes ? '[Walk-In] '.$this->walkIn_notes : '[Walk-In Pelanggan Langsung]',
        ]);

        $this->reset([
            'walkIn_customer_name',
            'walkIn_customer_phone',
            'walkIn_pet_name',
            'walkIn_notes',
            'showWalkInModal',
        ]);

        session()->flash('success', 'Pelanggan walk-in berhasil didaftarkan dengan tiket #'.$ticketNumber.'.');
    }

    public function render()
    {
        $query = Appointment::with(['service', 'staff', 'transaction'])
            ->whereDate('booking_date', $this->selectedDate);

        if ($this->filterStaffId) {
            $query->where('staff_id', $this->filterStaffId);
        }

        $appointments = $query->orderBy('time_slot', 'asc')->get();

        // Calculate summary metrics
        $totalCount = $appointments->count();
        $servingCount = $appointments->where('status', 'serving')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $completedCount = $appointments->where('status', 'completed')->count();

        $totalRevenueToday = Transaction::whereDate('paid_at', $this->selectedDate)
            ->where('status', 'paid')
            ->sum('amount');

        return view('livewire.cashier-workstation', [
            'appointments' => $appointments,
            'totalCount' => $totalCount,
            'servingCount' => $servingCount,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'totalRevenueToday' => $totalRevenueToday,
            'staffList' => StaffProfile::where('is_active', true)->get(),
            'servicesList' => Service::where('is_active', true)->get(),
            'payingAppointment' => $this->payingAppointmentId ? Appointment::with('service')->find($this->payingAppointmentId) : null,
        ])->layout('layouts.app', ['title' => 'Meja Kasir & Operasional Antrean - PAWSH!']);
    }
}
