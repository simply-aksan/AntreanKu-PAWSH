<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10 relative">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b-3 border-black">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#FAF0CA] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] mb-2">
                <x-lucide-shield-check class="w-4 h-4 text-[#2D6A4F]" />
                <span class="text-black">AntreanKu &bull; Portal Pemilik Usaha</span>
            </div>
            <h1 class="font-display text-3xl font-black text-black tracking-tight">
                Dashboard Manajemen Owner
            </h1>
            <p class="text-xs font-semibold text-stone-600">
                Kelola penuh data layanan, staf groomer, dan tinjau buku besar kuitansi transaksi.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Open Cashier Workstation as Owner -->
            <a 
                href="{{ route('workstation') }}"
                class="px-4 py-2.5 rounded-xl border-2 border-black bg-[#BEE1E6] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 cursor-pointer transition-all"
            >
                <x-lucide-clipboard-list class="w-4 h-4 text-black" />
                <span>Buka Meja Kasir</span>
            </a>

            <!-- Add Service Button -->
            <button 
                type="button" 
                wire:click="$set('showNewServiceModal', true)"
                class="px-4 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 cursor-pointer transition-all"
            >
                <x-lucide-plus-circle class="w-4 h-4 stroke-[2.5]" />
                <span>Tambah Layanan</span>
            </button>

            <!-- Add Staff Button -->
            <button 
                type="button" 
                wire:click="$set('showNewStaffModal', true)"
                class="px-4 py-2.5 rounded-xl border-2 border-black bg-[#2D6A4F] text-[#FAF0CA] font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#FFBA08] hover:text-black hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 cursor-pointer transition-all"
            >
                <x-lucide-user-plus class="w-4 h-4 stroke-[2.5]" />
                <span>Tambah Staf Groomer</span>
            </button>
        </div>
    </div>

    <!-- Overview Metrics (Botanical Palette) -->
    <!-- Overview Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="border-2 border-black rounded-2xl bg-white p-5 shadow-[4px_4px_0px_0px_#000] space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-stone-600 uppercase tracking-wider block">Total Reservasi</span>
                <x-lucide-calendar class="w-4 h-4 text-stone-400" />
            </div>
            <span class="font-display font-black text-3xl text-black block">{{ $totalAppointments }}</span>
        </div>

        <div class="border-2 border-black rounded-2xl bg-[#A7D7C5] p-5 shadow-[4px_4px_0px_0px_#000] space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-black uppercase tracking-wider block">Total Omzet Lunas</span>
                <x-lucide-banknote class="w-4 h-4 text-[#2D6A4F]" />
            </div>
            <span class="font-display font-black text-2xl text-black truncate block">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </span>
        </div>

        <div class="border-2 border-black rounded-2xl bg-[#BEE1E6] p-5 shadow-[4px_4px_0px_0px_#000] space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-black uppercase tracking-wider block">Layanan Terdaftar</span>
                <x-lucide-sparkles class="w-4 h-4 text-[#2D6A4F]" />
            </div>
            <span class="font-display font-black text-3xl text-black block">{{ $services->count() }}</span>
        </div>

        <div class="border-2 border-black rounded-2xl bg-[#FFBA08] p-5 shadow-[4px_4px_0px_0px_#000] space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-black uppercase tracking-wider block">Staf Groomer Aktif</span>
                <x-lucide-users class="w-4 h-4 text-black" />
            </div>
            <span class="font-display font-black text-3xl text-black block">{{ $staffList->where('is_active', true)->count() }}</span>
        </div>
    </div>

    <!-- Section: Services Management (CRUD) -->
    <div class="border-3 border-black rounded-2xl bg-white shadow-[6px_6px_0px_0px_#000] overflow-hidden">
        <div class="p-4 bg-[#FDFBF7] border-b-2 border-black flex items-center justify-between">
            <h3 class="font-display font-black text-base text-black uppercase tracking-wider flex items-center gap-2">
                <x-lucide-sparkles class="w-4 h-4 text-[#2D6A4F]" />
                <span>Kelola Katalog Layanan & Tarif (CRUD)</span>
            </h3>
            <span class="text-xs font-bold text-stone-600">{{ $services->count() }} paket terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-100 border-b-2 border-black font-display font-black uppercase text-black text-[11px]">
                    <tr>
                        <th class="p-4">Nama Layanan</th>
                        <th class="p-4">Durasi</th>
                        <th class="p-4">Tarif</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-stone-200 font-semibold">
                    @foreach ($services as $srv)
                        <tr class="hover:bg-stone-50">
                            <td class="p-4">
                                <span class="font-bold text-black text-sm block">{{ $srv->name }}</span>
                                <span class="text-[11px] text-stone-600 line-clamp-1">{{ $srv->description }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold">{{ $srv->duration_minutes }} Menit</td>
                            <td class="p-4 font-display font-black text-sm text-black">
                                Rp {{ number_format($srv->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md border border-black text-[10px] font-black uppercase {{ $srv->is_active ? 'bg-[#A7D7C5] text-black' : 'bg-stone-200 text-stone-600' }}">
                                    {{ $srv->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button 
                                        type="button" 
                                        wire:click="openEditService({{ $srv->id }})"
                                        class="px-2.5 py-1 rounded-lg border-2 border-black bg-white hover:bg-stone-100 font-display font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="toggleServiceStatus({{ $srv->id }})"
                                        class="px-2.5 py-1 rounded-lg border-2 border-black font-display font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer {{ $srv->is_active ? 'bg-stone-100 text-stone-700' : 'bg-[#A7D7C5] text-black' }}"
                                    >
                                        {{ $srv->is_active ? 'Nonaktif' : 'Aktifkan' }}
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="deleteService({{ $srv->id }})"
                                        class="p-1 rounded-lg border-2 border-black bg-white hover:bg-red-50 text-[#F28482] hover:border-[#F28482] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer"
                                        onclick="return confirm('Hapus layanan ini secara permanen?')"
                                        title="Hapus Layanan"
                                    >
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section: Staff Groomer Management (CRUD) -->
    <div class="border-3 border-black rounded-2xl bg-white shadow-[6px_6px_0px_0px_#000] overflow-hidden">
        <div class="p-4 bg-[#FDFBF7] border-b-2 border-black flex items-center justify-between">
            <h3 class="font-display font-black text-base text-black uppercase tracking-wider flex items-center gap-2">
                <x-lucide-users class="w-4 h-4 text-[#2D6A4F]" />
                <span>Kelola Staf Groomer (CRUD)</span>
            </h3>
            <span class="text-xs font-bold text-stone-600">{{ $staffList->count() }} staf terdaftar</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-5">
            @foreach ($staffList as $stf)
                <div class="border-2 border-black rounded-xl p-4 bg-white shadow-[3px_3px_0px_0px_#000] space-y-3 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl border-2 border-black flex items-center justify-center font-display font-black text-sm text-black shadow-[2px_2px_0px_0px_#000] shrink-0 overflow-hidden" style="background-color: {{ $stf->badge_color }}">
                                    @if ($stf->avatar_url)
                                        <img src="{{ $stf->avatar_url }}" alt="{{ $stf->nickname }}" class="w-full h-full object-cover">
                                    @else
                                        {{ mb_substr(preg_replace('/^Kak\s+/i', '', $stf->nickname), 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-display font-black text-sm text-black truncate">{{ $stf->nickname }}</h4>
                                    <span class="text-[10px] text-stone-600 block truncate">{{ $stf->title }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md border-2 border-black text-[9px] font-black uppercase {{ $stf->is_active ? 'bg-[#A7D7C5] text-black' : 'bg-stone-200 text-stone-600' }}">
                                {{ $stf->is_active ? 'Aktif' : 'Cuti' }}
                            </span>
                        </div>

                        <p class="text-[11px] text-stone-600 line-clamp-2">{{ $stf->bio }}</p>
                        <span class="text-[10px] font-mono font-bold text-stone-700 block">HP: {{ $stf->user?->phone ?? '-' }}</span>
                    </div>

                    <div class="pt-3 border-t-2 border-stone-200 flex items-center justify-between gap-1.5">
                        <div class="flex items-center gap-1.5">
                            <button 
                                type="button" 
                                wire:click="openEditStaff({{ $stf->id }})"
                                class="px-2.5 py-1 rounded-lg border-2 border-black bg-white hover:bg-stone-100 font-display font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer"
                            >
                                Edit
                            </button>
                            <button 
                                type="button" 
                                wire:click="toggleStaffStatus({{ $stf->id }})"
                                class="px-2.5 py-1 rounded-lg border-2 border-black font-display font-black text-[10px] uppercase shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer {{ $stf->is_active ? 'bg-stone-100 text-stone-700' : 'bg-[#A7D7C5] text-black' }}"
                            >
                                {{ $stf->is_active ? 'Libur' : 'Aktif' }}
                            </button>
                        </div>

                        <button 
                            type="button" 
                            wire:click="deleteStaff({{ $stf->id }})"
                            class="p-1 rounded-lg border-2 border-black bg-white text-[#F28482] hover:bg-red-50 hover:border-[#F28482] shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer"
                            onclick="return confirm('Hapus staf groomer ini beserta akun login miliknya?')"
                            title="Hapus Staf"
                        >
                            <x-lucide-trash-2 class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section: Recent Transactions Ledger -->
    <div class="border-3 border-black rounded-2xl bg-white shadow-[6px_6px_0px_0px_#000] overflow-hidden">
        <div class="p-4 bg-[#FDFBF7] border-b-2 border-black flex items-center justify-between">
            <h3 class="font-display font-black text-base text-black uppercase tracking-wider flex items-center gap-2">
                <x-lucide-receipt class="w-4 h-4 text-[#2D6A4F]" />
                <span>Buku Kuitansi Pembayaran Terakhir</span>
            </h3>
            <span class="text-xs font-bold text-stone-600">Terbaru</span>
        </div>

        <div class="divide-y border-stone-200">
            @forelse ($recentTransactions as $tx)
                <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <strong class="font-mono font-bold text-black">{{ $tx->invoice_number }}</strong>
                            <span class="px-2 py-0.5 rounded border border-black text-[9px] font-black uppercase bg-[#A7D7C5]">
                                Lunas ({{ strtoupper($tx->payment_method) }})
                            </span>
                        </div>
                        <span class="text-[11px] font-medium text-stone-600 block mt-0.5">
                            Tiket #{{ $tx->appointment?->ticket_number }} &bull; Anabul: {{ $tx->appointment?->pet_name }} ({{ $tx->appointment?->customer_name }}) &bull; Groomer: {{ $tx->appointment?->staff?->nickname }}
                        </span>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="font-display font-black text-sm text-black block">
                            Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-stone-500 font-medium">
                            {{ $tx->paid_at?->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-xs text-stone-500 font-bold">
                    Belum ada riwayat transaksi lunas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================= MODAL TAMBAH LAYANAN ================= -->
    @if ($showNewServiceModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showNewServiceModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <h3 class="font-display font-black text-xl text-black">Tambah Layanan Grooming</h3>
                    <button type="button" wire:click="$set('showNewServiceModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <form wire:submit.prevent="createService" class="flex flex-col flex-1 min-h-0 pt-4">
                    <div class="space-y-4 overflow-y-auto flex-1 pr-1">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Layanan *</label>
                            <input type="text" wire:model="new_name" placeholder="Contoh: Styling Bulu Khusus" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('new_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Deskripsi Singkat</label>
                            <textarea wire:model="new_description" rows="2" placeholder="Penjelasan paket perawatan..." class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Tarif (Rp) *</label>
                                <input type="number" wire:model="new_price" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('new_price') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Durasi (Menit)</label>
                                <input type="number" wire:model="new_duration" value="30" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Pilihan Warna Badge</label>
                            <div class="flex gap-2">
                                @foreach (['#FFBA08', '#A7D7C5', '#BEE1E6', '#FAF0CA', '#2D6A4F'] as $c)
                                    <button type="button" wire:click="$set('new_badge_color', '{{ $c }}')" class="w-8 h-8 rounded-lg border-2 border-black cursor-pointer {{ $new_badge_color === $c ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t-2 border-black flex items-center justify-end gap-3 shrink-0">
                        <button type="button" wire:click="$set('showNewServiceModal', false)" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-xs font-bold uppercase cursor-pointer shadow-[2px_2px_0px_0px_#000]">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] cursor-pointer transition-all">
                            Simpan Layanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================= MODAL EDIT LAYANAN ================= -->
    @if ($showEditServiceModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showEditServiceModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <h3 class="font-display font-black text-xl text-black">Edit Data Layanan</h3>
                    <button type="button" wire:click="$set('showEditServiceModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <form wire:submit.prevent="updateService" class="flex flex-col flex-1 min-h-0 pt-4">
                    <div class="space-y-4 overflow-y-auto flex-1 pr-1">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Layanan *</label>
                            <input type="text" wire:model="edit_name" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('edit_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Deskripsi Singkat</label>
                            <textarea wire:model="edit_description" rows="2" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Tarif (Rp) *</label>
                                <input type="number" wire:model="edit_price" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('edit_price') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Durasi (Menit)</label>
                                <input type="number" wire:model="edit_duration" class="w-full text-xs font-bold px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Warna Badge</label>
                            <div class="flex gap-2">
                                @foreach (['#FFBA08', '#A7D7C5', '#BEE1E6', '#FAF0CA', '#2D6A4F'] as $c)
                                    <button type="button" wire:click="$set('edit_badge_color', '{{ $c }}')" class="w-8 h-8 rounded-lg border-2 border-black cursor-pointer {{ $edit_badge_color === $c ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t-2 border-black flex items-center justify-end gap-3 shrink-0">
                        <button type="button" wire:click="$set('showEditServiceModal', false)" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-xs font-bold uppercase cursor-pointer shadow-[2px_2px_0px_0px_#000]">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] cursor-pointer transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================= MODAL TAMBAH STAF GROOMER ================= -->
    @if ($showNewStaffModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showNewStaffModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <h3 class="font-display font-black text-xl text-black">Tambah Staf Groomer Baru</h3>
                    <button type="button" wire:click="$set('showNewStaffModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <form wire:submit.prevent="createStaff" class="flex flex-col flex-1 min-h-0 pt-4">
                    <div class="space-y-4 overflow-y-auto flex-1 pr-1">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Lengkap *</label>
                            <input type="text" wire:model="new_staff_name" placeholder="Contoh: Maya Anggraini" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('new_staff_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Nama Panggilan *</label>
                                <input type="text" wire:model="new_staff_nickname" placeholder="Kak Maya" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('new_staff_nickname') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Nomor WhatsApp *</label>
                                <input type="text" wire:model="new_staff_phone" placeholder="08123456789" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('new_staff_phone') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Spesialisasi / Gelar *</label>
                            <input type="text" wire:model="new_staff_title" placeholder="Spesialis Kucing Ras & Spa" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('new_staff_title') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Biografi Singkat</label>
                            <textarea wire:model="new_staff_bio" rows="2" placeholder="Pengalaman dan keahlian groomer..." class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]"></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Warna Kartu Groomer</label>
                            <div class="flex gap-2">
                                @foreach (['#FFBA08', '#A7D7C5', '#BEE1E6', '#FAF0CA', '#2D6A4F'] as $c)
                                    <button type="button" wire:click="$set('new_staff_badge_color', '{{ $c }}')" class="w-8 h-8 rounded-lg border-2 border-black cursor-pointer {{ $new_staff_badge_color === $c ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t-2 border-black flex items-center justify-end gap-3 shrink-0">
                        <button type="button" wire:click="$set('showNewStaffModal', false)" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-xs font-bold uppercase cursor-pointer shadow-[2px_2px_0px_0px_#000]">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#2D6A4F] text-[#FAF0CA] font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] hover:bg-[#FFBA08] hover:text-black cursor-pointer transition-all">
                            Simpan Staf Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ================= MODAL EDIT STAF GROOMER ================= -->
    @if ($showEditStaffModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.set('showEditStaffModal', false)">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-8 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <h3 class="font-display font-black text-xl text-black">Edit Profil Staf Groomer</h3>
                    <button type="button" wire:click="$set('showEditStaffModal', false)" class="w-8 h-8 rounded-lg border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]">&times;</button>
                </div>

                <form wire:submit.prevent="updateStaff" class="flex flex-col flex-1 min-h-0 pt-4">
                    <div class="space-y-4 overflow-y-auto flex-1 pr-1">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Nama Lengkap *</label>
                            <input type="text" wire:model="edit_staff_name" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('edit_staff_name') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Nama Panggilan *</label>
                                <input type="text" wire:model="edit_staff_nickname" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('edit_staff_nickname') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold uppercase text-black">Nomor WhatsApp *</label>
                                <input type="text" wire:model="edit_staff_phone" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                                @error('edit_staff_phone') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Spesialisasi *</label>
                            <input type="text" wire:model="edit_staff_title" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]">
                            @error('edit_staff_title') <span class="text-[10px] font-bold text-[#F28482]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Biografi Singkat</label>
                            <textarea wire:model="edit_staff_bio" rows="2" class="w-full text-xs font-medium px-3 py-2 rounded-xl border-2 border-black bg-[#FDFBF7]"></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase text-black">Warna Kartu</label>
                            <div class="flex gap-2">
                                @foreach (['#FFBA08', '#A7D7C5', '#BEE1E6', '#FAF0CA', '#2D6A4F'] as $c)
                                    <button type="button" wire:click="$set('edit_staff_badge_color', '{{ $c }}')" class="w-8 h-8 rounded-lg border-2 border-black cursor-pointer {{ $edit_staff_badge_color === $c ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t-2 border-black flex items-center justify-end gap-3 shrink-0">
                        <button type="button" wire:click="$set('showEditStaffModal', false)" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-xs font-bold uppercase cursor-pointer shadow-[2px_2px_0px_0px_#000]">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase shadow-[2px_2px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] cursor-pointer transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
