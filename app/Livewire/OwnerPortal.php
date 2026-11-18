<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class OwnerPortal extends Component
{
    // New Service form state
    public bool $showNewServiceModal = false;

    public string $new_name = '';

    public string $new_description = '';

    public int $new_price = 50000;

    public int $new_duration = 30;

    public string $new_icon = 'sparkles';

    public string $new_badge_color = '#FFBA08';

    // Edit Service form state
    public bool $showEditServiceModal = false;

    public ?int $edit_service_id = null;

    public string $edit_name = '';

    public string $edit_description = '';

    public int $edit_price = 50000;

    public int $edit_duration = 30;

    public string $edit_icon = 'sparkles';

    public string $edit_badge_color = '#FFBA08';

    // New Staff form state
    public bool $showNewStaffModal = false;

    public string $new_staff_name = '';

    public string $new_staff_email = '';

    public string $new_staff_phone = '';

    public string $new_staff_nickname = '';

    public string $new_staff_title = '';

    public string $new_staff_bio = '';

    public string $new_staff_badge_color = '#FFBA08';

    // Edit Staff form state
    public bool $showEditStaffModal = false;

    public ?int $edit_staff_id = null;

    public string $edit_staff_name = '';

    public string $edit_staff_nickname = '';

    public string $edit_staff_title = '';

    public string $edit_staff_bio = '';

    public string $edit_staff_phone = '';

    public string $edit_staff_badge_color = '#FFBA08';

    public function mount()
    {
        // Enforce Owner Authentication
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if (Auth::user()->role !== 'owner') {
            abort(403, 'Akses terbatas khusus Pemilik Usaha (Owner).');
        }
    }

    /* ---------------- SERVICES CRUD ---------------- */

    public function createService(): void
    {
        $this->validate([
            'new_name' => 'required|string|min:3|max:100',
            'new_price' => 'required|integer|min:1000',
            'new_duration' => 'required|integer|min:15|max:180',
        ], [
            'new_name.required' => 'Nama layanan wajib diisi.',
            'new_price.required' => 'Tarif layanan wajib diisi.',
        ]);

        Service::create([
            'name' => $this->new_name,
            'slug' => Str::slug($this->new_name).'-'.rand(100, 999),
            'description' => $this->new_description ?: 'Layanan perawatan higienis dan profesional.',
            'price' => $this->new_price,
            'duration_minutes' => $this->new_duration,
            'icon' => $this->new_icon,
            'badge_color' => $this->new_badge_color,
            'is_active' => true,
        ]);

        $this->reset([
            'new_name',
            'new_description',
            'new_price',
            'showNewServiceModal',
        ]);

        session()->flash('success', 'Layanan baru berhasil ditambahkan.');
    }

    public function openEditService(int $id): void
    {
        $service = Service::findOrFail($id);
        $this->edit_service_id = $service->id;
        $this->edit_name = $service->name;
        $this->edit_description = (string) $service->description;
        $this->edit_price = (int) $service->price;
        $this->edit_duration = (int) $service->duration_minutes;
        $this->edit_icon = $service->icon;
        $this->edit_badge_color = $service->badge_color;

        $this->showEditServiceModal = true;
    }

    public function updateService(): void
    {
        $this->validate([
            'edit_name' => 'required|string|min:3|max:100',
            'edit_price' => 'required|integer|min:1000',
            'edit_duration' => 'required|integer|min:15|max:180',
        ]);

        $service = Service::findOrFail($this->edit_service_id);
        $service->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
            'price' => $this->edit_price,
            'duration_minutes' => $this->edit_duration,
            'icon' => $this->edit_icon,
            'badge_color' => $this->edit_badge_color,
        ]);

        $this->showEditServiceModal = false;
        $this->edit_service_id = null;

        session()->flash('success', 'Layanan '.$service->name.' berhasil diperbarui.');
    }

    public function deleteService(int $id): void
    {
        $service = Service::findOrFail($id);
        $name = $service->name;
        $service->delete();

        session()->flash('success', 'Layanan '.$name.' berhasil dihapus.');
    }

    public function toggleServiceStatus(int $id): void
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);
        session()->flash('success', 'Status layanan '.$service->name.' diperbarui.');
    }

    /* ---------------- STAFF CRUD ---------------- */

    public function createStaff(): void
    {
        $this->validate([
            'new_staff_name' => 'required|string|min:3|max:100',
            'new_staff_nickname' => 'required|string|min:2|max:50',
            'new_staff_title' => 'required|string|max:100',
            'new_staff_phone' => 'required|string|min:9|max:20|unique:users,phone',
            'new_staff_email' => 'nullable|email|unique:users,email',
        ], [
            'new_staff_name.required' => 'Nama lengkap staf wajib diisi.',
            'new_staff_nickname.required' => 'Nama panggilan staf wajib diisi.',
            'new_staff_phone.required' => 'Nomor HP staf wajib diisi.',
            'new_staff_phone.unique' => 'Nomor HP ini sudah digunakan akun lain.',
        ]);

        $user = User::create([
            'name' => $this->new_staff_name,
            'email' => $this->new_staff_email ?: 'staff'.rand(1000, 9999).'@pawsh.test',
            'phone' => $this->new_staff_phone,
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        StaffProfile::create([
            'user_id' => $user->id,
            'nickname' => $this->new_staff_nickname,
            'title' => $this->new_staff_title,
            'bio' => $this->new_staff_bio ?: 'Staf groomer profesional berdedikasi tinggi.',
            'badge_color' => $this->new_staff_badge_color,
            'is_active' => true,
        ]);

        $this->reset([
            'new_staff_name',
            'new_staff_nickname',
            'new_staff_title',
            'new_staff_phone',
            'new_staff_email',
            'new_staff_bio',
            'showNewStaffModal',
        ]);

        session()->flash('success', 'Staf groomer baru berhasil didaftarkan (Kata sandi default: password).');
    }

    public function openEditStaff(int $id): void
    {
        $staff = StaffProfile::with('user')->findOrFail($id);
        $this->edit_staff_id = $staff->id;
        $this->edit_staff_name = $staff->user->name;
        $this->edit_staff_nickname = $staff->nickname;
        $this->edit_staff_title = $staff->title;
        $this->edit_staff_bio = (string) $staff->bio;
        $this->edit_staff_phone = (string) $staff->user->phone;
        $this->edit_staff_badge_color = $staff->badge_color;

        $this->showEditStaffModal = true;
    }

    public function updateStaff(): void
    {
        $this->validate([
            'edit_staff_name' => 'required|string|min:3|max:100',
            'edit_staff_nickname' => 'required|string|min:2|max:50',
            'edit_staff_title' => 'required|string|max:100',
            'edit_staff_phone' => 'required|string|min:9|max:20',
        ]);

        $staff = StaffProfile::with('user')->findOrFail($this->edit_staff_id);
        $staff->update([
            'nickname' => $this->edit_staff_nickname,
            'title' => $this->edit_staff_title,
            'bio' => $this->edit_staff_bio,
            'badge_color' => $this->edit_staff_badge_color,
        ]);

        $staff->user->update([
            'name' => $this->edit_staff_name,
            'phone' => $this->edit_staff_phone,
        ]);

        $this->showEditStaffModal = false;
        $this->edit_staff_id = null;

        session()->flash('success', 'Data groomer '.$staff->nickname.' berhasil diperbarui.');
    }

    public function deleteStaff(int $id): void
    {
        $staff = StaffProfile::with('user')->findOrFail($id);
        $name = $staff->nickname;
        $user = $staff->user;

        $staff->delete();
        if ($user) {
            $user->delete();
        }

        session()->flash('success', 'Staf groomer '.$name.' berhasil dihapus dari sistem.');
    }

    public function toggleStaffStatus(int $id): void
    {
        $staff = StaffProfile::findOrFail($id);
        $staff->update(['is_active' => ! $staff->is_active]);
        session()->flash('success', 'Status groomer '.$staff->nickname.' diperbarui.');
    }

    public function render()
    {
        return view('livewire.owner-portal', [
            'totalAppointments' => Appointment::count(),
            'totalRevenue' => Transaction::where('status', 'paid')->sum('amount'),
            'services' => Service::all(),
            'staffList' => StaffProfile::with('user')->get(),
            'recentTransactions' => Transaction::with(['appointment.service', 'appointment.staff'])
                ->latest()
                ->take(8)
                ->get(),
        ])->layout('layouts.app', ['title' => 'Owner Management Portal - AntreanKu']);
    }
}
