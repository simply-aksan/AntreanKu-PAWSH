<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    private function authorizeStaff(): void
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role, ['owner', 'staff'], true)) {
            abort(403, 'Akses terbatas untuk staf dan pemilik usaha.');
        }
    }

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->authorizeStaff();

        $this->selectedDate = Carbon::today()->format('Y-m-d');

        $firstStaff = StaffProfile::where('is_active', true)->orderBy('id')->first();
        if ($firstStaff) {
            $this->walkIn_staff_id = $firstStaff->id;
        }

        $firstService = Service::where('is_active', true)->orderBy('id')->first();
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
        $this->authorizeStaff();

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
        $this->authorizeStaff();

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
        $this->authorizeStaff();

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
        $this->authorizeStaff();

        $this->payingAppointmentId = $id;
        $this->payment_method = 'tunai';
        $this->showPaymentModal = true;
    }

    /**
     * Complete transaction and mark appointment as completed
     */
    public function confirmPayment(): void
    {
        $this->authorizeStaff();

        $this->validate([
            'payingAppointmentId' => ['required', 'integer', 'exists:appointments,id'],
            'payment_method' => ['required', 'string', Rule::in(['tunai', 'qris', 'transfer'])],
        ]);

        $app = Appointment::with('service')->find($this->payingAppointmentId);
        if (! $app || ! $app->service) {
            return;
        }

        $invoiceNumber = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(6));

        DB::transaction(function () use ($app, $invoiceNumber): void {
            Transaction::updateOrCreate(
                ['appointment_id' => $app->id],
                [
                    'invoice_number' => $invoiceNumber,
                    'amount' => $app->service->price,
                    'payment_method' => $this->payment_method,
                    'status' => 'paid',
                    'paid_at' => Carbon::now(),
                    'cashier_name' => Auth::user()?->name ?? 'Kasir Operasional',
                ]
            );

            $app->update(['status' => 'completed']);
        });

        $this->showPaymentModal = false;
        $this->payingAppointmentId = null;

        session()->flash('success', 'Pembayaran tiket #'.$app->ticket_number.' lunas ('.strtoupper($this->payment_method).'). Status: [SELESAI].');
    }

    /**
     * Submit Walk-In customer from Cashier Desk
     */
    public function submitWalkIn(): void
    {
        $this->authorizeStaff();

        $this->validate([
            'walkIn_customer_name' => ['required', 'string', 'min:3', 'max:100'],
            'walkIn_customer_phone' => ['required', 'string', 'min:8', 'max:20'],
            'walkIn_pet_name' => ['required', 'string', 'min:2', 'max:50'],
            'walkIn_pet_type' => ['required', 'string', Rule::in(['Kucing', 'Anjing Kecil', 'Kelinci', 'Lainnya'])],
            'walkIn_service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'walkIn_staff_id' => ['required', Rule::exists('staff_profiles', 'id')->where('is_active', true)],
            'walkIn_time_slot' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'walkIn_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'walkIn_customer_name.required' => 'Nama pelanggan walk-in wajib diisi.',
            'walkIn_customer_phone.required' => 'Nomor HP wajib diisi.',
            'walkIn_pet_name.required' => 'Nama anabul wajib diisi.',
            'walkIn_service_id.required' => 'Pilih jenis layanan.',
            'walkIn_staff_id.required' => 'Pilih groomer.',
            'walkIn_time_slot.required' => 'Pilih slot jam.',
        ]);

        $ticketNumber = DB::transaction(function (): string {
            $todayCount = Appointment::whereDate('booking_date', $this->selectedDate)->count();
            $ticketNumber = 'PAW-'.str_pad((string) ($todayCount + 1), 2, '0', STR_PAD_LEFT);
            $trackingCode = 'TRK-WLK'.strtoupper(Str::random(7));

            Appointment::create([
                'ticket_number' => $ticketNumber,
                'tracking_code' => $trackingCode,
                'customer_name' => trim($this->walkIn_customer_name),
                'customer_phone' => trim($this->walkIn_customer_phone),
                'pet_name' => trim($this->walkIn_pet_name),
                'pet_type' => $this->walkIn_pet_type,
                'staff_id' => $this->walkIn_staff_id,
                'service_id' => $this->walkIn_service_id,
                'booking_date' => $this->selectedDate,
                'time_slot' => $this->walkIn_time_slot,
                'type' => 'walk_in',
                'status' => 'pending',
                'notes' => $this->walkIn_notes ? '[Walk-In] '.trim($this->walkIn_notes) : '[Walk-In Pelanggan Langsung]',
            ]);

            return $ticketNumber;
        });

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
        $this->authorizeStaff();

        $query = Appointment::with(['service', 'staff', 'transaction'])
            ->whereDate('booking_date', $this->selectedDate);

        if ($this->filterStaffId) {
            $query->where('staff_id', $this->filterStaffId);
        }

        $appointments = $query->orderBy('time_slot', 'asc')->get();

        // Calculate summary metrics in memory without extra DB roundtrips
        $totalCount = $appointments->count();
        $servingCount = $appointments->where('status', 'serving')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $completedCount = $appointments->where('status', 'completed')->count();

        $totalRevenueToday = Transaction::whereDate('paid_at', $this->selectedDate)
            ->where('status', 'paid')
            ->sum('amount');

        $payingAppointment = null;
        if ($this->payingAppointmentId) {
            $payingAppointment = $appointments->firstWhere('id', $this->payingAppointmentId)
                ?? Appointment::with('service')->find($this->payingAppointmentId);
        }

        return view('livewire.cashier-workstation', [
            'appointments' => $appointments,
            'totalCount' => $totalCount,
            'servingCount' => $servingCount,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'totalRevenueToday' => $totalRevenueToday,
            'staffList' => StaffProfile::where('is_active', true)->orderBy('id')->get(),
            'servicesList' => Service::where('is_active', true)->orderBy('id')->get(),
            'payingAppointment' => $payingAppointment,
        ])->layout('layouts.app', ['title' => 'Meja Kasir & Operasional Antrean - PAWSH!']);
    }
}
