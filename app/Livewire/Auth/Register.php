<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required|string|min:6|confirmed',
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
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => null,
            'password' => Hash::make($this->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        session()->flash('success', 'Pendaftaran berhasil! Selamat datang di AntreanKu, '.$user->name.'.');

        $intended = session()->pull('url.intended');
        if ($intended) {
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
