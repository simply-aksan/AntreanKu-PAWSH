<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserProfile extends Component
{
    // Profile Fields
    public string $name = '';

    public string $phone = '';

    public ?string $email = null;

    // Staff-Specific Fields
    public ?string $nickname = '';

    public ?string $title = '';

    public ?string $bio = '';

    // Password Fields
    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function mount()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = (string) $user->phone;
        $this->email = $user->email;

        if ($user->role === 'staff' && $user->staffProfile) {
            $this->nickname = $user->staffProfile->nickname;
            $this->title = $user->staffProfile->title;
            $this->bio = $user->staffProfile->bio;
        }
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|min:3|max:100',
            'phone' => [
                'required',
                'string',
                'min:9',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ];

        if ($user->role === 'staff') {
            $rules['nickname'] = 'nullable|string|max:50';
            $rules['title'] = 'nullable|string|max:100';
            $rules['bio'] = 'nullable|string|max:300';
        }

        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp ini sudah digunakan oleh akun lain.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
        ];

        $this->validate($rules, $messages);

        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->email = $this->email ?: null;
        $user->save();

        if ($user->role === 'staff' && $user->staffProfile) {
            $user->staffProfile->update([
                'nickname' => $this->nickname,
                'title' => $this->title,
                'bio' => $this->bio,
            ]);
        }

        session()->flash('profile_success', 'Data profil Anda berhasil disimpan.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required|current_password:web',
            'new_password' => 'required|string|min:6|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            'new_password.different' => 'Kata sandi baru harus berbeda dari kata sandi saat ini.',
            'new_password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
            'new_password_confirmation.same' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_success', 'Kata sandi berhasil diperbarui.');
    }

    public function saveAvatar(string $base64Data): void
    {
        $user = Auth::user();

        // Validate base64 image data
        if (! preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
            $this->addError('avatar', 'Format gambar tidak valid.');

            return;
        }

        $imageType = strtolower($matches[1]);
        if (! in_array($imageType, ['jpg', 'jpeg', 'png', 'webp'])) {
            $this->addError('avatar', 'Format gambar harus JPG, PNG, atau WEBP.');

            return;
        }

        $data = substr($base64Data, strpos($base64Data, ',') + 1);
        $decodedData = base64_decode($data);

        if ($decodedData === false) {
            $this->addError('avatar', 'Gagal memproses gambar avatar.');

            return;
        }

        // Limit size to max 3MB
        if (strlen($decodedData) > 3 * 1024 * 1024) {
            $this->addError('avatar', 'Ukuran foto maksimal adalah 3 MB.');

            return;
        }

        $avatarDir = public_path('uploads/avatars');
        if (! file_exists($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        // Delete old avatar file if exists
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }

        $fileName = 'avatar_'.$user->id.'_'.time().'.png';
        $relativeFilePath = 'uploads/avatars/'.$fileName;
        file_put_contents(public_path($relativeFilePath), $decodedData);

        $user->avatar = $relativeFilePath;
        $user->save();

        // If staff, also mirror to staffProfile avatar for consistent public display
        if ($user->role === 'staff' && $user->staffProfile) {
            $user->staffProfile->update([
                'avatar' => $relativeFilePath,
            ]);
        }

        session()->flash('profile_success', 'Foto profil berhasil disimpan dan diperbarui.');
    }

    public function deleteAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }

        $user->avatar = null;
        $user->save();

        if ($user->role === 'staff' && $user->staffProfile) {
            $user->staffProfile->update([
                'avatar' => null,
            ]);
        }

        session()->flash('profile_success', 'Foto profil telah dihapus dan dikembalikan ke inisial nama.');
    }

    public function render()
    {
        $user = Auth::user();

        $stats = [];
        if ($user->role === 'customer') {
            $stats['total_tickets'] = Appointment::where('customer_phone', $user->phone)->count();
            $stats['active_tickets'] = Appointment::where('customer_phone', $user->phone)
                ->whereIn('status', ['pending', 'serving'])
                ->count();
        } elseif ($user->role === 'staff') {
            $staffProfile = $user->staffProfile;
            $stats['handled_tickets'] = $staffProfile
                ? Appointment::where('staff_id', $staffProfile->id)->where('status', 'completed')->count()
                : 0;
            $stats['today_tickets'] = $staffProfile
                ? Appointment::where('staff_id', $staffProfile->id)
                    ->whereDate('booking_date', now()->format('Y-m-d'))
                    ->count()
                : 0;
        } elseif ($user->role === 'owner') {
            $stats['total_services'] = Service::count();
            $stats['total_staff'] = StaffProfile::count();
            $stats['total_appointments'] = Appointment::count();
        }

        return view('livewire.user-profile', [
            'user' => $user,
            'stats' => $stats,
        ])->layout('layouts.app', ['title' => 'Pengaturan Profil - AntreanKu']);
    }
}
