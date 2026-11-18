<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OwnerPortal extends Component
{
    /**
     * Allowed Lucide icon keys for service cards.
     *
     * @var array<int, string>
     */
    private const ALLOWED_ICONS = ['bath', 'scissors', 'sparkles', 'dog', 'heart', 'paw-print'];

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

    private function authorizeOwner(): void
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'owner') {
            abort(403, 'Akses terbatas khusus Pemilik Usaha (Owner).');
        }
    }

    public function mount()
    {
        // Enforce Owner Authentication
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $this->authorizeOwner();
    }

    /* ---------------- SERVICES CRUD ---------------- */

    public function createService(): void
    {
        $this->authorizeOwner();

        $this->validate([
            'new_name' => ['required', 'string', 'min:3', 'max:100'],
            'new_description' => ['nullable', 'string', 'max:500'],
            'new_price' => ['required', 'integer', 'min:1000', 'max:50000000'],
            'new_duration' => ['required', 'integer', 'min:15', 'max:180'],
            'new_icon' => ['required', 'string', Rule::in(self::ALLOWED_ICONS)],
            'new_badge_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'new_name.required' => 'Nama layanan wajib diisi.',
            'new_price.required' => 'Tarif layanan wajib diisi.',
            'new_badge_color.regex' => 'Format warna badge harus berupa kode hex yang valid.',
        ]);

        Service::create([
            'name' => trim($this->new_name),
            'slug' => Str::slug($this->new_name).'-'.strtolower(Str::random(4)),
            'description' => trim($this->new_description) ?: 'Layanan perawatan higienis dan profesional.',
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
        $this->authorizeOwner();

        $service = Service::findOrFail($id);
        $this->edit_service_id = $service->id;
        $this->edit_name = $service->name;
        $this->edit_description = (string) $service->description;
        $this->edit_price = (int) $service->price;
        $this->edit_duration = (int) $service->duration_minutes;
        $this->edit_icon = in_array($service->icon, self::ALLOWED_ICONS, true) ? $service->icon : 'sparkles';
        $this->edit_badge_color = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $service->badge_color) ? $service->badge_color : '#FFBA08';

        $this->showEditServiceModal = true;
    }

    public function updateService(): void
    {
        $this->authorizeOwner();

        $this->validate([
            'edit_name' => ['required', 'string', 'min:3', 'max:100'],
            'edit_description' => ['nullable', 'string', 'max:500'],
            'edit_price' => ['required', 'integer', 'min:1000', 'max:50000000'],
            'edit_duration' => ['required', 'integer', 'min:15', 'max:180'],
            'edit_icon' => ['required', 'string', Rule::in(self::ALLOWED_ICONS)],
            'edit_badge_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $service = Service::findOrFail($this->edit_service_id);
        $service->update([
            'name' => trim($this->edit_name),
            'description' => trim($this->edit_description),
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
        $this->authorizeOwner();

        $service = Service::findOrFail($id);
        $name = $service->name;
        $service->delete();

        session()->flash('success', 'Layanan '.$name.' berhasil dihapus.');
    }

    public function toggleServiceStatus(int $id): void
    {
        $this->authorizeOwner();

        $service = Service::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);
        session()->flash('success', 'Status layanan '.$service->name.' diperbarui.');
    }

    /* ---------------- STAFF CRUD ---------------- */

    public function createStaff(): void
    {
        $this->authorizeOwner();

        $this->validate([
            'new_staff_name' => ['required', 'string', 'min:3', 'max:100'],
            'new_staff_nickname' => ['required', 'string', 'min:2', 'max:50'],
            'new_staff_title' => ['required', 'string', 'max:100'],
            'new_staff_bio' => ['nullable', 'string', 'max:500'],
            'new_staff_phone' => ['required', 'string', 'min:9', 'max:20', 'unique:users,phone'],
            'new_staff_email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'new_staff_badge_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ], [
            'new_staff_name.required' => 'Nama lengkap staf wajib diisi.',
            'new_staff_nickname.required' => 'Nama panggilan staf wajib diisi.',
            'new_staff_phone.required' => 'Nomor HP staf wajib diisi.',
            'new_staff_phone.unique' => 'Nomor HP ini sudah digunakan akun lain.',
        ]);

        DB::transaction(function (): void {
            $user = User::create([
                'name' => trim($this->new_staff_name),
                'email' => $this->new_staff_email ? trim($this->new_staff_email) : 'staff.'.strtolower(Str::random(8)).'@pawsh.test',
                'phone' => trim($this->new_staff_phone),
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);

            StaffProfile::create([
                'user_id' => $user->id,
                'nickname' => trim($this->new_staff_nickname),
                'title' => trim($this->new_staff_title),
                'bio' => trim($this->new_staff_bio) ?: 'Staf groomer profesional berdedikasi tinggi.',
                'badge_color' => $this->new_staff_badge_color,
                'is_active' => true,
            ]);
        });

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
        $this->authorizeOwner();

        $staff = StaffProfile::with('user')->findOrFail($id);
        $this->edit_staff_id = $staff->id;
        $this->edit_staff_name = $staff->user->name;
        $this->edit_staff_nickname = $staff->nickname;
        $this->edit_staff_title = $staff->title;
        $this->edit_staff_bio = (string) $staff->bio;
        $this->edit_staff_phone = (string) $staff->user->phone;
        $this->edit_staff_badge_color = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $staff->badge_color) ? $staff->badge_color : '#FFBA08';

        $this->showEditStaffModal = true;
    }

    public function updateStaff(): void
    {
        $this->authorizeOwner();

        $staff = StaffProfile::with('user')->findOrFail($this->edit_staff_id);

        $this->validate([
            'edit_staff_name' => ['required', 'string', 'min:3', 'max:100'],
            'edit_staff_nickname' => ['required', 'string', 'min:2', 'max:50'],
            'edit_staff_title' => ['required', 'string', 'max:100'],
            'edit_staff_bio' => ['nullable', 'string', 'max:500'],
            'edit_staff_phone' => ['required', 'string', 'min:9', 'max:20', Rule::unique('users', 'phone')->ignore($staff->user_id)],
            'edit_staff_badge_color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        DB::transaction(function () use ($staff): void {
            $staff->update([
                'nickname' => trim($this->edit_staff_nickname),
                'title' => trim($this->edit_staff_title),
                'bio' => trim($this->edit_staff_bio),
                'badge_color' => $this->edit_staff_badge_color,
            ]);

            $staff->user->update([
                'name' => trim($this->edit_staff_name),
                'phone' => trim($this->edit_staff_phone),
            ]);
        });

        $this->showEditStaffModal = false;
        $this->edit_staff_id = null;

        session()->flash('success', 'Data groomer '.$staff->nickname.' berhasil diperbarui.');
    }

    public function deleteStaff(int $id): void
    {
        $this->authorizeOwner();

        $staff = StaffProfile::with('user')->findOrFail($id);
        $name = $staff->nickname;
        $user = $staff->user;

        DB::transaction(function () use ($staff, $user): void {
            $staff->delete();
            if ($user && $user->id !== Auth::id()) {
                $user->delete();
            }
        });

        session()->flash('success', 'Staf groomer '.$name.' berhasil dihapus dari sistem.');
    }

    public function toggleStaffStatus(int $id): void
    {
        $this->authorizeOwner();

        $staff = StaffProfile::findOrFail($id);
        $staff->update(['is_active' => ! $staff->is_active]);
        session()->flash('success', 'Status groomer '.$staff->nickname.' diperbarui.');
    }

    public function render()
    {
        $this->authorizeOwner();

        return view('livewire.owner-portal', [
            'totalAppointments' => Appointment::count(),
            'totalRevenue' => Transaction::where('status', 'paid')->sum('amount'),
            'services' => Service::orderBy('id')->get(),
            'staffList' => StaffProfile::with('user')->orderBy('id')->get(),
            'recentTransactions' => Transaction::with(['appointment.service', 'appointment.staff'])
                ->latest()
                ->take(8)
                ->get(),
        ])->layout('layouts.app', ['title' => 'Owner Management Portal - AntreanKu']);
    }
}
