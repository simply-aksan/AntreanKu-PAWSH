<x-layouts.app>
    <!-- Hero Section -->
    <section id="beranda" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-16 relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Left Column: Copywriting & CTA -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border-2 border-black bg-[#FAF0CA] shadow-[2px_2px_0px_0px_#121212]">
                    <x-lucide-paw-print class="w-4 h-4 text-[#2D6A4F] stroke-[2.5]" />
                    <span class="font-display font-black text-xs uppercase tracking-wider text-black">AntreanKu Template &bull; PAWSH! Pet Studio</span>
                </div>

                <h1 class="font-display text-4xl sm:text-6xl font-black text-black leading-[1.08] tracking-tight">
                    Grooming Nyaman, <br class="hidden sm:inline">
                    <span class="inline-block bg-[#FFBA08] px-3.5 py-1 border-3 border-black rounded-xl shadow-[4px_4px_0px_0px_#121212] rotate-[-1deg] mt-1">
                        Tanpa Antre Lama.
                    </span>
                </h1>

                <p class="text-base sm:text-lg font-medium text-stone-700 leading-relaxed max-w-xl">
                    Pilih kapster grooming favoritmu, booking slot jam pengerjaan secara online, dan nikmati sistem anti double-booking yang 100% presisi. Pantau status antrean langsung dari HP Anda.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('booking') }}" class="px-7 py-4 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-sm uppercase tracking-wider shadow-[4px_4px_0px_0px_#121212] transition-all hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_0px_#121212] active:translate-x-[2px] active:translate-y-[2px] active:shadow-[1px_1px_0px_0px_#121212] flex items-center gap-2.5">
                        <x-lucide-calendar-plus class="w-5 h-5 stroke-[2.5]" />
                        <span>Pesan Slot Sekarang</span>
                    </a>
                </div>

                <!-- Features Pill Badges (Matcha & Honey Palette) -->
                <div class="grid grid-cols-3 gap-2.5 sm:gap-3 pt-4 max-w-lg">
                    <div class="border-2 border-black rounded-xl p-2.5 sm:p-3 bg-[#D8F3DC] shadow-[2px_2px_0px_0px_#121212] text-center">
                        <span class="font-display font-black text-lg sm:text-xl text-[#2D6A4F] block">30 Mnt</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-black uppercase tracking-wider block">Slot Terukur</span>
                    </div>
                    <div class="border-2 border-black rounded-xl p-2.5 sm:p-3 bg-[#BEE1E6] shadow-[2px_2px_0px_0px_#121212] text-center">
                        <span class="font-display font-black text-lg sm:text-xl text-black block">100%</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-black uppercase tracking-wider block">Anti Bentrok</span>
                    </div>
                    <div class="border-2 border-black rounded-xl p-2.5 sm:p-3 bg-[#FAF0CA] shadow-[2px_2px_0px_0px_#121212] text-center">
                        <span class="font-display font-black text-lg sm:text-xl text-[#2D6A4F] block">Live</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-black uppercase tracking-wider block">Tracking Tiket</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive 3D Model Card -->
            <div class="lg:col-span-5 relative">
                <div class="border-3 border-black rounded-2xl bg-white p-5 shadow-[8px_8px_0px_0px_#121212] space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b-2 border-black">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#F28482] border border-black"></span>
                            <span class="w-3 h-3 rounded-full bg-[#FFBA08] border border-black"></span>
                            <span class="w-3 h-3 rounded-full bg-[#A7D7C5] border border-black"></span>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-black bg-[#A7D7C5]">
                            Model 3D Interaktif (Putar & Zoom)
                        </span>
                    </div>

                    <!-- 3D Model Canvas -->
                    <div class="relative bg-[#FDFBF7] border-2 border-black rounded-xl overflow-hidden">
                        <model-viewer 
                            src="/assets/models/corgi.glb" 
                            alt="3D Corgi Pet Model" 
                            auto-rotate 
                            camera-controls 
                            rotation-per-second="30deg"
                            interaction-prompt="auto"
                            shadow-intensity="1.2"
                            class="w-full h-72 sm:h-80"
                        >
                        </model-viewer>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                            <span class="px-2.5 py-1 rounded-md border border-black bg-white text-[10px] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-1">
                                <x-lucide-dog class="w-3 h-3 text-[#2D6A4F]" />
                                <span>Corgi Mascot 3D</span>
                            </span>
                            <span class="px-2 py-0.5 rounded border border-black bg-[#FFBA08] text-[9px] font-bold text-black shadow-[1px_1px_0px_0px_#000]">
                                Sentuh & Putar Model
                            </span>
                        </div>
                    </div>

                    <!-- Live Queue Today Teaser (Botanical Palette) -->
                    <div class="border-2 border-black rounded-xl p-3.5 bg-[#FAF0CA] shadow-[2px_2px_0px_0px_#121212] flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#2D6A4F] block">Status Antrean Hari Ini:</span>
                            <span class="font-display font-black text-sm text-black">
                                {{ $servingAppointment ? 'Sedang Dilayani: #' . $servingAppointment->ticket_number . ' (' . $servingAppointment->pet_name . ')' : 'Semua Antrean Lancar' }}
                            </span>
                        </div>
                        @auth
                            @if(in_array(auth()->user()->role, ['owner', 'staff']))
                                <a href="{{ route('workstation') }}" class="px-3 py-1.5 rounded-lg border-2 border-black bg-white text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px]">
                                    Buka Kasir
                                </a>
                            @else
                                <a href="{{ route('booking') }}" class="px-3 py-1.5 rounded-lg border-2 border-black bg-[#FFBA08] text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px]">
                                    Ambil Slot
                                </a>
                            @endif
                        @else
                            <a href="{{ route('booking') }}" class="px-3 py-1.5 rounded-lg border-2 border-black bg-[#FFBA08] text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px]">
                                Ambil Slot
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Catalog Section -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t-3 border-black relative">
        <div class="text-center max-w-2xl mx-auto space-y-3 mb-12">
            <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#FFBA08] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
                Menu & Tarif Resmi
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-black text-black tracking-tight">
                Pilih Perawatan Terbaik untuk Anabulmu
            </h2>
            <p class="text-sm font-semibold text-stone-600">
                Semua layanan dihitung dalam satuan slot waktu 30 menit dengan pengerjaan higienis dan peralatan tersterilisasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($services as $service)
                <div class="border-3 border-black rounded-2xl bg-white p-6 shadow-[5px_5px_0px_0px_#121212] flex flex-col justify-between space-y-5 transition-transform hover:translate-x-[-2px] hover:translate-y-[-2px]">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-xl border-2 border-black flex items-center justify-center shadow-[2px_2px_0px_0px_#000]" style="background-color: {{ $service->badge_color }}">
                                @if($service->icon === 'bath')
                                    <x-lucide-bath class="w-6 h-6 text-black stroke-[2.5]" />
                                @elseif($service->icon === 'scissors')
                                    <x-lucide-scissors class="w-6 h-6 text-black stroke-[2.5]" />
                                @elseif($service->icon === 'sparkles')
                                    <x-lucide-sparkles class="w-6 h-6 text-black stroke-[2.5]" />
                                @else
                                    <x-lucide-dog class="w-6 h-6 text-black stroke-[2.5]" />
                                @endif
                            </div>
                            <span class="px-2.5 py-1 rounded-lg border-2 border-black text-[11px] font-black uppercase tracking-wider bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000]">
                                {{ $service->duration_minutes }} Menit
                            </span>
                        </div>

                        <div>
                            <h3 class="font-display font-black text-lg text-black leading-snug">{{ $service->name }}</h3>
                            <p class="text-xs font-semibold text-stone-600 mt-2 leading-relaxed">
                                {{ $service->description }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t-2 border-black space-y-3">
                        <div class="flex items-baseline justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500">Tarif Layanan:</span>
                            <span class="font-display font-black text-xl text-black">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('booking', ['service' => $service->id]) }}" class="w-full py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] text-center block transition-all hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[4px_4px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none">
                            Pilih Layanan Ini
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Groomer Specialists Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t-3 border-black">
        <div class="text-center max-w-2xl mx-auto space-y-3 mb-12">
            <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#A7D7C5] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
                Tim Profesional
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-black text-black tracking-tight">
                Kenalan dengan Staf Groomer Kami
            </h2>
            <p class="text-sm font-semibold text-stone-600">
                Pilih groomer favoritmu saat memesan jadwal tanpa biaya tambahan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($staffProfiles as $staff)
                <div class="border-3 border-black rounded-2xl bg-white p-6 shadow-[5px_5px_0px_0px_#121212] space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl border-2 border-black flex items-center justify-center shadow-[3px_3px_0px_0px_#000] font-display font-black text-xl text-black shrink-0 overflow-hidden" style="background-color: {{ $staff->badge_color }}">
                            @if ($staff->avatar_url)
                                <img src="{{ $staff->avatar_url }}" alt="{{ $staff->nickname }}" class="w-full h-full object-cover">
                            @else
                                {{ mb_substr(preg_replace('/^Kak\s+/i', '', $staff->nickname), 0, 1) }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-display font-black text-lg text-black truncate">{{ $staff->nickname }}</h4>
                            <span class="text-[11px] font-bold text-stone-700 block truncate">{{ $staff->title }}</span>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-stone-600 leading-relaxed pt-2 border-t-2 border-stone-200">
                        {{ $staff->bio }}
                    </p>
                    <div class="pt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border border-black bg-[#D8F3DC] text-[10px] font-black uppercase tracking-wider text-black">
                            <span class="w-2 h-2 rounded-full bg-[#2D6A4F]"></span>
                            <span>Siap Melayani Hari Ini</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- How It Works Section (Matcha & Honey Neo-Brutalist Showcase) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t-3 border-black">
        <div class="border-3 border-black rounded-3xl bg-[#2D6A4F] p-8 sm:p-12 shadow-[8px_8px_0px_0px_#121212] space-y-8 relative overflow-hidden">
            <!-- Decorative Background Pet Icons inside showcase -->
            <div class="absolute -right-6 -bottom-6 w-40 h-40 text-white/5 pointer-events-none rotate-12">
                <x-lucide-paw-print class="w-full h-full" />
            </div>

            <div class="text-center max-w-xl mx-auto space-y-2 relative z-10">
                <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#FFBA08] text-black font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
                    Alur Reservasi Mudah
                </span>
                <h3 class="font-display text-3xl font-black text-[#FDFBF7]">3 Langkah Praktis Ambil Antrean</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <div class="border-2 border-black rounded-2xl bg-[#FDFBF7] p-6 shadow-[4px_4px_0px_0px_#000] space-y-3">
                    <div class="w-9 h-9 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-sm flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                        01
                    </div>
                    <h4 class="font-display font-black text-base text-black">Pilih Layanan & Groomer</h4>
                    <p class="text-xs font-semibold text-stone-600 leading-relaxed">
                        Tentukan jenis perawatan untuk anabulmu dan pilih groomer yang Anda percaya.
                    </p>
                </div>

                <div class="border-2 border-black rounded-2xl bg-[#FDFBF7] p-6 shadow-[4px_4px_0px_0px_#000] space-y-3">
                    <div class="w-9 h-9 rounded-xl border-2 border-black bg-[#A7D7C5] text-black font-display font-black text-sm flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                        02
                    </div>
                    <h4 class="font-display font-black text-base text-black">Pilih Slot Jam Pintar</h4>
                    <p class="text-xs font-semibold text-stone-600 leading-relaxed">
                        Sistem otomatis memeriksa jadwal kosong. Jam yang sudah dipesan orang lain terkunci otomatis.
                    </p>
                </div>

                <div class="border-2 border-black rounded-2xl bg-[#FDFBF7] p-6 shadow-[4px_4px_0px_0px_#000] space-y-3">
                    <div class="w-9 h-9 rounded-xl border-2 border-black bg-[#BEE1E6] text-black font-display font-black text-sm flex items-center justify-center shadow-[2px_2px_0px_0px_#000]">
                        03
                    </div>
                    <h4 class="font-display font-black text-base text-black">Dapatkan E-Tiket Digital</h4>
                    <p class="text-xs font-semibold text-stone-600 leading-relaxed">
                        Simpan nomor tiket unik Anda. Pantau status antrean secara langsung dari perangkat Anda.
                    </p>
                </div>
            </div>

            <div class="text-center pt-4 relative z-10">
                <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-sm uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px]">
                    <x-lucide-calendar class="w-5 h-5 stroke-[2.5]" />
                    <span>Mulai Ambil Tiket Antrean</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
