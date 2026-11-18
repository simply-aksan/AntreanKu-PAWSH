<div wire:poll.5s class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 relative">
    <!-- Welcome Header Card (Pistachio Mint) -->
    <div class="border-3 border-black rounded-3xl bg-[#A7D7C5] p-6 sm:p-8 shadow-[6px_6px_0px_0px_#121212] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 w-28 h-28 text-black/5 pointer-events-none rotate-12">
            <x-lucide-dog class="w-full h-full" />
        </div>

        <div class="flex items-center gap-4 relative z-10">
            <!-- User Avatar Monogram or Image -->
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border-3 border-black bg-white shadow-[3px_3px_0px_0px_#000] flex items-center justify-center shrink-0 overflow-hidden font-display font-black text-xl text-black">
                @if ($user->avatar && file_exists(public_path($user->avatar)))
                    <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>

            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md border-2 border-black bg-white text-[10px] font-black uppercase tracking-wider text-black">
                    <x-lucide-user class="w-3 h-3 text-[#2D6A4F]" />
                    <span>Pelanggan Terdaftar AntreanKu</span>
                </div>
                <h1 class="font-display font-black text-2xl sm:text-3xl text-black tracking-tight">
                    Halo, {{ $user->name }}!
                </h1>
                <p class="text-xs font-semibold text-stone-800">
                    Nomor WhatsApp: <span class="font-mono font-bold text-black">{{ $user->phone }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 relative z-10 w-full sm:w-auto">
            <a href="{{ route('profile') }}" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center justify-center gap-2">
                <x-lucide-settings class="w-3.5 h-3.5" />
                <span>Profil</span>
            </a>
            <a href="{{ route('booking') }}" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] hover:bg-[#2D6A4F] hover:text-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center justify-center gap-2">
                <x-lucide-calendar-plus class="w-3.5 h-3.5 stroke-[2.5]" />
                <span>Pesan Tiket</span>
            </a>
        </div>
    </div>

    <!-- Fitur Cari & Cek Tiket Pelanggan -->
    <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-7 shadow-[6px_6px_0px_0px_#121212] space-y-5 relative">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b-2 border-black">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-md border border-black bg-[#FAF0CA] text-[10px] font-black uppercase tracking-wider text-[#2D6A4F]">
                    Layanan Mandiri
                </span>
                <h2 class="font-display font-black text-lg sm:text-xl text-black mt-1 flex items-center gap-2">
                    <x-lucide-search class="w-5 h-5 text-[#2D6A4F]" />
                    <span>Cari & Cek Status Tiket Anda</span>
                </h2>
            </div>
            <span class="text-xs font-bold text-stone-500">
                Cari tiket aktif atau riwayat booking
            </span>
        </div>

        <form wire:submit.prevent="searchTicket" class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <input 
                        type="text" 
                        wire:model="searchQuery" 
                        placeholder="Ketik Nomor Tiket (cth: PAW-01), Kode Lacak (cth: TRK-XXX), atau Nama Anabul..."
                        class="w-full text-sm font-bold px-4 py-3 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                    >
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button 
                        type="submit" 
                        class="px-5 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center justify-center gap-2 cursor-pointer transition-all"
                    >
                        <x-lucide-search class="w-4 h-4 stroke-[2.5]" />
                        <span>Cari Tiket</span>
                    </button>
                    @if ($hasSearched)
                        <button 
                            type="button" 
                            wire:click="resetSearch"
                            class="px-4 py-3 rounded-xl border-2 border-black bg-white hover:bg-stone-100 text-stone-800 font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] cursor-pointer"
                        >
                            Reset
                        </button>
                    @endif
                </div>
            </div>
            @error('searchQuery')
                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
            @enderror
        </form>

        <!-- Hasil Pencarian Tiket Pelanggan -->
        @if ($hasSearched)
            <div class="pt-2 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-display font-black text-sm text-black uppercase tracking-wider">
                        Hasil Pencarian Tiket ({{ $searchResults->count() }} ditemukan)
                    </h3>
                </div>

                @if ($searchResults->isEmpty())
                    <div class="border-2 border-black rounded-2xl bg-[#FDFBF7] p-6 text-center shadow-[3px_3px_0px_0px_#000] space-y-2">
                        <div class="w-10 h-10 rounded-xl border-2 border-black bg-[#FAF0CA] mx-auto flex items-center justify-center">
                            <x-lucide-search-x class="w-5 h-5 text-black" />
                        </div>
                        <h4 class="font-display font-black text-sm text-black">Tiket Tidak Ditemukan</h4>
                        <p class="text-xs font-medium text-stone-600 max-w-sm mx-auto">
                            Tidak ditemukan tiket dengan kata kunci "{{ $searchQuery }}". Pastikan nomor tiket atau kode lacak sudah sesuai.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($searchResults as $item)
                            <div class="border-2 border-black rounded-2xl bg-[#FAF0CA] p-4 shadow-[3px_3px_0px_0px_#000] flex flex-col justify-between space-y-3">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="font-display font-black text-xl text-black">#{{ $item->ticket_number }}</span>
                                        <span class="px-2.5 py-0.5 rounded-lg border-2 border-black text-[10px] font-black uppercase {{ $item->status_badge_class }} shadow-[1px_1px_0px_0px_#000]">
                                            {{ $item->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-black mt-1">
                                        {{ $item->pet_name }} ({{ $item->pet_type }})
                                    </p>
                                    <span class="text-[11px] font-semibold text-stone-700 block mt-0.5">
                                        {{ $item->service->name }} &bull; {{ $item->booking_date->format('d M Y') }} ({{ $item->time_slot }} WIB)
                                    </span>
                                    <span class="text-[10px] font-mono text-stone-500 block">
                                        Ref: {{ $item->tracking_code }}
                                    </span>
                                </div>

                                <div class="pt-2 border-t border-black/20 flex items-center justify-between">
                                    <span class="font-display font-black text-xs text-black">
                                        Rp {{ number_format($item->service->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('ticket-status', ['tracking_code' => $item->tracking_code]) }}" class="px-3.5 py-1.5 rounded-xl border-2 border-black bg-white hover:bg-[#2D6A4F] hover:text-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] transition-all">
                                        Buka E-Tiket
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Active Tickets Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between pb-2 border-b-2 border-black">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#2D6A4F]"></span>
                <h2 class="font-display font-black text-xl text-black">
                    Antrean & Reservasi Aktif ({{ $activeAppointments->count() }})
                </h2>
            </div>
            <span class="text-xs font-bold text-stone-600">Pembaruan Langsung</span>
        </div>

        @if ($activeAppointments->isEmpty())
            <div class="border-2 border-black rounded-2xl bg-white p-8 text-center shadow-[4px_4px_0px_0px_#000] space-y-3">
                <div class="w-12 h-12 rounded-xl border-2 border-black bg-[#FAF0CA] mx-auto flex items-center justify-center">
                    <x-lucide-ticket class="w-6 h-6 text-[#2D6A4F]" />
                </div>
                <h3 class="font-display font-black text-base text-black">Tidak Ada Antrean Aktif Saat Ini</h3>
                <p class="text-xs font-semibold text-stone-600 max-w-sm mx-auto">
                    Anda belum memiliki reservasi antrean yang sedang berjalan hari ini.
                </p>
                <a href="{{ route('booking') }}" class="inline-block px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] mt-2">
                    Reservasi Jadwal Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($activeAppointments as $item)
                    <div class="border-3 border-black rounded-2xl p-5 shadow-[4px_4px_0px_0px_#000] space-y-4 flex flex-col justify-between transition-all {{ $item->status === 'serving' ? 'bg-[#BEE1E6]/30 border-l-8 border-l-[#2D6A4F] ring-2 ring-black/10' : 'bg-white' }}">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                @if ($item->status === 'serving')
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-3 w-3 relative shrink-0">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#2D6A4F] opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-[#2D6A4F]"></span>
                                        </span>
                                        <span class="font-display font-black text-2xl text-black">#{{ $item->ticket_number }}</span>
                                    </div>
                                @else
                                    <span class="font-display font-black text-2xl text-black">#{{ $item->ticket_number }}</span>
                                @endif

                                <span class="px-3 py-1 rounded-xl border-2 border-black text-xs font-black uppercase {{ $item->status_badge_class }} shadow-[2px_2px_0px_0px_#000]">
                                    {{ $item->status_label }}
                                </span>
                            </div>

                            <div class="p-3 rounded-xl border-2 border-black bg-[#FDFBF7] space-y-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-600">Anabul:</span>
                                    <strong class="text-black font-bold">{{ $item->pet_name }} ({{ $item->pet_type }})</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-600">Layanan:</span>
                                    <strong class="text-black font-bold">{{ $item->service->name }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-600">Groomer:</span>
                                    <strong class="text-black font-bold">{{ $item->staff->nickname }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-stone-600">Jadwal:</span>
                                    <strong class="text-black font-bold">{{ $item->booking_date->format('d M Y') }} | {{ $item->time_slot }} WIB</strong>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 border-t-2 border-black flex items-center justify-between gap-2">
                            <a href="{{ route('ticket-status', ['tracking_code' => $item->tracking_code]) }}" class="px-4 py-2 rounded-xl border-2 border-black bg-[#BEE1E6] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] transition-all">
                                Buka E-Tiket
                            </a>

                            @if ($item->status === 'pending')
                                <button 
                                    type="button" 
                                    wire:click="cancelTicket({{ $item->id }})"
                                    class="px-3 py-2 rounded-xl border-2 border-black bg-white text-[#F28482] hover:bg-red-50 font-bold text-xs uppercase cursor-pointer active:translate-x-[1px] active:translate-y-[1px] transition-all"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan tiket ini?')"
                                >
                                    Batalkan
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Past Booking History -->
    <div class="space-y-4 pt-6">
        <div class="pb-2 border-b-2 border-black">
            <h2 class="font-display font-black text-xl text-black">
                Riwayat Kunjungan Lampau
            </h2>
        </div>

        <div class="border-2 border-black rounded-2xl bg-white shadow-[4px_4px_0px_0px_#000] overflow-hidden">
            @if ($pastAppointments->isEmpty())
                <div class="p-8 text-center text-xs font-semibold text-stone-500">
                    Belum ada riwayat kunjungan lampau.
                </div>
            @else
                <div class="divide-y border-stone-200">
                    @foreach ($pastAppointments as $past)
                        <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <strong class="font-display font-black text-sm text-black">#{{ $past->ticket_number }}</strong>
                                    <span class="px-2 py-0.5 rounded border border-black text-[9px] font-black uppercase {{ $past->status_badge_class }}">
                                        {{ $past->status_label }}
                                    </span>
                                    <span class="text-stone-500 font-medium">{{ $past->booking_date->format('d/m/Y') }}</span>
                                </div>
                                <span class="font-semibold text-stone-700 block">
                                    {{ $past->pet_name }} &bull; {{ $past->service->name }} &bull; Groomer: {{ $past->staff->nickname }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="font-display font-black text-sm text-black">
                                    Rp {{ number_format($past->service->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('ticket-status', ['tracking_code' => $past->tracking_code]) }}" class="px-3 py-1.5 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] font-display font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
