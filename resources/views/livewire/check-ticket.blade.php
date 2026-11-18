<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
    <div class="text-center space-y-3 mb-8">
        <span class="inline-block px-3 py-1 rounded-md border-2 border-black bg-[#FFBA08] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
            Layanan Mandiri Pelanggan
        </span>
        <h1 class="font-display text-3xl font-black text-black tracking-tight">
            Cari & Periksa Status Tiket
        </h1>
        <p class="text-xs font-semibold text-stone-600 max-w-md mx-auto">
            Masukkan nomor WhatsApp atau kode tiket yang Anda gunakan saat melakukan reservasi.
        </p>
    </div>

    <!-- Search Form Card -->
    <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-8 shadow-[6px_6px_0px_0px_#121212] mb-8 relative">
        <form wire:submit.prevent="search" class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <input 
                        type="text" 
                        wire:model="query" 
                        placeholder="Ketik Nomor WA (cth: 081234567890) atau No Tiket (cth: PAW-01)"
                        class="w-full text-sm font-bold px-4 py-3 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                    >
                </div>
                <button 
                    type="submit" 
                    class="px-6 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center justify-center gap-2 cursor-pointer shrink-0 transition-all"
                >
                    <x-lucide-search class="w-4 h-4" />
                    <span>Temukan Tiket</span>
                </button>
            </div>
            @error('query')
                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
            @enderror
        </form>
    </div>

    <!-- Search Results -->
    @if ($hasSearched)
        <div class="space-y-4">
            <h3 class="font-display font-black text-base text-black px-1">
                Hasil Pencarian: ({{ $appointments->count() }} tiket ditemukan)
            </h3>

            @if ($appointments->isEmpty())
                <div class="border-2 border-black rounded-2xl bg-white p-8 text-center shadow-[4px_4px_0px_0px_#000] space-y-3">
                    <div class="w-12 h-12 rounded-xl border-2 border-black bg-[#F28482] mx-auto flex items-center justify-center">
                        <x-lucide-search-x class="w-6 h-6 text-black" />
                    </div>
                    <h4 class="font-display font-black text-base text-black">Tiket Tidak Ditemukan</h4>
                    <p class="text-xs font-semibold text-stone-600 max-w-sm mx-auto">
                        Tidak ada antrean yang cocok dengan pencarian "{{ $query }}". Pastikan nomor WhatsApp yang Anda masukkan sudah benar.
                    </p>
                    <a href="{{ route('booking') }}" class="inline-block px-4 py-2 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] mt-2">
                        Buat Reservasi Baru
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($appointments as $item)
                        <div class="border-2 border-black rounded-2xl bg-white p-5 shadow-[4px_4px_0px_0px_#000] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-display font-black text-xl text-black">#{{ $item->ticket_number }}</span>
                                    <span class="px-2 py-0.5 rounded-md border border-black text-[10px] font-black uppercase {{ $item->status_badge_class }}">
                                        {{ $item->status_label }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-stone-800">
                                    {{ $item->pet_name }} ({{ $item->pet_type }}) — Pemilik: {{ $item->customer_name }}
                                </p>
                                <span class="text-[11px] font-semibold text-stone-600 block">
                                    {{ $item->service->name }} &bull; {{ $item->booking_date->format('d M Y') }} jam {{ $item->time_slot }} WIB &bull; Groomer: {{ $item->staff->nickname }}
                                </span>
                            </div>

                            <a href="{{ route('ticket-status', ['tracking_code' => $item->tracking_code]) }}" class="px-4 py-2 rounded-xl border-2 border-black bg-[#A7D7C5] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] hover:translate-x-[-1px] hover:translate-y-[-1px] shrink-0 transition-all">
                                Buka E-Tiket
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
