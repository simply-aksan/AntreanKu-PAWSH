<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Title -->
    <div class="text-center space-y-3 mb-10">
        <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#FFBA08] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
            Formulir Reservasi Cerdas
        </span>
        <h1 class="font-display text-3xl sm:text-4xl font-black text-black tracking-tight">
            Ambil Tiket Antrean Anabul
        </h1>
        <p class="text-xs sm:text-sm font-semibold text-stone-600 max-w-lg mx-auto">
            Sistem anti-bentrok kami memastikan jadwal tidak saling tumpang tindih. Isi data di bawah dengan mudah.
        </p>
    </div>

    <!-- Stepper Indicator (Matcha & Honey Palette) -->
    <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-8">
        <button 
            type="button" 
            wire:click="goToStep(1)"
            class="border-2 border-black rounded-xl p-3 text-center transition-all cursor-pointer {{ $currentStep >= 1 ? 'bg-[#A7D7C5] text-black shadow-[3px_3px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]' : 'bg-white opacity-60' }}"
        >
            <span class="font-display font-black text-xs sm:text-sm block">01. Layanan & Staf</span>
        </button>
        <button 
            type="button" 
            wire:click="goToStep(2)"
            class="border-2 border-black rounded-xl p-3 text-center transition-all cursor-pointer {{ $currentStep >= 2 ? 'bg-[#FFBA08] text-black shadow-[3px_3px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]' : 'bg-white opacity-60' }}"
        >
            <span class="font-display font-black text-xs sm:text-sm block">02. Tanggal & Jam</span>
        </button>
        <button 
            type="button" 
            wire:click="goToStep(3)"
            class="border-2 border-black rounded-xl p-3 text-center transition-all cursor-pointer {{ $currentStep >= 3 ? 'bg-[#2D6A4F] text-[#FAF0CA] shadow-[3px_3px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]' : 'bg-white opacity-60' }}"
        >
            <span class="font-display font-black text-xs sm:text-sm block">03. Data Anabul</span>
        </button>
    </div>

    <!-- Form Container Card -->
    <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-10 shadow-[8px_8px_0px_0px_#121212] space-y-8 relative overflow-hidden">
        <!-- Floating Subtle Pet Stamp in Form -->
        <div class="absolute -top-3 -right-3 w-16 h-16 text-[#A7D7C5]/20 pointer-events-none rotate-12">
            <x-lucide-paw-print class="w-full h-full" />
        </div>
        
        <!-- ================= STEP 1: PILIH LAYANAN & GROOMER ================= -->
        @if ($currentStep === 1)
            <div class="space-y-8 relative z-10">
                <!-- Section 1A: Pilih Layanan -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b-2 border-black">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#2D6A4F]"></span>
                            <h2 class="font-display font-black text-xl text-black">Langkah 1: Pilih Perawatan</h2>
                        </div>
                        @error('service_id')
                            <span class="text-xs font-bold text-[#F28482]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($services as $service)
                            <button 
                                type="button" 
                                wire:click="selectService({{ $service->id }})"
                                class="text-left p-4 rounded-2xl border-2 border-black transition-all cursor-pointer {{ $service_id === $service->id ? 'bg-[#FAF0CA] shadow-[4px_4px_0px_0px_#000] translate-x-[-1px] translate-y-[-1px]' : 'bg-white hover:bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000]' }}"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="space-y-1">
                                        <h3 class="font-display font-black text-base text-black">{{ $service->name }}</h3>
                                        <p class="text-xs font-medium text-stone-700 line-clamp-2">{{ $service->description }}</p>
                                    </div>
                                    <div class="w-6 h-6 rounded-md border-2 border-black flex items-center justify-center shrink-0 ml-2 {{ $service_id === $service->id ? 'bg-[#2D6A4F] text-[#FFBA08]' : 'bg-white' }}">
                                        @if($service_id === $service->id)
                                            <x-lucide-check class="w-4 h-4 stroke-[3]" />
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 pt-2 border-t border-black/20 flex items-center justify-between text-xs font-black">
                                    <span class="px-2 py-0.5 rounded border border-black bg-white text-[10px] uppercase">
                                        {{ $service->duration_minutes }} Menit
                                    </span>
                                    <span class="font-display text-sm text-black">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Section 1B: Pilih Groomer -->
                <div class="space-y-4 pt-4 border-t-2 border-black">
                    <div class="flex items-center justify-between pb-2 border-b-2 border-black">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#FFBA08]"></span>
                            <h2 class="font-display font-black text-xl text-black">Pilih Groomer yang Bertugas</h2>
                        </div>
                        @error('staff_id')
                            <span class="text-xs font-bold text-[#F28482]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($staffList as $staff)
                            <button 
                                type="button" 
                                wire:click="selectStaff({{ $staff->id }})"
                                class="text-left p-4 rounded-2xl border-2 border-black transition-all cursor-pointer {{ $staff_id === $staff->id ? 'bg-[#FAF0CA] shadow-[4px_4px_0px_0px_#000] translate-x-[-1px] translate-y-[-1px]' : 'bg-white hover:bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000]' }}"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl border-2 border-black flex items-center justify-center font-display font-black text-sm text-black shadow-[2px_2px_0px_0px_#000] shrink-0 overflow-hidden" style="background-color: {{ $staff->badge_color }}">
                                        @if ($staff->avatar && file_exists(public_path($staff->avatar)))
                                            <img src="{{ asset($staff->avatar) }}" alt="{{ $staff->nickname }}" class="w-full h-full object-cover">
                                        @else
                                            {{ mb_substr(preg_replace('/^Kak\s+/i', '', $staff->nickname), 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-display font-black text-sm text-black truncate">{{ $staff->nickname }}</h4>
                                        <span class="text-[10px] font-bold text-stone-700 block truncate">{{ $staff->title }}</span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Step 1 Navigation -->
                <div class="pt-6 border-t-2 border-black flex justify-end">
                    <button 
                        type="button" 
                        wire:click="nextStep"
                        class="px-6 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[5px_5px_0px_0px_#000] flex items-center gap-2 cursor-pointer"
                    >
                        <span>Lanjut ke Tanggal & Jam</span>
                        <x-lucide-arrow-right class="w-4 h-4 stroke-[2.5]" />
                    </button>
                </div>
            </div>
        @endif

        <!-- ================= STEP 2: TANGGAL & SLOT JAM ================= -->
        @if ($currentStep === 2)
            <div class="space-y-6 relative z-10">
                <div class="flex items-center justify-between pb-2 border-b-2 border-black">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FFBA08]"></span>
                        <h2 class="font-display font-black text-xl text-black">Langkah 2: Tentukan Tanggal & Jam</h2>
                    </div>
                    <span class="text-xs font-bold text-stone-600">Groomer: <strong>{{ $selectedStaff?->nickname }}</strong></span>
                </div>

                <!-- Date Picker -->
                <div class="space-y-2 max-w-xs">
                    <label class="block font-bold uppercase tracking-wider text-black text-xs">Pilih Tanggal Reservasi</label>
                    <input 
                        type="date" 
                        wire:model.live="booking_date" 
                        min="{{ date('Y-m-d') }}"
                        class="w-full text-sm font-bold px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                    >
                    @error('booking_date')
                        <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Time Slot Grid -->
                <div class="space-y-3 pt-4 border-t-2 border-black">
                    <div class="flex items-center justify-between">
                        <label class="font-bold uppercase tracking-wider text-black text-xs">
                            Pilih Slot Jam (Interval 30 Menit)
                        </label>
                        <div class="flex items-center gap-3 text-[10px] font-bold">
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded border border-black bg-[#A7D7C5]"></span> Tersedia
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded border border-stone-400 bg-stone-200"></span> Penuh/Booked
                            </span>
                        </div>
                    </div>

                    @error('time_slot')
                        <div class="p-3 border-2 border-black rounded-xl bg-[#F28482] text-xs font-bold text-black shadow-[2px_2px_0px_0px_#000]">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5">
                        @foreach ($this->timeSlots as $slot)
                            @if ($slot['is_available'])
                                <button 
                                    type="button" 
                                    wire:click="selectSlot('{{ $slot['time'] }}')"
                                    class="py-2.5 px-3 rounded-xl border-2 border-black font-display font-black text-xs text-center transition-all cursor-pointer {{ $time_slot === $slot['time'] ? 'bg-[#2D6A4F] text-[#FFBA08] shadow-[3px_3px_0px_0px_#000]' : 'bg-[#A7D7C5] text-black shadow-[2px_2px_0px_0px_#000] hover:bg-[#D8F3DC] hover:translate-x-[-1px] hover:translate-y-[-1px]' }}"
                                >
                                    {{ $slot['time'] }}
                                </button>
                            @else
                                <div class="py-2.5 px-3 rounded-xl border-2 border-stone-400 bg-stone-200 font-display font-bold text-xs text-stone-500 text-center cursor-not-allowed">
                                    <span class="line-through">{{ $slot['time'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Selected Slot Preview Summary (Warm Cream Honey) -->
                @if ($time_slot)
                    <div class="border-2 border-black rounded-xl p-4 bg-[#FAF0CA] shadow-[3px_3px_0px_0px_#000] flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#2D6A4F]">Jadwal yang Dipilih:</span>
                        <span class="font-display font-black text-base text-black">
                            {{ date('d M Y', strtotime($booking_date)) }} | Jam {{ $time_slot }} WIB
                        </span>
                    </div>
                @endif

                <!-- Step 2 Navigation -->
                <div class="pt-6 border-t-2 border-black flex items-center justify-between">
                    <button 
                        type="button" 
                        wire:click="prevStep"
                        class="px-5 py-2.5 rounded-xl border-2 border-black bg-white text-black font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#FAF0CA]/40 flex items-center gap-2 cursor-pointer"
                    >
                        <x-lucide-arrow-left class="w-4 h-4" />
                        <span>Kembali</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="nextStep"
                        class="px-6 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 cursor-pointer"
                    >
                        <span>Lanjut ke Data Anabul</span>
                        <x-lucide-arrow-right class="w-4 h-4 stroke-[2.5]" />
                    </button>
                </div>
            </div>
        @endif

        <!-- ================= STEP 3: DATA PEMILIK & ANABUL ================= -->
        @if ($currentStep === 3)
            <form wire:submit.prevent="submitBooking" class="space-y-6 relative z-10">
                <div class="pb-2 border-b-2 border-black">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2D6A4F]"></span>
                        <h2 class="font-display font-black text-xl text-black">Langkah 3: Identitas Pemilik & Anabul</h2>
                    </div>
                    <p class="text-xs font-semibold text-stone-600 mt-1">
                        Tiket digital dan notifikasi status antrean akan dikaitkan dengan nomor WhatsApp ini.
                    </p>
                </div>

                <!-- Recap Summary Card (Soft Sage & Honey) -->
                <div class="border-2 border-black rounded-2xl p-4 bg-[#FAF0CA] shadow-[3px_3px_0px_0px_#000] grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-[#2D6A4F] uppercase block">Layanan:</span>
                        <strong class="font-black text-black">{{ $selectedService?->name }}</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#2D6A4F] uppercase block">Tarif & Durasi:</span>
                        <strong class="font-black text-[#2D6A4F]">Rp {{ number_format($selectedService?->price ?? 0, 0, ',', '.') }} ({{ $selectedService?->duration_minutes ?? 30 }}m)</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#2D6A4F] uppercase block">Groomer:</span>
                        <strong class="font-black text-black">{{ $selectedStaff?->nickname }}</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#2D6A4F] uppercase block">Tanggal:</span>
                        <strong class="font-black text-black">{{ date('d M Y', strtotime($booking_date)) }}</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#2D6A4F] uppercase block">Jam Slot:</span>
                        <strong class="font-black text-black">{{ $time_slot }} WIB</strong>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Pemilik -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Nama Pemilik (Customer) <span class="text-[#F28482]">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="customer_name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                        >
                        @error('customer_name')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Nomor WhatsApp / HP <span class="text-[#F28482]">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="customer_phone"
                            placeholder="Contoh: 081234567890"
                            class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                        >
                        @error('customer_phone')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nama Anabul -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Nama Anabul (Pet) <span class="text-[#F28482]">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="pet_name"
                            placeholder="Contoh: Milo / Chiko / Si Oyen"
                            class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                        >
                        @error('pet_name')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Hewan -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Jenis Anabul <span class="text-[#F28482]">*</span>
                        </label>
                        <select 
                            wire:model="pet_type"
                            class="w-full text-sm font-bold px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                        >
                            <option value="Kucing">Kucing</option>
                            <option value="Anjing Kecil">Anjing Kecil</option>
                            <option value="Kelinci">Kelinci</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('pet_type')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Catatan Khusus -->
                <div class="space-y-1.5">
                    <label class="block font-bold uppercase tracking-wider text-black text-xs">
                        Catatan Khusus Groomer (Opsional)
                    </label>
                    <textarea 
                        wire:model="notes"
                        rows="3"
                        placeholder="Contoh: Ada sedikit jamur di telinga, anabul takut suara blower kencang, dsb."
                        class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-white shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                    ></textarea>
                </div>

                <!-- Info Pembayaran Di Tempat -->
                <div class="p-3.5 border-2 border-black rounded-xl bg-[#FDFBF7] flex items-center gap-3 text-xs font-bold text-stone-700">
                    <x-lucide-info class="w-5 h-5 text-[#2D6A4F] shrink-0" />
                    <span>Pembayaran dilakukan di kasir setelah pengerjaan grooming selesai (Tunai / QRIS / Transfer).</span>
                </div>

                <!-- Step 3 Navigation -->
                <div class="pt-6 border-t-2 border-black flex items-center justify-between">
                    <button 
                        type="button" 
                        wire:click="prevStep"
                        class="px-5 py-2.5 rounded-xl border-2 border-black bg-white text-black font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#FAF0CA]/40 flex items-center gap-2 cursor-pointer"
                    >
                        <x-lucide-arrow-left class="w-4 h-4" />
                        <span>Kembali</span>
                    </button>
                    <button 
                        type="submit" 
                        class="px-8 py-3.5 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] transition-all hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] flex items-center gap-2 cursor-pointer"
                    >
                        <x-lucide-check class="w-4 h-4 stroke-[3]" />
                        <span>Konfirmasi & Terbitkan Tiket</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
