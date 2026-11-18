<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial demo data.
     */
    public function run(): void
    {
        // 1. Owner Account
        $owner = User::create([
            'name' => 'Dr. Hendra',
            'email' => 'owner@pawsh.test',
            'phone' => '081100000001',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // 2. Staff Accounts & Profiles
        $staffData = [
            [
                'name' => 'Sari Handayani',
                'email' => 'sari@pawsh.test',
                'phone' => '081100000002',
                'nickname' => 'Kak Sari',
                'title' => 'Senior Cat Specialist & Spa',
                'bio' => 'Berpengalaman 6+ tahun menangani kucing ras, grooming anti-stress, dan perawatan bulu rontok.',
                'badge_color' => '#A7D7C5',
                'avatar' => 'sari',
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas@pawsh.test',
                'phone' => '081100000003',
                'nickname' => 'Mas Dimas',
                'title' => 'Canine Styling & Haircut Pro',
                'bio' => 'Spesialis gunting model teddy bear, poodles, pom, dan corgi dengan teknik lembut dan presisi.',
                'badge_color' => '#FFBA08',
                'avatar' => 'dimas',
            ],
            [
                'name' => 'Rian Maulana',
                'email' => 'rian@pawsh.test',
                'phone' => '081100000004',
                'nickname' => 'Kak Rian',
                'title' => 'Medicated Bath & Flea Expert',
                'bio' => 'Ahli terapi mandi obat jamur, kutu, dan perawatan higienitas kuku serta telinga.',
                'badge_color' => '#BEE1E6',
                'avatar' => 'rian',
            ],
        ];

        $staffProfiles = [];
        foreach ($staffData as $item) {
            $user = User::create([
                'name' => $item['name'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);

            $profile = StaffProfile::create([
                'user_id' => $user->id,
                'nickname' => $item['nickname'],
                'title' => $item['title'],
                'bio' => $item['bio'],
                'avatar' => $item['avatar'],
                'badge_color' => $item['badge_color'],
                'is_active' => true,
            ]);

            $staffProfiles[] = $profile;
        }

        // 2B. Sample Customer Account
        User::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@gmail.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // 3. Services Catalog
        $services = [
            [
                'name' => 'Basic Fresh Bath & Blow Dry',
                'slug' => 'basic-fresh-bath',
                'description' => 'Mandi air hangat terapeutik, shampoo hypoallergenic organik, pembersihan telinga, dan pengeringan bulu sampai tuntas.',
                'price' => 55000,
                'duration_minutes' => 30,
                'icon' => 'bath',
                'badge_color' => '#BEE1E6',
                'is_active' => true,
            ],
            [
                'name' => 'Full Grooming & Fur Styling',
                'slug' => 'full-grooming-styling',
                'description' => 'Paket komplit mandi bersih, potong kuku, rapikan bulu telapak kaki, higienitas genital, dan potong styling bulu wajah/badan.',
                'price' => 95000,
                'duration_minutes' => 30,
                'icon' => 'scissors',
                'badge_color' => '#FFBA08',
                'is_active' => true,
            ],
            [
                'name' => 'Anti-Flea & Fungal Medicated Spa',
                'slug' => 'anti-flea-fungal-spa',
                'description' => 'Perawatan mandi khusus obat anti-kutu dan anti-jamur bersertifikat medis hewan, perendaman relaksasi, dan pengeringan steril.',
                'price' => 80000,
                'duration_minutes' => 30,
                'icon' => 'sparkles',
                'badge_color' => '#A7D7C5',
                'is_active' => true,
            ],
            [
                'name' => 'Express Paw & Nail Care',
                'slug' => 'express-paw-nail-care',
                'description' => 'Layanan cepat pemotongan kuku, perataan kikir halus, pembersihan telinga, dan pemberian vitamin pelembap telapak kaki.',
                'price' => 35000,
                'duration_minutes' => 30,
                'icon' => 'dog',
                'badge_color' => '#D8F3DC',
                'is_active' => true,
            ],
        ];

        $createdServices = [];
        foreach ($services as $service) {
            $createdServices[] = Service::create($service);
        }

        // 4. Today's Realistic Appointments
        $today = Carbon::today()->format('Y-m-d');

        // Ticket 1: Completed & Paid
        $app1 = Appointment::create([
            'ticket_number' => 'PAW-01',
            'tracking_code' => 'TRK-PAW01'.strtoupper(Str::random(4)),
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'pet_name' => 'Milo',
            'pet_type' => 'Kucing Persian',
            'staff_id' => $staffProfiles[0]->id, // Kak Sari
            'service_id' => $createdServices[1]->id, // Full Grooming
            'booking_date' => $today,
            'time_slot' => '09:00',
            'type' => 'online',
            'status' => 'completed',
            'notes' => 'Telinga agak sensitif, mohon perlahan.',
        ]);

        Transaction::create([
            'appointment_id' => $app1->id,
            'invoice_number' => 'INV-'.date('Ymd').'-001',
            'amount' => $createdServices[1]->price,
            'payment_method' => 'qris',
            'status' => 'paid',
            'paid_at' => Carbon::now()->subMinutes(45),
            'cashier_name' => 'Kasir Utama',
        ]);

        // Ticket 2: Serving right now
        $app2 = Appointment::create([
            'ticket_number' => 'PAW-02',
            'tracking_code' => 'TRK-PAW02'.strtoupper(Str::random(4)),
            'customer_name' => 'Jessica Tan',
            'customer_phone' => '081987654321',
            'pet_name' => 'Chiko',
            'pet_type' => 'Anjing Poodle',
            'staff_id' => $staffProfiles[1]->id, // Mas Dimas
            'service_id' => $createdServices[0]->id, // Basic Fresh Bath
            'booking_date' => $today,
            'time_slot' => '09:30',
            'type' => 'walk_in',
            'status' => 'serving',
            'notes' => 'Pelanggan walk-in datang langsung ke kasir.',
        ]);

        // Ticket 3: Waiting in line
        $app3 = Appointment::create([
            'ticket_number' => 'PAW-03',
            'tracking_code' => 'TRK-PAW03'.strtoupper(Str::random(4)),
            'customer_name' => 'Rian Prasetyo',
            'customer_phone' => '082112233445',
            'pet_name' => 'Si Oyen',
            'pet_type' => 'Kucing Domestik',
            'staff_id' => $staffProfiles[0]->id, // Kak Sari
            'service_id' => $createdServices[2]->id, // Anti-Flea Spa
            'booking_date' => $today,
            'time_slot' => '10:00',
            'type' => 'online',
            'status' => 'pending',
            'notes' => 'Ada jamur kecil di bagian leher.',
        ]);

        // Ticket 4: Waiting in line
        $app4 = Appointment::create([
            'ticket_number' => 'PAW-04',
            'tracking_code' => 'TRK-PAW04'.strtoupper(Str::random(4)),
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '085678901234',
            'pet_name' => 'Brownie',
            'pet_type' => 'Anjing Corgi',
            'staff_id' => $staffProfiles[1]->id, // Mas Dimas
            'service_id' => $createdServices[1]->id, // Full Grooming
            'booking_date' => $today,
            'time_slot' => '10:30',
            'type' => 'online',
            'status' => 'pending',
            'notes' => 'Potong rapi pantat model corgi bulat.',
        ]);
    }
}
