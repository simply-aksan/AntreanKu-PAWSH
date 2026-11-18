<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class BookingForm extends Component
{
    public ?int $service_id = null;

    public ?int $staff_id = null;

    public string $booking_date = '';

    public string $time_slot = '';

    public string $customer_name = '';

    public string $customer_phone = '';

    public string $pet_name = '';

    public string $pet_type = 'Kucing';

    public string $notes = '';

    public int $currentStep = 1;

    /**
     * @return array<string, array<string, string>>
     */
    protected function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff_profiles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|min:9|max:20',
            'pet_name' => 'required|string|min:2|max:50',
            'pet_type' => 'required|string|in:Kucing,Anjing Kecil,Kelinci,Lainnya',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'service_id.required' => 'Silakan pilih jenis layanan grooming.',
            'staff_id.required' => 'Silakan pilih staf groomer yang bertugas.',
            'booking_date.required' => 'Silakan tentukan tanggal reservasi.',
            'booking_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'time_slot.required' => 'Silakan pilih salah satu slot jam yang tersedia.',
            'customer_name.required' => 'Nama pemilik wajib diisi.',
            'customer_name.min' => 'Nama pemilik minimal 3 karakter.',
            'customer_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'pet_name.required' => 'Nama anabul wajib diisi.',
            'pet_type.required' => 'Pilih jenis anabul Anda.',
        ];
    }

    public function mount()
    {
        if (! Auth::check()) {
            $intended = request()->has('service')
                ? route('booking', ['service' => request()->query('service')])
                : route('booking');

            session()->put('url.intended', $intended);
            session()->flash('warning', 'Silakan masuk atau daftar terlebih dahulu untuk memesan tiket antrean.');

            return $this->redirect(route('login'), navigate: true);
        }

        $this->booking_date = Carbon::today()->format('Y-m-d');

        if (Auth::user()->role === 'customer') {
            $this->customer_name = Auth::user()->name;
            $this->customer_phone = (string) Auth::user()->phone;
        }

        // Auto select service from query string if available
        if (request()->has('service')) {
            $service = Service::where('id', request()->query('service'))->where('is_active', true)->first();
            if ($service) {
                $this->service_id = $service->id;
            }
        }

        // Default to first active staff if not selected
        if (! $this->staff_id) {
            $firstStaff = StaffProfile::where('is_active', true)->first();
            if ($firstStaff) {
                $this->staff_id = $firstStaff->id;
            }
        }
    }

    public function selectService(int $id): void
    {
        $this->service_id = $id;
    }

    public function selectStaff(int $id): void
    {
        $this->staff_id = $id;
        $this->time_slot = ''; // Reset slot selection when staff changes
    }

    public function selectSlot(string $slot): void
    {
        $this->time_slot = $slot;
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'service_id' => 'required|exists:services,id',
                'staff_id' => 'required|exists:staff_profiles,id',
            ], $this->messages());
            $this->currentStep = 2;

            return;
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'booking_date' => 'required|date|after_or_equal:today',
                'time_slot' => 'required|string',
            ], $this->messages());
            $this->currentStep = 3;

            return;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep && $step >= 1) {
            $this->currentStep = $step;

            return;
        }

        if ($step === 2 && $this->currentStep === 1) {
            $this->nextStep();

            return;
        }

        if ($step === 3 && $this->currentStep === 2) {
            $this->nextStep();

            return;
        }
    }

    /**
     * Compute available 30-minute time slots for the chosen staff & date.
     *
     * @return array<int, array{time: string, is_available: bool}>
     */
    public function getTimeSlotsProperty(): array
    {
        $slots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
            '18:00', '18:30', '19:00', '19:30', '20:00', '20:30',
        ];

        if (! $this->staff_id || ! $this->booking_date) {
            return [];
        }

        $bookedSlots = Appointment::whereDate('booking_date', $this->booking_date)
            ->where('staff_id', $this->staff_id)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->pluck('time_slot')
            ->toArray();

        $result = [];
        foreach ($slots as $slot) {
            $result[] = [
                'time' => $slot,
                'is_available' => ! in_array($slot, $bookedSlots, true),
            ];
        }

        return $result;
    }

    public function submitBooking()
    {
        $this->validate();

        // Strict Anti Double-Booking Check
        $isAlreadyBooked = Appointment::whereDate('booking_date', $this->booking_date)
            ->where('staff_id', $this->staff_id)
            ->where('time_slot', $this->time_slot)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->exists();

        if ($isAlreadyBooked) {
            $this->addError('time_slot', 'Maaf, slot jam ini baru saja dipesan oleh orang lain. Silakan pilih slot lainnya.');
            $this->currentStep = 2;

            return;
        }

        // Generate Ticket Number (PAW-01, PAW-02, etc.)
        $todayCount = Appointment::where('booking_date', $this->booking_date)->count();
        $ticketNumber = 'PAW-'.str_pad((string) ($todayCount + 1), 2, '0', STR_PAD_LEFT);
        $trackingCode = 'TRK-'.strtoupper(Str::random(8));

        $appointment = Appointment::create([
            'ticket_number' => $ticketNumber,
            'tracking_code' => $trackingCode,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'pet_name' => $this->pet_name,
            'pet_type' => $this->pet_type,
            'staff_id' => $this->staff_id,
            'service_id' => $this->service_id,
            'booking_date' => $this->booking_date,
            'time_slot' => $this->time_slot,
            'type' => 'online',
            'status' => 'pending',
            'notes' => $this->notes ?: null,
        ]);

        session()->flash('success', 'Reservasi berhasil! Nomor tiket digital Anda: '.$ticketNumber);

        return $this->redirect(route('ticket-status', ['tracking_code' => $trackingCode]), navigate: true);
    }

    public function render()
    {
        return view('livewire.booking-form', [
            'services' => Service::where('is_active', true)->get(),
            'staffList' => StaffProfile::where('is_active', true)->get(),
            'selectedService' => Service::find($this->service_id),
            'selectedStaff' => StaffProfile::find($this->staff_id),
        ])->layout('layouts.app', ['title' => 'Form Ambil Antrean & Reservasi - PAWSH!']);
    }
}
