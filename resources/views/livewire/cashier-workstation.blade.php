<div wire:poll.visible.10s class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 relative">
    <!-- Top Bar & Title -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b-3 border-black">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#FAF0CA] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] mb-2">
                <span class="w-2 h-2 rounded-full bg-[#2D6A4F] animate-pulse"></span>
                <span class="text-black">Workstation Operasional Staf & Kasir</span>
            </div>
            <h1 class="font-display text-3xl font-black text-black tracking-tight">
                Konsol Antrean Layanan & Kasir
            </h1>
            <p class="text-xs font-semibold text-stone-600">
                Kelola pemanggilan antrean online dan pelanggan walk-in secara langsung.
            </p>
        </div>

        <!-- Quick Walk-in Button -->
        <button 
            type="button" 
            wire:click="$set('showWalkInModal', true)"
            class="px-5 py-3 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] flex items-center gap-2 cursor-pointer shrink-0 transition-all"
        >
            <x-lucide-user-plus class="w-4 h-4 stroke-[2.5]" />
            <span>Tambah Walk-In</span>
        </button>
    </div>

    <!-- Metric Tiles Grid (Variasi B Botanical) -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <!-- Total Antrean -->
        <div class="border-2 border-black rounded-2xl bg-white p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-600 block">Total Antrean</span>
            <span class="font-display font-black text-2xl sm:text-3xl text-black">{{ $totalCount }}</span>
        </div>

        <!-- Sedang Dilayani -->
        <div class="border-2 border-black rounded-2xl bg-[#BEE1E6] p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-black block">Sedang Dilayani</span>
            <span class="font-display font-black text-2xl sm:text-3xl text-black">{{ $servingCount }}</span>
        </div>

        <!-- Menunggu -->
        <div class="border-2 border-black rounded-2xl bg-[#FFBA08] p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-black block">Menunggu Giliran</span>
            <span class="font-display font-black text-2xl sm:text-3xl text-black">{{ $pendingCount }}</span>
        </div>

        <!-- Selesai -->
        <div class="border-2 border-black rounded-2xl bg-[#A7D7C5] p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-black block">Selesai Hari Ini</span>
            <span class="font-display font-black text-2xl sm:text-3xl text-black">{{ $completedCount }}</span>
        </div>

        <!-- Omzet Kasir -->
        <div class="col-span-2 sm:col-span-1 border-2 border-black rounded-2xl bg-[#FAF0CA] p-4 shadow-[3px_3px_0px_0px_#000]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-[#2D6A4F] block">Kasir Terkumpul</span>
            <span class="font-display font-black text-lg sm:text-xl text-black truncate block">
                Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Filters Toolbar Card -->
    <div class="border-2 border-black rounded-2xl bg-white p-4 shadow-[4px_4px_0px_0px_#000] flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Tanggal -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold uppercase tracking-wider text-black">Tanggal:</label>
                <input 
                    type="date" 
                    wire:model.live="selectedDate"
                    class="text-xs font-bold px-3 py-1.5 rounded-lg border-2 border-black bg-[#FDFBF7]"
                >
            </div>

            <!-- Filter Groomer -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold uppercase tracking-wider text-black">Groomer:</label>
                <select 
                    wire:model.live="filterStaffId"
                    class="text-xs font-bold px-3 py-1.5 rounded-lg border-2 border-black bg-[#FDFBF7]"
                >
                    <option value="">Semua Groomer</option>
                    @foreach ($staffList as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->nickname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold text-stone-600">
            <span class="inline-block w-2 h-2 rounded-full bg-[#2D6A4F] animate-ping"></span>
            <span>Live Sync Aktif (5s)</span>
        </div>
    </div>

    <!-- Appointments Queue List Table -->
    <div class="border-3 border-black rounded-2xl bg-white shadow-[6px_6px_0px_0px_#000] overflow-hidden">
        <div class="p-4 bg-[#FDFBF7] border-b-2 border-black flex items-center justify-between">
            <h3 class="font-display font-black text-base text-black uppercase tracking-wider flex items-center gap-2">
                <x-lucide-clipboard-list class="w-4 h-4 text-[#2D6A4F]" />
                <span>Daftar Antrean & Jadwal Pelayanan</span>
            </h3>
            <span class="text-xs font-bold text-stone-600">{{ $appointments->count() }} antrean terdaftar</span>
        </div>

        @if ($appointments->isEmpty())
            <div class="p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-xl border-2 border-black bg-[#FAF0CA] mx-auto flex items-center justify-center">
                    <x-lucide-calendar-x class="w-6 h-6 text-black" />
                </div>
                <h4 class="font-display font-black text-lg text-black">Tidak Ada Antrean di Tanggal Ini</h4>
                <p class="text-xs font-semibold text-stone-600">
                    Belum ada reservasi masuk untuk tanggal {{ date('d M Y', strtotime($selectedDate)) }}.
                </p>
            </div>
        @else
            <div class="divide-y-2 border-stone-200">
                @foreach ($appointments as $item)
                    <div class="p-4 sm:p-5 flex flex-col lg:grid lg:grid-cols-12 lg:items-center lg:gap-4 gap-4 hover:bg-stone-50 transition-colors {{ $item->status === 'serving' ? 'bg-[#BEE1E6]/25 border-l-6 border-l-[#2D6A4F]' : '' }}">
                        <!-- Col 1: Time & Ticket -->
                        <div class="lg:col-span-3 flex items-center gap-3.5">
                            <div class="text-center p-2 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] shrink-0 min-w-[58px]">
                                <span class="text-[9px] font-bold text-[#2D6A4F] uppercase block">Jam</span>
                                <span class="font-display font-black text-sm sm:text-base text-black">{{ $item->time_slot }}</span>
                            </div>
                            <div class="min-w-0">
                                <span class="font-display font-black text-xl text-black block truncate">#{{ $item->ticket_number }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="px-2 py-0.5 rounded border border-black text-[9px] font-black uppercase {{ $item->type === 'walk_in' ? 'bg-[#FAF0CA] text-black' : 'bg-white text-stone-700' }}">
                                        {{ $item->type === 'walk_in' ? 'Walk-In' : 'Online' }}
                                    </span>
                                    <span class="text-[10px] font-mono text-stone-500 truncate">{{ $item->tracking_code }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Col 2: Customer & Pet -->
                        <div class="lg:col-span-3 space-y-1 min-w-0">
                            <span class="font-display font-black text-sm text-black block truncate">
                                {{ $item->pet_name }} <span class="text-xs font-bold text-stone-600">({{ $item->pet_type }})</span>
                            </span>
                            <div class="text-xs font-semibold text-stone-600 flex items-center gap-1.5 truncate">
                                <span class="truncate">Pemilik: <strong class="text-black">{{ $item->customer_name }}</strong></span>
                                <span>&bull;</span>
                                <span class="font-mono shrink-0">{{ $item->customer_phone }}</span>
                            </div>
                            @if($item->notes)
                                <p class="text-[11px] font-medium text-stone-500 italic line-clamp-1">
                                    "{{ $item->notes }}"
                                </p>
                            @endif
                        </div>

                        <!-- Col 3: Service & Groomer -->
                        <div class="lg:col-span-2 space-y-1 min-w-0">
                            <span class="font-bold text-xs text-black block truncate">{{ $item->service->name }}</span>
                            <span class="text-[11px] font-semibold text-stone-600 block truncate">
                                Groomer: <strong>{{ $item->staff->nickname }}</strong> &bull; Rp {{ number_format($item->service->price, 0, ',', '.') }}
                            </span>
                            @if($item->transaction)
                                <span class="inline-block px-2 py-0.5 rounded border border-black bg-[#A7D7C5] text-[9px] font-black uppercase shadow-[1px_1px_0px_0px_#000]">
                                    Lunas: {{ strtoupper($item->transaction->payment_method) }}
                                </span>
                            @endif
                        </div>

                        <!-- Col 4: Status Badge -->
                        <div class="lg:col-span-2">
                            <span class="inline-block px-3 py-1 rounded-xl border-2 border-black text-xs font-black uppercase {{ $item->status_badge_class }} shadow-[2px_2px_0px_0px_#000]">
                                {{ $item->status_label }}
                            </span>
                        </div>

                        <!-- Col 5: Action Buttons -->
                        <div class="lg:col-span-2 flex flex-wrap items-center lg:justify-end gap-1.5 shrink-0">
                            @if ($item->status === 'pending')
                                <button 
                                    type="button" 
                                    wire:click="startServing({{ $item->id }})"
                                    class="px-3 py-2 rounded-xl border-2 border-black bg-[#A7D7C5] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] flex items-center gap-1.5 cursor-pointer transition-all"
                                    title="Panggil dan mulai pengerjaan grooming"
                                >
                                    <x-lucide-play class="w-3.5 h-3.5 fill-current" />
                                    <span>Layani</span>
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="markNoShow({{ $item->id }})"
                                    class="px-2.5 py-2 rounded-xl border-2 border-black bg-stone-100 text-stone-700 font-bold text-xs uppercase tracking-wider hover:bg-stone-200 active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                    title="Tandai pelanggan tidak hadir"
                                >
                                    <span>Lewati</span>
                                </button>
                            @elseif ($item->status === 'serving')
                                <button 
                                    type="button" 
                                    wire:click="openPaymentModal({{ $item->id }})"
                                    class="px-3.5 py-2 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] flex items-center gap-1.5 cursor-pointer transition-all"
                                >
                                    <x-lucide-check-circle-2 class="w-4 h-4 stroke-[2.5]" />
                                    <span>Selesai & Bayar</span>
                                </button>
                            @elseif ($item->status === 'completed' && ! $item->transaction)
                                <button 
                                    type="button" 
                                    wire:click="openPaymentModal({{ $item->id }})"
                                    class="px-3 py-1.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-bold text-xs uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                >
                                    Catat Bayar
                                </button>
                            @endif

                            @if (! in_array($item->status, ['completed', 'cancelled']))
                                <button 
                                    type="button" 
                                    wire:click="cancelTicket({{ $item->id }})"
                                    class="p-2 rounded-xl border-2 border-black bg-white hover:bg-red-50 text-[#F28482] hover:border-[#F28482] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                    title="Batalkan tiket ini"
                                >
                                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ================= MODAL TAMBAH WALK-IN ================= -->
    @if ($showWalkInModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showWalkInModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-black bg-[#FAF0CA]">
                            Kasir Konsol
                        </span>
                        <h3 class="font-display font-black text-xl text-black mt-1">Input Pelanggan Walk-In</h3>
                    </div>
                    <button type="button" wire:click="$set('showWalkInModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <form wire:submit.prevent="submitWalkIn" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Pemilik *</label>
                            <input type="text" wire:model="walkIn_customer_name" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('walkIn_customer_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nomor WhatsApp *</label>
                            <input type="text" wire:model="walkIn_customer_phone" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('walkIn_customer_phone') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Anabul *</label>
                            <input type="text" wire:model="walkIn_pet_name" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('walkIn_pet_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Jenis Hewan</label>
                            <select wire:model="walkIn_pet_type" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                <option value="Kucing">Kucing</option>
                                <option value="Anjing Kecil">Anjing Kecil</option>
                                <option value="Kelinci">Kelinci</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase text-black">Pilih Layanan *</label>
                        <select wire:model="walkIn_service_id" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @foreach ($servicesList as $srv)
                                <option value="{{ $srv->id }}">{{ $srv->name }} (Rp {{ number_format($srv->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Pilih Groomer *</label>
                            <select wire:model="walkIn_staff_id" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @foreach ($staffList as $stf)
                                    <option value="{{ $stf->id }}">{{ $stf->nickname }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Slot Jam *</label>
                            <input type="text" wire:model="walkIn_time_slot" placeholder="Contoh: 11:00" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('walkIn_time_slot') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase text-black">Catatan Kasir (Opsional)</label>
                        <input type="text" wire:model="walkIn_notes" placeholder="Catatan khusus walk-in..." class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                    </div>

                    <div class="pt-4 border-t-2 border-black flex items-center justify-end gap-3">
                        <button type="button" wire:click="$set('showWalkInModal', false)" class="px-4 py-2 rounded-xl border-2 border-black bg-white text-xs font-bold uppercase cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] cursor-pointer">
                            Simpan & Cetak Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================= MODAL CATAT PEMBAYARAN KASIR ================= -->
    @if ($showPaymentModal && $payingAppointment)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showPaymentModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-md w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col space-y-6">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-black bg-[#FAF0CA]">
                            Kuitansi Kasir
                        </span>
                        <h3 class="font-display font-black text-xl text-black mt-1">Penyelesaian Transaksi</h3>
                    </div>
                    <button type="button" wire:click="$set('showPaymentModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <!-- Invoice Details -->
                <div class="p-4 rounded-xl border-2 border-black bg-[#FAF0CA] space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="font-semibold text-stone-600">Nomor Tiket:</span>
                        <strong class="font-black text-black">#{{ $payingAppointment->ticket_number }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-stone-600">Anabul:</span>
                        <strong class="font-bold text-black">{{ $payingAppointment->pet_name }} ({{ $payingAppointment->customer_name }})</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-stone-600">Layanan:</span>
                        <strong class="font-bold text-black">{{ $payingAppointment->service->name }}</strong>
                    </div>
                    <div class="pt-2 border-t border-black/20 flex justify-between items-baseline">
                        <span class="font-bold uppercase text-stone-800">Total Tagihan:</span>
                        <span class="font-display font-black text-xl text-black">
                            Rp {{ number_format($payingAppointment->service->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Payment Method Radios -->
                <div class="space-y-2">
                    <label class="block font-bold uppercase tracking-wider text-black text-xs">
                        Pilih Metode Pembayaran
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="p-3 rounded-xl border-2 border-black text-center cursor-pointer transition-all {{ $payment_method === 'tunai' ? 'bg-[#FFBA08] font-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white font-bold' }}">
                            <input type="radio" wire:model="payment_method" value="tunai" class="sr-only">
                            <span class="text-xs uppercase block">Tunai</span>
                        </label>
                        <label class="p-3 rounded-xl border-2 border-black text-center cursor-pointer transition-all {{ $payment_method === 'qris' ? 'bg-[#BEE1E6] font-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white font-bold' }}">
                            <input type="radio" wire:model="payment_method" value="qris" class="sr-only">
                            <span class="text-xs uppercase block">QRIS</span>
                        </label>
                        <label class="p-3 rounded-xl border-2 border-black text-center cursor-pointer transition-all {{ $payment_method === 'transfer' ? 'bg-[#A7D7C5] font-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white font-bold' }}">
                            <input type="radio" wire:model="payment_method" value="transfer" class="sr-only">
                            <span class="text-xs uppercase block">Transfer</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t-2 border-black flex items-center justify-end gap-3">
                    <button type="button" wire:click="$set('showPaymentModal', false)" class="px-4 py-2 rounded-xl border-2 border-black bg-white text-xs font-bold uppercase cursor-pointer">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmPayment" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#2D6A4F] text-[#FFBA08] font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] flex items-center gap-1.5 cursor-pointer">
                        <x-lucide-check class="w-4 h-4 stroke-[3]" />
                        <span>Konfirmasi Lunas & Selesai</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
