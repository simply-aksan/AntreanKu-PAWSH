<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-black/10 pb-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#FAF0CA] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212] mb-2">
                <x-lucide-user-cog class="w-4 h-4 text-[#2D6A4F]" />
                <span class="text-black">Pengaturan Akun & Profil</span>
            </div>
            <h1 class="font-display font-black text-3xl sm:text-4xl text-black tracking-tight">
                Profil Pengguna
            </h1>
            <p class="text-sm font-semibold text-stone-600 mt-1">
                Kelola data diri, kontak aktif, dan keamanan kata sandi akun Anda.
            </p>
        </div>

        <!-- Quick Back Button Based on Role -->
        <div class="shrink-0">
            @if ($user->role === 'owner')
                <a href="{{ route('owner') }}" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 transition-all">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    <span>Kembali ke Panel Owner</span>
                </a>
            @elseif ($user->role === 'staff')
                <a href="{{ route('workstation') }}" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 transition-all">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    <span>Kembali ke Meja Kasir</span>
                </a>
            @else
                <a href="{{ route('customer-dashboard') }}" class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-2 transition-all">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    <span>Kembali ke Antrean Saya</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Layout Grid: 1/3 Summary + 2/3 Settings Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- ================= LEFT COLUMN: PROFILE CARD & STATS ================= -->
        <div 
            class="space-y-6"
            x-data="{
                showModal: false,
                imageSrc: null,
                zoom: 100,
                offsetX: 0,
                offsetY: 0,
                isDragging: false,
                dragStartX: 0,
                dragStartY: 0,
                imgElement: null,

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (file.size > 8 * 1024 * 1024) {
                        alert('Ukuran file maksimal adalah 8 MB.');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageSrc = e.target.result;
                        this.zoom = 100;
                        this.offsetX = 0;
                        this.offsetY = 0;
                        this.showModal = true;
                        
                        const img = new Image();
                        img.onload = () => {
                            this.imgElement = img;
                            this.$nextTick(() => this.drawCanvas());
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    event.target.value = '';
                },

                startDrag(e) {
                    this.isDragging = true;
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    this.dragStartX = clientX - this.offsetX;
                    this.dragStartY = clientY - this.offsetY;
                },

                onDrag(e) {
                    if (!this.isDragging) return;
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    this.offsetX = clientX - this.dragStartX;
                    this.offsetY = clientY - this.dragStartY;
                    this.drawCanvas();
                },

                endDrag() {
                    this.isDragging = false;
                },

                changeZoom(delta) {
                    this.zoom = Math.min(250, Math.max(50, this.zoom + delta));
                    this.drawCanvas();
                },

                resetTransform() {
                    this.zoom = 100;
                    this.offsetX = 0;
                    this.offsetY = 0;
                    this.drawCanvas();
                },

                drawCanvas() {
                    const canvas = this.$refs.previewCanvas;
                    if (!canvas || !this.imgElement) return;
                    const ctx = canvas.getContext('2d');
                    const size = canvas.width;

                    ctx.clearRect(0, 0, size, size);

                    const img = this.imgElement;
                    const scaleFactor = (this.zoom / 100);
                    
                    const imgRatio = img.width / img.height;
                    let drawWidth, drawHeight;
                    if (imgRatio > 1) {
                        drawHeight = size * scaleFactor;
                        drawWidth = drawHeight * imgRatio;
                    } else {
                        drawWidth = size * scaleFactor;
                        drawHeight = drawWidth / imgRatio;
                    }

                    const centerX = (size - drawWidth) / 2 + (this.offsetX * (size / 260));
                    const centerY = (size - drawHeight) / 2 + (this.offsetY * (size / 260));

                    ctx.save();
                    ctx.drawImage(img, centerX, centerY, drawWidth, drawHeight);
                    ctx.restore();
                },

                applyAvatar() {
                    const canvas = this.$refs.previewCanvas;
                    if (!canvas) return;
                    const dataUrl = canvas.toDataURL('image/png', 0.92);
                    this.showModal = false;
                    $wire.saveAvatar(dataUrl);
                }
            }"
        >
            
            <!-- User Identity Card -->
            <div class="border-3 border-black rounded-3xl bg-white p-6 shadow-[6px_6px_0px_0px_#121212] relative overflow-hidden">
                <div class="absolute -top-4 -right-4 w-20 h-20 text-[#2D6A4F]/10 pointer-events-none rotate-12">
                    <x-lucide-paw-print class="w-full h-full" />
                </div>

                <div class="flex flex-col items-center text-center space-y-3 relative z-10">
                    <!-- Avatar Photo or Big Monogram Initial -->
                    <div class="relative group">
                        @if ($user->avatar_url)
                            <img 
                                src="{{ str_starts_with($user->avatar_url, 'data:') ? $user->avatar_url : $user->avatar_url.'?v='.time() }}" 
                                alt="{{ $user->name }}" 
                                class="w-24 h-24 rounded-2xl border-3 border-black object-cover shadow-[4px_4px_0px_0px_#000]"
                            >
                        @else
                            <div class="w-24 h-24 rounded-2xl border-3 border-black flex items-center justify-center font-display font-black text-3xl shadow-[4px_4px_0px_0px_#000] {{ $user->role === 'owner' ? 'bg-[#FFBA08] text-black' : ($user->role === 'staff' ? 'bg-[#BEE1E6] text-black' : 'bg-[#A7D7C5] text-black') }}">
                                @if ($user->role === 'owner')
                                    <x-lucide-crown class="w-12 h-12 text-black stroke-[2.5]" />
                                @elseif ($user->role === 'staff')
                                    <x-lucide-badge-check class="w-12 h-12 text-black stroke-[2.5]" />
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                        @endif

                        <!-- Camera Quick Action Button -->
                        <button 
                            type="button"
                            @click="$refs.avatarFileInput.click()"
                            class="absolute -bottom-2 -right-2 p-2 rounded-xl border-2 border-black bg-[#FFBA08] hover:bg-[#2D6A4F] text-black hover:text-[#FAF0CA] shadow-[2px_2px_0px_0px_#000] cursor-pointer transition-all hover:scale-105"
                            title="Ganti / Unggah Foto Profil"
                        >
                            <x-lucide-camera class="w-4 h-4 stroke-[2.5]" />
                        </button>
                    </div>

                    <!-- Photo Management Buttons -->
                    <div class="flex items-center gap-2 pt-1">
                        <input 
                            type="file" 
                            x-ref="avatarFileInput" 
                            @change="handleFileSelect($event)" 
                            accept="image/png, image/jpeg, image/jpg, image/webp" 
                            class="hidden"
                        >
                        <button 
                            type="button"
                            @click="$refs.avatarFileInput.click()"
                            class="px-3 py-1.5 rounded-lg border-2 border-black bg-stone-100 hover:bg-[#FAF0CA] text-stone-800 font-display font-black text-[11px] uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-1.5 cursor-pointer transition-all"
                        >
                            <x-lucide-upload class="w-3.5 h-3.5 stroke-[2.5]" />
                            <span>{{ $user->avatar ? 'Ganti Foto' : 'Unggah Foto' }}</span>
                        </button>

                        @if ($user->avatar)
                            <button 
                                type="button"
                                wire:click="deleteAvatar"
                                wire:confirm="Apakah Anda yakin ingin menghapus foto profil ini?"
                                class="px-3 py-1.5 rounded-lg border-2 border-black bg-red-50 hover:bg-red-100 text-red-600 font-display font-black text-[11px] uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-1 cursor-pointer transition-all"
                            >
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>

                    @error('avatar')
                        <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                    @enderror

                    <div>
                        <h2 class="font-display font-black text-xl text-black">{{ $user->name }}</h2>
                        <p class="text-xs font-semibold text-stone-500">{{ $user->phone }}</p>
                        @if ($user->email)
                            <p class="text-xs font-semibold text-stone-500">{{ $user->email }}</p>
                        @endif
                    </div>

                    <!-- Role Badge -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg border-2 border-black font-display font-black text-xs uppercase tracking-wider {{ $user->role === 'owner' ? 'bg-[#FFBA08] text-black shadow-[2px_2px_0px_0px_#000]' : ($user->role === 'staff' ? 'bg-[#BEE1E6] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-[#A7D7C5] text-black shadow-[2px_2px_0px_0px_#000]') }}">
                        @if ($user->role === 'owner')
                            <x-lucide-shield-check class="w-3.5 h-3.5" />
                            <span>Owner / Pemilik Usaha</span>
                        @elseif ($user->role === 'staff')
                            <x-lucide-scissors class="w-3.5 h-3.5" />
                            <span>Staf Groomer & Kasir</span>
                        @else
                            <x-lucide-heart class="w-3.5 h-3.5" />
                            <span>Pelanggan PAWSH!</span>
                        @endif
                    </div>

                    <div class="w-full pt-4 border-t-2 border-black/10 text-left text-xs text-stone-600 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold">Terdaftar Sejak:</span>
                            <span class="font-semibold">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold">ID Pengguna:</span>
                            <span class="font-mono font-semibold">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= AVATAR CUSTOMIZER / CROP & SIZE MODAL ================= -->
            <div 
                x-show="showModal" 
                x-cloak 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] bg-black/70 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
            >
                <div 
                    @click.away="showModal = false"
                    class="max-w-md w-full bg-white border-3 border-black rounded-3xl p-6 shadow-[8px_8px_0px_0px_#121212] space-y-5 relative"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b-2 border-black/10 pb-3">
                        <div class="flex items-center gap-2">
                            <x-lucide-crop class="w-5 h-5 text-[#2D6A4F] stroke-[2.5]" />
                            <h3 class="font-display font-black text-lg text-black">Sesuaikan Ukuran Foto</h3>
                        </div>
                        <button 
                            type="button" 
                            @click="showModal = false" 
                            class="p-1 rounded-lg border-2 border-black hover:bg-stone-100 cursor-pointer"
                        >
                            <x-lucide-x class="w-4 h-4 stroke-[2.5]" />
                        </button>
                    </div>

                    <!-- Canvas Interactive Preview Box -->
                    <div class="flex flex-col items-center gap-3">
                        <p class="text-xs font-semibold text-stone-600 text-center">
                            Geser gambar untuk mengatur posisi & gunakan slider di bawah untuk mengatur ukuran/zoom.
                        </p>

                        <!-- Rounded Viewport Box -->
                        <div 
                            class="w-64 h-64 rounded-3xl border-3 border-black overflow-hidden relative shadow-[4px_4px_0px_0px_#000] bg-[#FAF0CA] flex items-center justify-center cursor-move select-none touch-none"
                            @mousedown="startDrag($event)"
                            @mousemove="onDrag($event)"
                            @mouseup="endDrag()"
                            @mouseleave="endDrag()"
                            @touchstart="startDrag($event)"
                            @touchmove="onDrag($event)"
                            @touchend="endDrag()"
                        >
                            <canvas x-ref="previewCanvas" width="400" height="400" class="w-full h-full object-cover pointer-events-none"></canvas>
                            <div class="absolute inset-0 pointer-events-none rounded-3xl border-2 border-dashed border-black/40"></div>
                        </div>
                    </div>

                    <!-- Zoom / Size Customizer -->
                    <div class="space-y-2 bg-[#FDFBF7] p-3.5 rounded-2xl border-2 border-black">
                        <div class="flex items-center justify-between text-xs font-bold text-black">
                            <span class="flex items-center gap-1.5 uppercase tracking-wider text-[11px]">
                                <x-lucide-zoom-in class="w-3.5 h-3.5 text-[#2D6A4F]" />
                                <span>Ukuran / Skala Foto</span>
                            </span>
                            <span class="font-mono font-black" x-text="zoom + '%'"></span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="changeZoom(-15)"
                                class="p-2 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] shadow-[1.5px_1.5px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                title="Perkecil Ukuran"
                            >
                                <x-lucide-minus class="w-4 h-4 stroke-[2.5]" />
                            </button>
                            
                            <input 
                                type="range" 
                                min="50" 
                                max="250" 
                                step="5" 
                                x-model.number="zoom" 
                                @input="drawCanvas()" 
                                class="w-full accent-[#2D6A4F] cursor-pointer"
                            >

                            <button 
                                type="button" 
                                @click="changeZoom(15)"
                                class="p-2 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] shadow-[1.5px_1.5px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                title="Perbesar Ukuran"
                            >
                                <x-lucide-plus class="w-4 h-4 stroke-[2.5]" />
                            </button>

                            <button 
                                type="button" 
                                @click="resetTransform()"
                                class="p-2 rounded-xl border-2 border-black bg-white hover:bg-stone-100 shadow-[1.5px_1.5px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer"
                                title="Reset Ukuran & Posisi"
                            >
                                <x-lucide-rotate-ccw class="w-4 h-4 stroke-[2.5]" />
                            </button>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="showModal = false"
                            class="px-4 py-2.5 rounded-xl border-2 border-black bg-white hover:bg-stone-100 text-stone-700 font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            @click="applyAvatar()"
                            class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] hover:bg-[#2D6A4F] text-black hover:text-[#FAF0CA] font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] flex items-center gap-1.5 cursor-pointer transition-all"
                        >
                            <x-lucide-check class="w-4 h-4 stroke-[2.5]" />
                            <span>Gunakan Foto Ini</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Role Metrics / Stats Widget -->
            <div class="border-2 border-black rounded-2xl bg-[#FAF0CA] p-5 shadow-[4px_4px_0px_0px_#121212] space-y-3">
                <div class="flex items-center gap-2">
                    <x-lucide-activity class="w-4 h-4 text-[#2D6A4F] stroke-[2.5]" />
                    <span class="font-display font-black text-xs uppercase tracking-wider text-black">Aktivitas Akun</span>
                </div>

                @if ($user->role === 'customer')
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="p-3 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[10px] font-bold uppercase text-stone-500 block">Total Tiket</span>
                            <span class="font-display font-black text-2xl text-black">{{ $stats['total_tickets'] ?? 0 }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[10px] font-bold uppercase text-stone-500 block">Tiket Aktif</span>
                            <span class="font-display font-black text-2xl text-[#2D6A4F]">{{ $stats['active_tickets'] ?? 0 }}</span>
                        </div>
                    </div>
                @elseif ($user->role === 'staff')
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="p-3 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[10px] font-bold uppercase text-stone-500 block">Selesai Ditangani</span>
                            <span class="font-display font-black text-2xl text-black">{{ $stats['handled_tickets'] ?? 0 }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[10px] font-bold uppercase text-stone-500 block">Antrean Hari Ini</span>
                            <span class="font-display font-black text-2xl text-[#2D6A4F]">{{ $stats['today_tickets'] ?? 0 }}</span>
                        </div>
                    </div>
                @elseif ($user->role === 'owner')
                    <div class="grid grid-cols-3 gap-2 pt-1 text-center">
                        <div class="p-2.5 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[9px] font-bold uppercase text-stone-500 block">Layanan</span>
                            <span class="font-display font-black text-xl text-black">{{ $stats['total_services'] ?? 0 }}</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[9px] font-bold uppercase text-stone-500 block">Staf</span>
                            <span class="font-display font-black text-xl text-black">{{ $stats['total_staff'] ?? 0 }}</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_#000]">
                            <span class="text-[9px] font-bold uppercase text-stone-500 block">Antrean</span>
                            <span class="font-display font-black text-xl text-[#2D6A4F]">{{ $stats['total_appointments'] ?? 0 }}</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- ================= RIGHT COLUMN: SETTINGS FORMS ================= -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Section 1: Data Diri & Kontak -->
            <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-8 shadow-[6px_6px_0px_0px_#121212] space-y-6">
                
                <div class="flex items-center justify-between border-b-2 border-black/10 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#A7D7C5] border-2 border-black flex items-center justify-center shadow-[1.5px_1.5px_0px_0px_#000]">
                            <x-lucide-user class="w-4 h-4 text-black stroke-[2.5]" />
                        </div>
                        <div>
                            <h2 class="font-display font-black text-lg text-black">Informasi Akun</h2>
                            <p class="text-xs text-stone-600 font-medium">Perbarui identitas diri dan informasi kontak.</p>
                        </div>
                    </div>
                </div>

                @if (session('profile_success'))
                    <div class="p-3.5 rounded-xl border-2 border-black bg-[#A7D7C5] shadow-[3px_3px_0px_0px_#000] flex items-center gap-2.5">
                        <x-lucide-check-circle class="w-5 h-5 text-black shrink-0 stroke-[2.5]" />
                        <span class="font-bold text-xs text-black">{{ session('profile_success') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updateProfile" class="space-y-5">
                    
                    <!-- Nama Lengkap -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Nama Lengkap *
                        </label>
                        <input 
                            type="text" 
                            wire:model="name"
                            class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                            placeholder="Contoh: Budi Santoso"
                        >
                        @error('name')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Grid: Phone & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor HP / WhatsApp -->
                        <div class="space-y-1.5">
                            <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                Nomor WhatsApp / HP *
                            </label>
                            <input 
                                type="text" 
                                wire:model="phone"
                                class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                placeholder="Contoh: 081234567890"
                            >
                            @error('phone')
                                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                Alamat Email (Opsional)
                            </label>
                            <input 
                                type="email" 
                                wire:model="email"
                                class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                placeholder="Contoh: nama@domain.com"
                            >
                            @error('email')
                                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Specific Fields for Staff Role -->
                    @if ($user->role === 'staff')
                        <div class="pt-4 border-t-2 border-black/10 space-y-4">
                            <div class="flex items-center gap-2">
                                <x-lucide-scissors class="w-4 h-4 text-[#2D6A4F] stroke-[2.5]" />
                                <span class="font-display font-black text-xs uppercase tracking-wider text-black">Profil Publik Staf Groomer</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                        Nama Panggilan (Groomer)
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="nickname"
                                        class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                        placeholder="Contoh: Kak Sari"
                                    >
                                    @error('nickname')
                                        <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                        Spesialisasi / Jabatan
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="title"
                                        class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                        placeholder="Contoh: Senior Feline Specialist"
                                    >
                                    @error('title')
                                        <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                    Bio Singkat
                                </label>
                                <textarea 
                                    wire:model="bio"
                                    rows="2"
                                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden resize-none"
                                    placeholder="Ceritakan sedikit keahlian Anda merawat anabul..."
                                ></textarea>
                                @error('bio')
                                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Submit Profile Button -->
                    <div class="flex items-center justify-end pt-2">
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#2D6A4F] text-[#FAF0CA] font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#FFBA08] hover:text-black hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer flex items-center gap-2 transition-all disabled:opacity-50"
                        >
                            <x-lucide-save class="w-4 h-4 stroke-[2.5]" />
                            <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                            <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Section 2: Keamanan Akun & Ubah Kata Sandi -->
            <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-8 shadow-[6px_6px_0px_0px_#121212] space-y-6">
                
                <div class="flex items-center justify-between border-b-2 border-black/10 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#FAF0CA] border-2 border-black flex items-center justify-center shadow-[1.5px_1.5px_0px_0px_#000]">
                            <x-lucide-shield-alert class="w-4 h-4 text-black stroke-[2.5]" />
                        </div>
                        <div>
                            <h2 class="font-display font-black text-lg text-black">Keamanan & Kata Sandi</h2>
                            <p class="text-xs text-stone-600 font-medium">Ubah kata sandi untuk mengamankan akses akun Anda.</p>
                        </div>
                    </div>
                </div>

                @if (session('password_success'))
                    <div class="p-3.5 rounded-xl border-2 border-black bg-[#A7D7C5] shadow-[3px_3px_0px_0px_#000] flex items-center gap-2.5">
                        <x-lucide-check-circle class="w-5 h-5 text-black shrink-0 stroke-[2.5]" />
                        <span class="font-bold text-xs text-black">{{ session('password_success') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    
                    <!-- Kata Sandi Saat Ini -->
                    <div class="space-y-1.5">
                        <label class="block font-bold uppercase tracking-wider text-black text-xs">
                            Kata Sandi Saat Ini *
                        </label>
                        <input 
                            type="password" 
                            wire:model="current_password"
                            class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                            placeholder="Ketik kata sandi saat ini..."
                        >
                        @error('current_password')
                            <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Grid: Password Baru & Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                Kata Sandi Baru *
                            </label>
                            <input 
                                type="password" 
                                wire:model="new_password"
                                class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                placeholder="Minimal 6 karakter..."
                            >
                            @error('new_password')
                                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-bold uppercase tracking-wider text-black text-xs">
                                Konfirmasi Kata Sandi Baru *
                            </label>
                            <input 
                                type="password" 
                                wire:model="new_password_confirmation"
                                class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                                placeholder="Ulangi kata sandi baru..."
                            >
                            @error('new_password_confirmation')
                                <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Password Button -->
                    <div class="flex items-center justify-end pt-2">
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] hover:translate-x-[-1px] hover:translate-y-[-1px] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer flex items-center gap-2 transition-all disabled:opacity-50"
                        >
                            <x-lucide-lock class="w-4 h-4 stroke-[2.5]" />
                            <span wire:loading.remove wire:target="updatePassword">Ubah Kata Sandi</span>
                            <span wire:loading wire:target="updatePassword">Menyimpan...</span>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
