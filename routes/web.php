<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\BookingForm;
use App\Livewire\CashierWorkstation;
use App\Livewire\CheckTicket;
use App\Livewire\CustomerDashboard;
use App\Livewire\OwnerPortal;
use App\Livewire\TicketStatus;
use App\Livewire\UserProfile;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', function () {
    return view('welcome', [
        'services' => Service::query()
            ->select(['id', 'name', 'slug', 'description', 'price', 'duration_minutes', 'icon', 'badge_color'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get(),
        'staffProfiles' => StaffProfile::query()
            ->select(['id', 'nickname', 'title', 'bio', 'avatar', 'badge_color'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get(),
        'servingAppointment' => Appointment::query()
            ->select(['id', 'ticket_number', 'pet_name', 'status', 'booking_date'])
            ->whereDate('booking_date', Carbon::today()->format('Y-m-d'))
            ->where('status', 'serving')
            ->first(),
    ]);
})->name('home');

Route::get('/katalog', function () {
    return redirect('/#katalog');
})->name('catalog');

Route::get('/booking', BookingForm::class)->name('booking');
Route::get('/tiket/{tracking_code}', TicketStatus::class)->name('ticket-status');
Route::get('/cek-tiket', CheckTicket::class)->name('check-ticket');

// Authentication Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// Role Protected Dashboards & Profile
Route::middleware('auth')->group(function (): void {
    Route::get('/profil', UserProfile::class)->name('profile');
    Route::get('/customer/dashboard', CustomerDashboard::class)->name('customer-dashboard');
    Route::get('/app/workstation', CashierWorkstation::class)
        ->middleware('role:owner,staff')
        ->name('workstation');
    Route::get('/app/owner', OwnerPortal::class)
        ->middleware('role:owner')
        ->name('owner');
});
