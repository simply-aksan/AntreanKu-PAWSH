<?php

namespace Tests\Feature;

use App\Livewire\AiConsultantModal;
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
use App\Models\User;
use App\Services\GeminiConsultantService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AntreanKuFeatureTest extends TestCase
{
    use RefreshDatabase;

    private Service $service;

    private StaffProfile $staff;

    private User $staffUser;

    private User $ownerUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create initial service
        $this->service = Service::create([
            'name' => 'Basic Fresh Bath & Blow Dry',
            'slug' => 'basic-fresh-bath',
            'description' => 'Mandi segar untuk anabul tercinta.',
            'price' => 55000,
            'duration_minutes' => 30,
            'icon' => 'bath',
            'badge_color' => '#BAE6FD',
            'is_active' => true,
        ]);

        // Create staff user and profile
        $this->staffUser = User::create([
            'name' => 'Sari Handayani',
            'email' => 'sari@pawsh.test',
            'phone' => '081100000002',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->staff = StaffProfile::create([
            'user_id' => $this->staffUser->id,
            'nickname' => 'Kak Sari',
            'title' => 'Senior Feline Groomer',
            'bio' => 'Spesialis perawatan kucing.',
            'badge_color' => '#A6F4C5',
            'is_active' => true,
        ]);

        // Create owner user
        $this->ownerUser = User::create([
            'name' => 'Dr. Hendra',
            'email' => 'owner@pawsh.test',
            'phone' => '081100000001',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('PAWSH!');
        $response->assertSee('Basic Fresh Bath & Blow Dry');
        $response->assertSee('Kak Sari');
    }

    public function test_customer_can_register_with_name_phone_and_password(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'Pelanggan Baru')
            ->set('phone', '081999888777')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('registerCustomer')
            ->assertHasNoErrors()
            ->assertRedirect(route('customer-dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'Pelanggan Baru',
            'phone' => '081999888777',
            'role' => 'customer',
        ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_login_using_phone_or_email(): void
    {
        Livewire::test(Login::class)
            ->set('login', '081100000001')
            ->set('password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner'));

        $this->assertAuthenticatedAs($this->ownerUser);
    }

    public function test_customer_dashboard_renders_active_and_past_tickets(): void
    {
        $customer = User::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $today = Carbon::today()->format('Y-m-d');

        Appointment::create([
            'ticket_number' => 'PAW-55',
            'tracking_code' => 'TRK-BUDI55',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'pet_name' => 'Milo',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '11:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        $this->actingAs($customer);

        Livewire::test(CustomerDashboard::class)
            ->assertSee('PAW-55')
            ->assertSee('Milo')
            ->assertSee('Budi Santoso');
    }

    public function test_customer_can_book_appointment_and_receive_ticket(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $customer = User::create([
            'name' => 'Ahmad Dhani',
            'phone' => '081234567890',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $this->actingAs($customer);

        Livewire::test(BookingForm::class)
            ->set('service_id', $this->service->id)
            ->set('staff_id', $this->staff->id)
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('booking_date', $today)
            ->set('time_slot', '10:00')
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->set('customer_name', 'Ahmad Dhani')
            ->set('customer_phone', '081234567890')
            ->set('pet_name', 'Milo')
            ->set('pet_type', 'Kucing')
            ->set('notes', 'Bulu agak gimbal di ekor.')
            ->call('submitBooking')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('appointments', [
            'customer_name' => 'Ahmad Dhani',
            'customer_phone' => '081234567890',
            'pet_name' => 'Milo',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'time_slot' => '10:00',
            'type' => 'online',
            'status' => 'pending',
        ]);
    }

    public function test_system_prevents_double_booking_on_same_staff_date_and_time(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        // First appointment
        Appointment::create([
            'ticket_number' => 'PAW-01',
            'tracking_code' => 'TRK-FIRST123',
            'customer_name' => 'Pelanggan Pertama',
            'customer_phone' => '081111111111',
            'pet_name' => 'Kocheng',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '10:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        // Second booking attempt on identical staff, date, and time slot
        $customer = User::create([
            'name' => 'Pelanggan Kedua',
            'phone' => '082222222222',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $this->actingAs($customer);

        Livewire::test(BookingForm::class)
            ->set('service_id', $this->service->id)
            ->set('staff_id', $this->staff->id)
            ->set('booking_date', $today)
            ->set('time_slot', '10:00')
            ->set('customer_name', 'Pelanggan Kedua')
            ->set('customer_phone', '082222222222')
            ->set('pet_name', 'Chiki')
            ->set('pet_type', 'Kucing')
            ->call('submitBooking')
            ->assertHasErrors(['time_slot']);

        $this->assertEquals(1, Appointment::where('staff_id', $this->staff->id)->whereDate('booking_date', $today)->where('time_slot', '10:00')->count());
    }

    public function test_customer_can_view_ticket_status_and_cancel_it(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $appointment = Appointment::create([
            'ticket_number' => 'PAW-09',
            'tracking_code' => 'TRK-CANCELME',
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '083333333333',
            'pet_name' => 'Mimi',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '14:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        $response = $this->get(route('ticket-status', ['tracking_code' => 'TRK-CANCELME']));
        $response->assertStatus(200);
        $response->assertSee('PAW-09');
        $response->assertSee('Mimi');

        Livewire::test(TicketStatus::class, ['tracking_code' => 'TRK-CANCELME'])
            ->call('cancelAppointment')
            ->assertHasNoErrors();

        $this->assertEquals('cancelled', $appointment->fresh()->status);
    }

    public function test_customer_can_search_ticket_by_phone_number(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        Appointment::create([
            'ticket_number' => 'PAW-12',
            'tracking_code' => 'TRK-SEARCH12',
            'customer_name' => 'Bambang Sudirman',
            'customer_phone' => '087788990011',
            'pet_name' => 'Oreo',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '11:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        Livewire::test(CheckTicket::class)
            ->set('query', '087788990011')
            ->call('search')
            ->assertSee('PAW-12')
            ->assertSee('Oreo');
    }

    public function test_cashier_and_owner_can_access_workstation_and_process_payments(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $appointment = Appointment::create([
            'ticket_number' => 'PAW-20',
            'tracking_code' => 'TRK-PAY20',
            'customer_name' => 'Dewi Sandra',
            'customer_phone' => '089988776655',
            'pet_name' => 'Luna',
            'pet_type' => 'Anjing Kecil',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '13:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        // 1. Staff access
        $this->actingAs($this->staffUser);

        Livewire::test(CashierWorkstation::class)
            ->set('selectedDate', $today)
            ->call('startServing', $appointment->id);

        $this->assertEquals('serving', $appointment->fresh()->status);

        // 2. Owner access to Cashier Workstation (Owner has full cashier access)
        $this->actingAs($this->ownerUser);

        Livewire::test(CashierWorkstation::class)
            ->set('selectedDate', $today)
            ->set('payingAppointmentId', $appointment->id)
            ->set('payment_method', 'qris')
            ->call('confirmPayment')
            ->assertHasNoErrors();

        $this->assertEquals('completed', $appointment->fresh()->status);
        $this->assertDatabaseHas('transactions', [
            'appointment_id' => $appointment->id,
            'payment_method' => 'qris',
            'status' => 'paid',
            'amount' => 55000,
        ]);
    }

    public function test_cashier_can_register_walk_in_customer(): void
    {
        $this->actingAs($this->staffUser);
        $today = Carbon::today()->format('Y-m-d');

        Livewire::test(CashierWorkstation::class)
            ->set('selectedDate', $today)
            ->set('walkIn_customer_name', 'Pelanggan Datang Langsung')
            ->set('walkIn_customer_phone', '085544332211')
            ->set('walkIn_pet_name', 'Rocky')
            ->set('walkIn_pet_type', 'Anjing Kecil')
            ->set('walkIn_service_id', $this->service->id)
            ->set('walkIn_staff_id', $this->staff->id)
            ->set('walkIn_time_slot', '15:30')
            ->call('submitWalkIn')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('appointments', [
            'customer_name' => 'Pelanggan Datang Langsung',
            'pet_name' => 'Rocky',
            'type' => 'walk_in',
            'status' => 'pending',
            'time_slot' => '15:30',
        ]);
    }

    public function test_owner_portal_full_crud_operations(): void
    {
        $this->actingAs($this->ownerUser);

        // 1. Service Update & Delete
        Livewire::test(OwnerPortal::class)
            ->call('openEditService', $this->service->id)
            ->set('edit_name', 'Paket Bath & Spa Mewah')
            ->set('edit_price', 75000)
            ->set('edit_duration', 45)
            ->call('updateService')
            ->assertHasNoErrors();

        $this->assertEquals('Paket Bath & Spa Mewah', $this->service->fresh()->name);
        $this->assertEquals(75000, $this->service->fresh()->price);

        // 2. Staff Create, Update & Delete
        Livewire::test(OwnerPortal::class)
            ->set('new_staff_name', 'Maya Anggraini')
            ->set('new_staff_nickname', 'Kak Maya')
            ->set('new_staff_title', 'Spesialis Kucing Ras')
            ->set('new_staff_phone', '081299998888')
            ->call('createStaff')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('staff_profiles', [
            'nickname' => 'Kak Maya',
        ]);

        $createdStaff = StaffProfile::where('nickname', 'Kak Maya')->first();

        Livewire::test(OwnerPortal::class)
            ->call('openEditStaff', $createdStaff->id)
            ->set('edit_staff_title', 'Master Feline Stylist')
            ->call('updateStaff')
            ->assertHasNoErrors();

        $this->assertEquals('Master Feline Stylist', $createdStaff->fresh()->title);

        Livewire::test(OwnerPortal::class)
            ->call('deleteStaff', $createdStaff->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('staff_profiles', [
            'id' => $createdStaff->id,
        ]);
    }

    public function test_customer_can_search_and_check_tickets_from_customer_dashboard(): void
    {
        $customer = User::create([
            'name' => 'Rina Nose',
            'phone' => '081233445566',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $today = Carbon::today()->format('Y-m-d');

        Appointment::create([
            'ticket_number' => 'PAW-77',
            'tracking_code' => 'TRK-RINA77',
            'customer_name' => 'Rina Nose',
            'customer_phone' => '081233445566',
            'pet_name' => 'Chiko',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '13:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        $this->actingAs($customer);

        Livewire::test(CustomerDashboard::class)
            ->set('searchQuery', 'PAW-77')
            ->call('searchTicket')
            ->assertHasNoErrors()
            ->assertSee('PAW-77')
            ->assertSee('Chiko')
            ->assertSee('TRK-RINA77')
            ->call('resetSearch')
            ->assertSet('hasSearched', false)
            ->assertSet('searchQuery', '');
    }

    public function test_guest_is_redirected_to_login_when_accessing_booking_page(): void
    {
        Livewire::test(BookingForm::class)
            ->assertRedirect(route('login'));

        $this->assertEquals(route('booking'), session('url.intended'));
        $this->assertTrue(session()->has('warning'));
    }

    public function test_login_redirects_to_intended_booking_url(): void
    {
        $customer = User::create([
            'name' => 'Intended User',
            'phone' => '087777777777',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        session()->put('url.intended', route('booking'));

        Livewire::test(Login::class)
            ->set('login', '087777777777')
            ->set('password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect(route('booking'));
    }

    public function test_user_can_view_and_update_profile(): void
    {
        $customer = User::create([
            'name' => 'Budi Lama',
            'phone' => '081234567899',
            'email' => 'budi@pawsh.test',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($customer);

        Livewire::test(UserProfile::class)
            ->assertSee('Budi Lama')
            ->assertSee('081234567899')
            ->set('name', 'Budi Baru')
            ->set('phone', '089876543210')
            ->set('email', 'budibaru@pawsh.test')
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertEquals('Budi Baru', $customer->fresh()->name);
        $this->assertEquals('089876543210', $customer->fresh()->phone);
        $this->assertEquals('budibaru@pawsh.test', $customer->fresh()->email);
    }

    public function test_user_can_update_password(): void
    {
        $customer = User::create([
            'name' => 'Ganti Sandi',
            'phone' => '085555555555',
            'password' => bcrypt('oldpassword'),
            'role' => 'customer',
        ]);

        $this->actingAs($customer);

        Livewire::test(UserProfile::class)
            ->set('current_password', 'oldpassword')
            ->set('new_password', 'newsecret123')
            ->set('new_password_confirmation', 'newsecret123')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newsecret123', $customer->fresh()->password));
    }

    public function test_user_can_upload_and_delete_avatar(): void
    {
        $customer = User::create([
            'name' => 'Foto User',
            'phone' => '087711223344',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($customer);

        // 1x1 base64 transparent PNG
        $sampleBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(UserProfile::class)
            ->call('saveAvatar', $sampleBase64)
            ->assertHasNoErrors();

        $updatedAvatar = $customer->fresh()->avatar;
        $this->assertNotNull($updatedAvatar);
        $this->assertFileExists(public_path($updatedAvatar));

        // Delete Avatar
        Livewire::test(UserProfile::class)
            ->call('deleteAvatar')
            ->assertHasNoErrors();

        $this->assertNull($customer->fresh()->avatar);
        $this->assertFileDoesNotExist(public_path($updatedAvatar));
    }

    public function test_ai_consultant_analyzes_pet_condition_and_recommends_service(): void
    {
        Livewire::test(AiConsultantModal::class)
            ->call('openModal')
            ->assertSet('isOpen', true)
            ->set('petQuery', 'Kucing saya kutuan dan jamuran di leher')
            ->call('askAi', app(GeminiConsultantService::class))
            ->assertHasNoErrors()
            ->assertSet('result.recommended_service_id', $this->service->id)
            ->assertSee('Hasil Analisis AI')
            ->call('closeModal')
            ->assertSet('isOpen', false);
    }

    public function test_security_headers_are_applied_to_responses(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_role_middleware_blocks_unauthorized_access_to_owner_and_workstation(): void
    {
        // Guest redirected to login
        $this->get(route('owner'))->assertRedirect(route('login'));
        $this->get(route('workstation'))->assertRedirect(route('login'));

        // Customer blocked with 403
        $customer = User::create([
            'name' => 'Regular Customer',
            'phone' => '081200001111',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $this->actingAs($customer);
        $this->get(route('owner'))->assertStatus(403);
        $this->get(route('workstation'))->assertStatus(403);

        // Staff blocked from Owner portal with 403
        $this->actingAs($this->staffUser);
        $this->get(route('owner'))->assertStatus(403);
        $this->get(route('workstation'))->assertStatus(200);
    }

    public function test_customer_without_phone_cannot_cancel_another_customers_ticket(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $victimAppointment = Appointment::create([
            'ticket_number' => 'PAW-99',
            'tracking_code' => 'TRK-VICTIM99',
            'customer_name' => 'Korban Pelanggan',
            'customer_phone' => '081999111222',
            'pet_name' => 'Bola',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '16:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        $noPhoneUser = User::create([
            'name' => 'No Phone User',
            'email' => 'nophone@pawsh.test',
            'phone' => null,
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($noPhoneUser);

        Livewire::test(CustomerDashboard::class)
            ->call('cancelTicket', $victimAppointment->id)
            ->assertForbidden();

        $this->assertEquals('pending', $victimAppointment->fresh()->status);
    }

    public function test_public_check_ticket_prevents_wildcard_pii_enumeration(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        Appointment::create([
            'ticket_number' => 'PAW-88',
            'tracking_code' => 'TRK-EXACT888',
            'customer_name' => 'Rahasia Pelanggan',
            'customer_phone' => '081888777666',
            'pet_name' => 'Snowy',
            'pet_type' => 'Kucing',
            'staff_id' => $this->staff->id,
            'service_id' => $this->service->id,
            'booking_date' => $today,
            'time_slot' => '17:00',
            'type' => 'online',
            'status' => 'pending',
        ]);

        // Partial phone prefix must not leak tickets
        Livewire::test(CheckTicket::class)
            ->set('query', '08188')
            ->call('search')
            ->assertDontSee('Rahasia Pelanggan')
            ->assertDontSee('PAW-88');

        // Exact tracking code succeeds
        Livewire::test(CheckTicket::class)
            ->set('query', 'TRK-EXACT888')
            ->call('search')
            ->assertSee('PAW-88')
            ->assertSee('Snowy');
    }

    public function test_owner_portal_rejects_css_injection_in_badge_color(): void
    {
        $this->actingAs($this->ownerUser);

        Livewire::test(OwnerPortal::class)
            ->set('new_name', 'Paket Tes Warna')
            ->set('new_price', 50000)
            ->set('new_duration', 30)
            ->set('new_badge_color', '#FFBA08; background:url(javascript:alert(1))')
            ->call('createService')
            ->assertHasErrors(['new_badge_color']);
    }

    public function test_login_rate_limiter_blocks_brute_force_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->set('login', 'owner@pawsh.test')
                ->set('password', 'wrong-password')
                ->call('authenticate')
                ->assertHasErrors(['login']);
        }

        Livewire::test(Login::class)
            ->set('login', 'owner@pawsh.test')
            ->set('password', 'password')
            ->call('authenticate')
            ->assertHasErrors(['login']);

        $this->assertGuest();
    }
}
