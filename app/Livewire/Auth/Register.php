<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'phone' => 'required|string|min:9|max:20|unique:users,phone',
            'password' => 'required|string|min:6|max:128|confirmed',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp ini sudah terdaftar. Silakan langsung masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }

    public function registerCustomer()
    {
        $throttleKey = 'register:'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('phone', 'Terlalu banyak percobaan pendaftaran. Silakan tunggu '.$seconds.' detik.');

            return;
        }

        $this->phone = trim($this->phone);
        $this->name = trim($this->name);
        $this->validate();

        RateLimiter::hit($throttleKey, 60);

        $user = User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => null,
            'password' => Hash::make($this->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        session()->regenerate();

        session()->flash('success', 'Pendaftaran berhasil! Selamat datang di AntreanKu, '.$user->name.'.');

        $intended = session()->pull('url.intended');
        if ($intended && str_starts_with($intended, url('/'))) {
            return $this->redirect($intended, navigate: true);
        }

        return $this->redirect(route('customer-dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.app', ['title' => 'Pendaftaran Pelanggan Baru - AntreanKu']);
    }
}
