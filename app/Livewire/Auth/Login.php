<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public string $login = ''; // Can be phone or email

    public string $password = '';

    public bool $remember = false;

    /**
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'login' => 'required|string|max:120',
            'password' => 'required|string|max:128',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'login.required' => 'Nomor HP atau Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }

    public function authenticate()
    {
        $this->validate();

        $normalizedLogin = Str::lower(trim($this->login));
        $throttleKey = 'login:'.$normalizedLogin.'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('login', 'Terlalu banyak percobaan masuk. Coba lagi dalam '.$seconds.' detik.');

            return;
        }

        // Determine if login input is email or phone
        $loginType = filter_var(trim($this->login), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => trim($this->login),
            'password' => $this->password,
        ];

        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('login', 'Kredensial yang dimasukkan tidak cocok dengan catatan kami.');

            return;
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        $user = Auth::user();

        session()->flash('success', 'Selamat datang kembali, '.$user->name.'!');

        $intended = session()->pull('url.intended');
        if ($intended && str_starts_with($intended, url('/'))) {
            return $this->redirect($intended, navigate: true);
        }

        if ($user->role === 'owner') {
            return $this->redirect(route('owner'), navigate: true);
        }

        if ($user->role === 'staff') {
            return $this->redirect(route('workstation'), navigate: true);
        }

        return $this->redirect(route('customer-dashboard'), navigate: true);
    }

    public function fillCredentials(string $login, string $password = 'password'): void
    {
        $this->login = $login;
        $this->password = $password;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.app', ['title' => 'Masuk ke Akun - AntreanKu']);
    }
}
