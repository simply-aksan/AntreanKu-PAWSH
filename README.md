# AntreanKu &bull; PAWSH! Pet Grooming & Studio

> **Sistem Manajemen Reservasi & Tiket Antrean Layanan Cerdas Berbasis Waktu**  
> Didesain dengan estetika **Soft Neo-Brutalism (Botanical Matcha & Honey Palette)**, terproteksi sistem penguncian jadwal anti bentrok (*anti double-booking*), serta dilengkapi model 3D interaktif.

---

## 1. Ikhtisar Proyek

**AntreanKu** adalah platform otomasi operasional dan reservasi antrean digital untuk bisnis berbasis jasa. Pada edisi *Official Business Template* ini, AntreanKu diimplementasikan secara penuh untuk **PAWSH! Pet Care & Grooming Studio**, mencakup alur pelanggan dari reservasi daring hingga pencatatan kasir dan pembukuan kuitansi pemilik usaha.

---

## 2. Fitur Utama

- **Pemesanan Jadwal Cerdas 3 Tahap**: Alur wizard interaktif (Layanan & Staf &rarr; Tanggal & Slot Jam &rarr; Data Anabul) dengan rekap estimasi biaya transparan.
- **Proteksi Anti Double-Booking (100% Presisi)**: Slot jam yang telah dipesan terkunci otomatis secara *real-time* per staf groomer, mencegah tumpang tindih antrean.
- **Workstation Meja Kasir Terpadu**: Sinkronisasi dinamis (5 detik) untuk memanggil antrean (*layani*), input pelanggan *walk-in* langsung di kasir, mencatat kehadiran (*lewati*), serta pelunasan transaksi.
- **Portal Manajemen Pemilik Usaha (Owner)**: Dashboard analitik omzet lunas, ringkasan reservasi, serta CRUD katalog layanan dan staf groomer secara mandiri.
- **E-Tiket Digital & Cetak Fisik**: Kode pelacakan unik (`TRK-XXX`), estimasi sisa antrean di depan, dan fitur cetak/simpan PDF langsung dari browser.
- **Konsultan AI Anabul Cerdas (Google Gemini AI Engine)**: Fitur konsultasi kondisi fisik/bulu anabul dengan pemrosesan bahasa alami yang otomatis merekomendasikan paket layanan dan groomer spesialis dengan tombol 1-klik booking langsung.
- **Kustomisasi Profil Pengguna**: Manajemen nama, kontak WhatsApp, kata sandi, dan foto profil dengan fitur pemotong/penyesuai ukuran (*cropper*) langsung di browser.
- **Autofill Demo 1-Klik**: Tombol pengisian cepat akun pengujian (*Owner*, *Kasir*, *Pelanggan*) di halaman login untuk kemudahan evaluasi/demo ujian.
- **Model 3D Interaktif**: Integrasi Google Model-Viewer untuk maskot anabul 3D yang dapat disentuh, diputar, dan dizoom langsung di halaman beranda.
- **Halaman Galat Kustom (404, 403, 500)**: Seluruh rute yang salah atau tidak terotorisasi diarahkan ke tampilan ramah bermotif neo-brutalism.
- **Kebijakan Nol Emotikon**: Menggunakan 100% ikon vektor SVG (Lucide) yang bersih dan profesional.

---

## 3. Tumpukan Teknologi (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 12 (PHP 8.5) |
| **AI Integration** | Google Gemini API (`gemini-3.8-flash`) + Smart Heuristic Engine |
| **Reaktivitas UI** | Livewire 3/4 Single File Components + Alpine.js |
| **CSS & Styling** | Tailwind CSS v4 &bull; Soft Neo-Brutalism Theme |
| **Ikonografi** | Lucide Icons (Blade Components) |
| **3D Rendering** | Google `<model-viewer>` |
| **Basis Data** | SQLite (Default, mendukung MySQL & PostgreSQL) |
| **Pengujian** | PHPUnit 12 (20 Feature Tests, 82 Assertions) |

---

## 4. Panduan Instalasi & Menjalankan Aplikasi

Jalankan langkah-langkah berikut di terminal:

### Langkah 1: Kloning Repositori & Instal Dependensi
```bash
composer install
npm install
```

### Langkah 2: Konfigurasi Berkas Lingkungan (.env)
```bash
cp .env.example .env
php artisan key:generate
```

### Langkah 3: Migrasi Basis Data & Data Awal (Seeder)
```bash
php artisan migrate:fresh --seed
```

### Langkah 4: Kompilasi Aset Frontend
```bash
npm run build
```

### Langkah 5: Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser pada alamat: **`http://localhost:8000`**

---

## 5. Kredensial Akun Demo Pengujian

Aplikasi telah dilengkapi seeder akun untuk seluruh peran pengguna. Anda dapat menggunakan tombol **Autofill 1-Klik** di halaman Login atau memasukkan kredensial berikut:

| Peran | Login (Email / No. HP) | Kata Sandi | Akses Utama |
| :--- | :--- | :--- | :--- |
| **Owner (Pemilik Usaha)** | `owner@pawsh.test` atau `081100000001` | `password` | `/app/owner` (Panel Owner) & Meja Kasir |
| **Staf Groomer & Kasir** | `sari@pawsh.test` atau `081100000002` | `password` | `/app/workstation` (Meja Kasir & Pelayanan) |
| **Pelanggan Terdaftar** | `081234567890` atau `budi@gmail.test` | `password` | `/app/customer` (Dashboard Tiket Saya) & Booking |

---

## 6. Pengujian Otomatis (Automated Testing)

Untuk menjalankan seluruh rangkaian uji fitur (19 pengujian, 77 asersi):

```bash
vendor/bin/phpunit
```

Untuk memformat kode sesuai standar PSR / Laravel Pint:
```bash
vendor/bin/pint --format agent
```

---

## 7. Lisensi & Hak Cipta

Dikembangkan untuk proyek tugas akhir semester implementasi arsitektur **AntreanKu Platform**.
Hak Cipta &copy; 2026 AntreanKu Platform &bull; PAWSH! Pet Care & Grooming Studio.
