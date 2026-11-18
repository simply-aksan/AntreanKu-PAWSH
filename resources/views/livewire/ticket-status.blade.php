<div wire:poll.5s class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
    <!-- Header Notice -->
    <div class="text-center space-y-2 mb-8">
        <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#A7D7C5] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
            E-Tiket Digital Aktif
        </span>
        <h1 class="font-display text-3xl font-black text-black tracking-tight">
            Status Antrean Anda
        </h1>
        <p class="text-xs font-semibold text-stone-600">
            Halaman ini diperbarui otomatis setiap 5 detik secara langsung dari sistem kasir.
        </p>
    </div>

    <!-- Ticket Neo-Brutalism Card -->
    <div class="border-3 border-black rounded-3xl bg-white shadow-[8px_8px_0px_0px_#121212] overflow-hidden relative">
        <!-- Ticket Top Band (Vivid Matcha & Honey Butter) -->
        <div class="p-6 sm:p-8 bg-[#2D6A4F] border-b-3 border-black flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-white relative">
            <div class="space-y-1 relative z-10">
                <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#FAF0CA]">
                    <x-lucide-paw-print class="w-3.5 h-3.5 text-[#FFBA08]" />
                    <span>Nomor Antrean PAWSH!</span>
                </div>
                <span class="font-display font-black text-4xl sm:text-5xl text-[#FFBA08] tracking-tight block">
                    #{{ $appointment->ticket_number }}
                </span>
                <span class="text-[11px] font-mono font-bold text-[#D8F3DC] block">
                    Kode Lacak: {{ $appointment->tracking_code }}
                </span>
            </div>

            <!-- Status Pill -->
            <div class="flex flex-col items-start sm:items-end gap-2 relative z-10">
                <span class="px-4 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] {{ $appointment->status_badge_class }}">
                    {{ $appointment->status_label }}
                </span>
                @if($appointment->status === 'pending')
                    <span class="text-[11px] font-bold text-[#FAF0CA]">
                        {{ $queuesAhead > 0 ? $queuesAhead . ' antrean di depan Anda' : 'Anda adalah antrean berikutnya!' }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Ticket Body Details -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pb-6 border-b-2 border-stone-200">
                <!-- Info Anabul & Pemilik -->
                <div class="space-y-3">
                    <h3 class="font-display font-black text-sm uppercase tracking-wider text-black flex items-center gap-1.5">
                        <x-lucide-user class="w-4 h-4 text-[#2D6A4F]" />
                        <span>Identitas Pemesan</span>
                    </h3>
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Nama Pemilik:</span>
                            <strong class="text-black font-bold">{{ $appointment->customer_name }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Nomor WhatsApp:</span>
                            <strong class="text-black font-mono font-bold">{{ $appointment->customer_phone }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Nama Anabul:</span>
                            <strong class="text-black font-bold">{{ $appointment->pet_name }} ({{ $appointment->pet_type }})</strong>
                        </div>
                    </div>
                </div>

                <!-- Info Jadwal & Layanan -->
                <div class="space-y-3">
                    <h3 class="font-display font-black text-sm uppercase tracking-wider text-black flex items-center gap-1.5">
                        <x-lucide-scissors class="w-4 h-4 text-[#2D6A4F]" />
                        <span>Detail Pelayanan</span>
                    </h3>
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Layanan:</span>
                            <strong class="text-black font-bold">{{ $appointment->service->name }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Groomer Bertugas:</span>
                            <strong class="text-black font-bold">{{ $appointment->staff->nickname }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Jadwal Kedatangan:</span>
                            <strong class="text-black font-bold">{{ $appointment->booking_date->format('d M Y') }} | {{ $appointment->time_slot }} WIB</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-stone-600">Tarif Layanan:</span>
                            <strong class="text-black font-display font-black text-sm">Rp {{ number_format($appointment->service->price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if ($appointment->notes)
                <div class="p-3.5 border-2 border-black rounded-xl bg-[#FDFBF7] text-xs">
                    <span class="font-bold text-black uppercase tracking-wider block mb-1">Catatan Khusus:</span>
                    <p class="text-stone-700 font-medium">{{ $appointment->notes }}</p>
                </div>
            @endif

            <!-- Live Status Guidance Card -->
            <div class="p-4 rounded-xl border-2 border-black {{ $appointment->status === 'serving' ? 'bg-[#BEE1E6]' : ($appointment->status === 'completed' ? 'bg-[#A7D7C5]' : ($appointment->status === 'cancelled' ? 'bg-[#F28482]' : 'bg-[#FAF0CA]')) }} shadow-[2px_2px_0px_0px_#000] flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-black text-white flex items-center justify-center shrink-0">
                    @if($appointment->status === 'serving')
                        <x-lucide-scissors class="w-5 h-5 text-[#FFBA08]" />
                    @elseif($appointment->status === 'completed')
                        <x-lucide-check class="w-5 h-5 text-[#A7D7C5] stroke-[3]" />
                    @elseif($appointment->status === 'cancelled')
                        <x-lucide-x class="w-5 h-5 text-[#F28482]" />
                    @else
                        <x-lucide-clock class="w-5 h-5 text-[#FFBA08]" />
                    @endif
                </div>
                <div class="text-xs">
                    @if($appointment->status === 'pending')
                        <span class="font-black text-black block">Harap datang 10 menit sebelum jam {{ $appointment->time_slot }} WIB</span>
                        <p class="text-stone-700 font-medium mt-0.5">Tunjukkan kode tiket ini kepada staf kasir setibanya di studio.</p>
                    @elseif($appointment->status === 'serving')
                        <span class="font-black text-black block">Anabul sedang dalam pengerjaan grooming oleh {{ $appointment->staff->nickname }}</span>
                        <p class="text-stone-700 font-medium mt-0.5">Silakan bersantai di ruang tunggu atau hubungi kasir jika ada kebutuhan tambahan.</p>
                    @elseif($appointment->status === 'completed')
                        <span class="font-black text-black block">Grooming telah selesai! Anabul siap dijemput.</span>
                        <p class="text-stone-700 font-medium mt-0.5">Silakan lakukan penyelesaian pembayaran di meja kasir. Terima kasih!</p>
                    @elseif($appointment->status === 'cancelled')
                        <span class="font-black text-black block">Reservasi ini telah dibatalkan.</span>
                        <p class="text-stone-700 font-medium mt-0.5">Slot jam telah dikembalikan ke sistem dan dapat dipesan kembali oleh pelanggan lain.</p>
                    @endif
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="pt-4 flex flex-wrap items-center justify-between gap-3 print:hidden">
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl border-2 border-black bg-white text-black font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#FAF0CA]/40 flex items-center gap-2 active:translate-x-[1px] active:translate-y-[1px] transition-all">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        <span>Beranda</span>
                    </a>
                    <button 
                        type="button" 
                        onclick="window.print()"
                        class="px-4 py-2 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center gap-2 cursor-pointer"
                    >
                        <x-lucide-printer class="w-4 h-4" />
                        <span>Cetak E-Tiket</span>
                    </button>
                </div>

                @if ($appointment->status === 'pending')
                    <button 
                        type="button" 
                        wire:click="$set('showCancelModal', true)"
                        class="px-4 py-2 rounded-xl border-2 border-black bg-[#F28482] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] flex items-center gap-1.5 cursor-pointer"
                    >
                        <x-lucide-trash-2 class="w-4 h-4" />
                        <span>Batalkan Janji Temu</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Batal (Alpine.js & Livewire) -->
    @if ($showCancelModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showCancelModal', false)">
            <div class="border-3 border-black rounded-2xl bg-white max-w-md w-full p-6 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col space-y-4">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black">
                    <h3 class="font-display font-black text-lg text-black">Batalkan Reservasi?</h3>
                    <button wire:click="$set('showCancelModal', false)" class="text-black font-black text-xl cursor-pointer">&times;</button>
                </div>
                <p class="text-xs font-semibold text-stone-700 leading-relaxed">
                    Apakah Anda yakin ingin membatalkan tiket <strong>#{{ $appointment->ticket_number }}</strong> untuk anabul <strong>{{ $appointment->pet_name }}</strong>? Slot jam {{ $appointment->time_slot }} WIB akan otomatis terbuka kembali untuk pelanggan lain.
                </p>
                <div class="pt-3 border-t-2 border-black flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="$set('showCancelModal', false)"
                        class="px-4 py-2 rounded-xl border-2 border-black bg-white text-xs font-bold text-black uppercase cursor-pointer"
                    >
                        Tutup
                    </button>
                    <button 
                        type="button" 
                        wire:click="cancelAppointment"
                        class="px-5 py-2 rounded-xl border-2 border-black bg-[#F28482] text-xs font-black text-black uppercase shadow-[2px_2px_0px_0px_#000] cursor-pointer"
                    >
                        Ya, Batalkan Tiket
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
