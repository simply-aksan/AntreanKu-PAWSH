<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'PAWSH! Pet Grooming & Studio - AntreanKu' }}</title>

    <!-- Favicon: Authentic PAWSH! Vector Logo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/PAWSH_logo.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('assets/PAWSH_logo.png') }}">

    <!-- Google Fonts: Space Grotesk (Headings) & Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700;900&display=swap" rel="stylesheet">

    <!-- 3D Model Viewer by Google -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#FDFBF7] bg-grid-soft text-[#121212] font-sans antialiased min-h-screen flex flex-col selection:bg-[#2D6A4F] selection:text-[#FFBA08] relative overflow-x-hidden">

    <!-- Background Decorative Pet Watermarks & Floating Elements (Non-intrusive) -->
    <div class="fixed inset-0 pointer-events-none select-none z-0 overflow-hidden" aria-hidden="true">
        <!-- Floating Paw Top Left -->
        <div class="absolute -top-6 -left-6 w-36 h-36 text-[#2D6A4F]/10 rotate-12">
            <x-lucide-paw-print class="w-full h-full" />
        </div>

        <!-- Floating Bone Mid Left -->
        <div class="absolute top-1/3 -left-4 w-24 h-24 text-[#FFBA08]/15 -rotate-45">
            <x-lucide-bone class="w-full h-full" />
        </div>

        <!-- Floating Dog Outline Top Right -->
        <div class="absolute top-24 -right-8 w-40 h-40 text-[#2D6A4F]/10 rotate-[-15deg]">
            <x-lucide-dog class="w-full h-full" />
        </div>

        <!-- Floating Cat Outline Mid-Bottom Left -->
        <div class="absolute top-2/3 -left-6 w-32 h-32 text-[#2D6A4F]/10 rotate-12">
            <x-lucide-cat class="w-full h-full" />
        </div>

        <!-- Floating Paw Bottom Right -->
        <div class="absolute bottom-20 -right-6 w-36 h-36 text-[#FFBA08]/15 rotate-12">
            <x-lucide-paw-print class="w-full h-full" />
        </div>

        <!-- Floating Bone Bottom Left -->
        <div class="absolute bottom-6 left-1/4 w-20 h-20 text-[#A7D7C5]/20 rotate-45">
            <x-lucide-bone class="w-full h-full" />
        </div>

        <!-- Floating Sparkles Mid Right -->
        <div class="absolute top-1/2 right-12 w-16 h-16 text-[#A7D7C5]/25 rotate-45">
            <x-lucide-sparkles class="w-full h-full" />
        </div>
    </div>

    <!-- Top Notice Banner (Matcha Green with Honey Ping) -->
    <div class="relative z-10 bg-[#2D6A4F] border-b-2 border-black px-4 py-1.5 text-center text-[10px] sm:text-xs font-bold tracking-wider uppercase text-[#FDFBF7] flex items-center justify-center gap-2 shadow-xs">
        <span class="inline-block w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-[#FFBA08] border border-black animate-ping shrink-0"></span>
        <span class="tracking-wide">
            <span class="hidden sm:inline">AntreanKu Engine &bull; </span>Official Business Template: PAWSH! Pet Care & Grooming Studio
        </span>
    </div>

    @php
        $isCustomerDashboard = request()->routeIs('customer-dashboard');
        $isWorkstation = request()->routeIs('workstation');
        $isOwnerPortal = request()->routeIs('owner');
        $isDedicatedPortal = $isCustomerDashboard || $isWorkstation || $isOwnerPortal;

        $userRole = auth()->check() ? auth()->user()->role : null;

        // Clean user name from duplicate role tags if present in database
        $cleanUserName = auth()->check() ? trim(preg_replace('/\s*\((owner|staff|customer)\)\s*/i', '', auth()->user()->name)) : '';

        // Destination URL for clicking logo
        if ($userRole === 'owner') {
            $brandUrl = route('owner');
            $brandSubtitle = 'Owner Workspace';
        } elseif ($userRole === 'staff') {
            $brandUrl = route('workstation');
            $brandSubtitle = 'Konsol Kasir';
        } elseif ($userRole === 'customer') {
            $brandUrl = route('customer-dashboard');
            $brandSubtitle = 'Portal Pelanggan';
        } else {
            $brandUrl = route('home');
            $brandSubtitle = 'Pet Care Studio';
        }
    @endphp

    <!-- Main Navigation Bar (Modern Standard 2-Cluster Architecture with Mobile Hamburger) -->
    <header 
        x-data="{ 
            mobileMenuOpen: false,
            activeTab: (window.location.hash === '#katalog' ? 'katalog' : 'beranda'),
            init() {
                const evaluateTab = () => {
                    if (window.location.hash === '#katalog') {
                        this.activeTab = 'katalog';
                    } else if (window.location.hash === '#beranda' || window.location.hash === '') {
                        this.activeTab = 'beranda';
                    }
                };

                evaluateTab();

                window.addEventListener('hashchange', () => {
                    evaluateTab();
                });

                const katalogEl = document.getElementById('katalog');
                if (katalogEl) {
                    const handleScroll = () => {
                        const rect = katalogEl.getBoundingClientRect();
                        // When top of katalog section is at or above 180px from viewport top, activate katalog tab
                        if (rect.top <= 180) {
                            this.activeTab = 'katalog';
                        } else {
                            this.activeTab = 'beranda';
                        }
                    };

                    window.addEventListener('scroll', handleScroll, { passive: true });
                    this.$nextTick(() => {
                        handleScroll();
                    });
                }
            }
        }" 
        @keydown.escape.window="mobileMenuOpen = false"
        class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b-3 border-black"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- ================= LEFT CLUSTER: BRAND + NAVIGATION TABS ================= -->
                <div class="flex items-center gap-4 lg:gap-6 shrink-0">
                    <!-- Brand Identity with Authentic Logo -->
                    <a href="{{ $brandUrl }}" class="flex items-center gap-3 group shrink-0">
                        <div class="w-12 h-12 bg-white border-2 border-black rounded-xl shadow-[3px_3px_0px_0px_#121212] p-1 flex items-center justify-center transition-transform group-hover:translate-x-[-1px] group-hover:translate-y-[-1px]">
                            <img src="{{ asset('assets/PAWSH_logo.svg') }}" alt="PAWSH! Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-display font-black text-2xl tracking-tight text-black leading-none">PAWSH!</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-[#2D6A4F]">{{ $brandSubtitle }}</span>
                        </div>
                    </a>

                    <!-- Vertical Divider Separating Brand from Navigation -->
                    <div class="hidden md:block h-8 w-[2px] bg-black/20"></div>

                    <!-- Desktop Navigation Tabs (Hidden on Mobile) -->
                    <nav class="hidden md:flex items-center gap-2">
                        @if ($userRole === 'owner')
                            <!-- OWNER TABS: Clean Segmented Control -->
                            <a href="{{ route('owner') }}" class="px-3.5 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center gap-1.5 transition-all {{ $isOwnerPortal ? 'bg-[#FFBA08] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-stone-50 hover:bg-[#FAF0CA] text-stone-700 hover:text-black shadow-[1px_1px_0px_0px_#000]' }}">
                                <x-lucide-shield-check class="w-4 h-4 stroke-[2.5]" />
                                <span>Panel Owner</span>
                            </a>
                            <a href="{{ route('workstation') }}" class="px-3.5 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center gap-1.5 transition-all {{ $isWorkstation ? 'bg-[#BEE1E6] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-stone-50 hover:bg-[#BEE1E6]/50 text-stone-700 hover:text-black shadow-[1px_1px_0px_0px_#000]' }}">
                                <x-lucide-clipboard-list class="w-4 h-4 stroke-[2.5]" />
                                <span>Meja Kasir</span>
                            </a>

                        @elseif ($userRole === 'staff')
                            <!-- STAFF TABS: Operational Workstation -->
                            <a href="{{ route('workstation') }}" class="px-3.5 py-2 rounded-xl border-2 border-black bg-[#BEE1E6] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#2D6A4F] animate-pulse"></span>
                                <x-lucide-clipboard-list class="w-4 h-4 stroke-[2.5]" />
                                <span>Meja Kasir & Antrean</span>
                            </a>

                        @elseif ($userRole === 'customer')
                            <!-- CUSTOMER TABS: Beranda, Dashboard, & Booking Shortcut -->
                            <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider transition-all {{ request()->routeIs('home') ? 'bg-[#FAF0CA] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-stone-50 hover:bg-[#FAF0CA]/50 text-stone-700 hover:text-black shadow-[1px_1px_0px_0px_#000]' }}">
                                Beranda
                            </a>
                            <a href="{{ route('customer-dashboard') }}" class="px-3.5 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center gap-1.5 transition-all {{ $isCustomerDashboard ? 'bg-[#A7D7C5] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-stone-50 hover:bg-[#A7D7C5]/50 text-stone-700 hover:text-black shadow-[1px_1px_0px_0px_#000]' }}">
                                <x-lucide-ticket class="w-4 h-4 text-[#2D6A4F] stroke-[2.5]" />
                                <span>Antrean Saya</span>
                            </a>
                            <a href="{{ route('booking') }}" class="px-3.5 py-2 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-1.5 transition-all {{ request()->routeIs('booking') ? 'bg-[#FFBA08] text-black' : 'bg-stone-50 hover:bg-[#FFBA08] text-stone-800' }}">
                                <x-lucide-calendar-plus class="w-4 h-4 stroke-[2.5]" />
                                <span>Pesan Antrean</span>
                            </a>

                        @else
                            <!-- GUEST TABS: Landing & Catalog Links with Dynamic Active Pill -->
                            @if (request()->routeIs('home'))
                                <a 
                                    href="{{ route('home') }}#beranda" 
                                    @click="activeTab = 'beranda'"
                                    :class="activeTab === 'beranda' 
                                        ? 'bg-[#FAF0CA] text-black border-black shadow-[2px_2px_0px_0px_#000] font-black' 
                                        : 'border-transparent text-stone-700 hover:border-black hover:bg-[#FAF0CA]/50 font-bold'"
                                    class="px-3.5 py-2 rounded-xl border-2 font-display text-xs uppercase tracking-wider transition-all"
                                >
                                    Beranda
                                </a>
                                <a 
                                    href="{{ route('home') }}#katalog" 
                                    @click="activeTab = 'katalog'"
                                    :class="activeTab === 'katalog' 
                                        ? 'bg-[#FAF0CA] text-black border-black shadow-[2px_2px_0px_0px_#000] font-black' 
                                        : 'border-transparent text-stone-700 hover:border-black hover:bg-[#FAF0CA]/50 font-bold'"
                                    class="px-3.5 py-2 rounded-xl border-2 font-display text-xs uppercase tracking-wider transition-all"
                                >
                                    Katalog Layanan
                                </a>
                            @else
                                <a 
                                    href="{{ route('home') }}" 
                                    class="px-3.5 py-2 rounded-xl border-2 border-transparent hover:border-black font-display font-bold text-xs uppercase tracking-wider text-stone-700 hover:text-black hover:bg-[#FAF0CA]/50 transition-all"
                                >
                                    Beranda
                                </a>
                                <a 
                                    href="{{ route('home') }}#katalog" 
                                    class="px-3.5 py-2 rounded-xl border-2 border-transparent hover:border-black font-display font-bold text-xs uppercase tracking-wider text-stone-700 hover:text-black hover:bg-[#FAF0CA]/50 transition-all"
                                >
                                    Katalog Layanan
                                </a>
                            @endif
                        @endif
                    </nav>
                </div>

                <!-- ================= RIGHT CLUSTER: DESKTOP ACTIONS + MOBILE HAMBURGER TOGGLE ================= -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    
                    <!-- Desktop Action Buttons (Hidden on Mobile) -->
                    <div class="hidden md:flex items-center gap-3">
                        @guest
                            <!-- Guest Auth Controls -->
                            <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl border-2 border-black bg-white hover:bg-stone-50 text-black font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212] transition-all hover:translate-x-[-1px] hover:translate-y-[-1px]">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-xl border-2 border-black bg-[#A7D7C5] hover:bg-[#2D6A4F] hover:text-[#FAF0CA] text-black font-display font-bold text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212] transition-all hover:translate-x-[-1px] hover:translate-y-[-1px]">
                                Daftar
                            </a>
                            <a href="{{ route('booking') }}" class="px-4 py-2.5 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#121212] transition-all hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-1.5">
                                <x-lucide-calendar-plus class="w-4 h-4 stroke-[2.5]" />
                                <span>Ambil Antrean</span>
                            </a>
                        @else
                            <!-- Authenticated User Profile Pill (Clickable Link to Profile) -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] shadow-[2px_2px_0px_0px_#121212] transition-all hover:translate-x-[-1px] hover:translate-y-[-1px] group {{ request()->routeIs('profile') ? 'bg-[#FAF0CA] ring-2 ring-black' : '' }}" title="Buka Pengaturan Profil Saya">
                                    <!-- Avatar (Photo or Monogram) -->
                                    @if (auth()->user()->avatar_url)
                                        <img 
                                            src="{{ auth()->user()->avatar_url }}" 
                                            alt="{{ $cleanUserName }}" 
                                            class="w-7 h-7 rounded-lg border-2 border-black object-cover shrink-0 shadow-[1px_1px_0px_0px_#000]"
                                        >
                                    @else
                                        <div class="w-7 h-7 rounded-lg border-2 border-black flex items-center justify-center text-xs font-black shrink-0 shadow-[1px_1px_0px_0px_#000] {{ $userRole === 'owner' ? 'bg-[#FFBA08]' : ($userRole === 'staff' ? 'bg-[#BEE1E6]' : 'bg-[#A7D7C5]') }}">
                                            @if ($userRole === 'owner')
                                                <x-lucide-crown class="w-3.5 h-3.5 text-black stroke-[2.5]" />
                                            @elseif ($userRole === 'staff')
                                                <x-lucide-badge-check class="w-3.5 h-3.5 text-black stroke-[2.5]" />
                                            @else
                                                <x-lucide-user class="w-3.5 h-3.5 text-black stroke-[2.5]" />
                                            @endif
                                        </div>
                                    @endif

                                    <!-- User Name & Role Label -->
                                    <div class="flex flex-col text-left leading-tight pr-1">
                                        <span class="font-display font-black text-xs text-black group-hover:text-[#2D6A4F] truncate max-w-[130px]">{{ $cleanUserName }}</span>
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-stone-500">
                                            {{ $userRole === 'owner' ? 'Owner' : ($userRole === 'staff' ? 'Staff' : 'Customer') }} &bull; Profil
                                        </span>
                                    </div>
                                </a>

                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 rounded-xl border-2 border-black bg-white hover:bg-red-50 text-red-600 font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212] transition-all hover:translate-x-[-1px] hover:translate-y-[-1px] flex items-center gap-1.5 cursor-pointer" title="Keluar dari akun">
                                        <x-lucide-log-out class="w-3.5 h-3.5 stroke-[2.5]" />
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile Quick CTA (Direct Antre/Booking for fast mobile access) -->
                    <a href="{{ route('booking') }}" class="md:hidden px-3 py-2 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center gap-1 shrink-0">
                        <x-lucide-calendar-plus class="w-3.5 h-3.5 stroke-[2.5]" />
                        <span>Antre</span>
                    </a>

                    <!-- ================= MOBILE HAMBURGER BUTTON ================= -->
                    <button 
                        type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="md:hidden p-2.5 rounded-xl border-2 border-black bg-white text-black shadow-[2.5px_2.5px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] cursor-pointer transition-all hover:bg-[#FAF0CA] flex items-center justify-center shrink-0"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-label="Buka Menu Navigasi"
                    >
                        <!-- Animated / Dynamic Icon: Hamburger to Close -->
                        <div class="w-5 h-5 flex items-center justify-center relative">
                            <span x-show="!mobileMenuOpen" class="flex items-center justify-center">
                                <x-lucide-menu class="w-5 h-5 stroke-[2.5]" />
                            </span>
                            <span x-show="mobileMenuOpen" x-cloak class="flex items-center justify-center">
                                <x-lucide-x class="w-5 h-5 stroke-[2.5] text-red-600" />
                            </span>
                        </div>
                    </button>

                </div>

            </div>
        </div>

        <!-- ================= MOBILE ACCORDION / DRAWER MENU ================= -->
        <div 
            x-show="mobileMenuOpen" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t-2 border-black bg-[#FDFBF7] shadow-[0px_8px_16px_rgba(0,0,0,0.12)] px-4 py-5"
        >
            <div class="flex flex-col gap-3">

                <!-- Auth Status / User Profile Card (Clickable to Edit Profile) -->
                @auth
                    <a 
                        href="{{ route('profile') }}" 
                        @click="mobileMenuOpen = false"
                        class="p-3 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] shadow-[2px_2px_0px_0px_#000] flex items-center justify-between transition-all group"
                    >
                        <div class="flex items-center gap-3">
                            @if (auth()->user()->avatar_url)
                                <img 
                                    src="{{ auth()->user()->avatar_url }}" 
                                    alt="{{ $cleanUserName }}" 
                                    class="w-10 h-10 rounded-xl border-2 border-black object-cover shrink-0 shadow-[1px_1px_0px_0px_#000]"
                                >
                            @else
                                <div class="w-10 h-10 rounded-xl border-2 border-black flex items-center justify-center font-black text-sm shrink-0 shadow-[1px_1px_0px_0px_#000] {{ $userRole === 'owner' ? 'bg-[#FFBA08]' : ($userRole === 'staff' ? 'bg-[#BEE1E6]' : 'bg-[#A7D7C5]') }}">
                                    @if ($userRole === 'owner')
                                        <x-lucide-crown class="w-4 h-4 text-black stroke-[2.5]" />
                                    @elseif ($userRole === 'staff')
                                        <x-lucide-badge-check class="w-4 h-4 text-black stroke-[2.5]" />
                                    @else
                                        <x-lucide-user class="w-4 h-4 text-black stroke-[2.5]" />
                                    @endif
                                </div>
                            @endif
                            <div class="flex flex-col text-left">
                                <span class="font-display font-black text-sm text-black group-hover:text-[#2D6A4F]">{{ $cleanUserName }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500">
                                    {{ $userRole === 'owner' ? 'Owner / Pemilik' : ($userRole === 'staff' ? 'Staff Groomer & Kasir' : 'Pelanggan PAWSH!') }} &bull; Ubah Profil
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded border border-black text-[9px] font-black uppercase tracking-wider {{ $userRole === 'owner' ? 'bg-[#FFBA08]' : ($userRole === 'staff' ? 'bg-[#BEE1E6]' : 'bg-[#A7D7C5]') }}">
                                {{ strtoupper($userRole) }}
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-stone-500" />
                        </div>
                    </a>
                @endauth

                <!-- Role Specific Navigation Links -->
                <div class="flex flex-col gap-2 pt-1">
                    <span class="text-[10px] font-display font-black uppercase tracking-wider text-stone-500 px-1">Menu Navigasi</span>

                    @if ($userRole === 'owner')
                        <a 
                            href="{{ route('owner') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ $isOwnerPortal ? 'bg-[#FFBA08] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-[#FAF0CA] text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-shield-check class="w-4 h-4 stroke-[2.5]" />
                                <span>Panel Owner</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('workstation') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ $isWorkstation ? 'bg-[#BEE1E6] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-[#BEE1E6]/40 text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-clipboard-list class="w-4 h-4 stroke-[2.5]" />
                                <span>Meja Kasir</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('profile') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ request()->routeIs('profile') ? 'bg-[#FAF0CA] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-stone-50 text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-user-cog class="w-4 h-4 stroke-[2.5]" />
                                <span>Profil & Pengaturan Akun</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                    @elseif ($userRole === 'staff')
                        <a 
                            href="{{ route('workstation') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black bg-[#BEE1E6] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-[#2D6A4F] animate-pulse"></span>
                                <x-lucide-clipboard-list class="w-4 h-4 stroke-[2.5]" />
                                <span>Meja Kasir & Operasional Antrean</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('profile') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ request()->routeIs('profile') ? 'bg-[#BEE1E6] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-stone-50 text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-user-cog class="w-4 h-4 stroke-[2.5]" />
                                <span>Profil & Pengaturan Akun</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                    @elseif ($userRole === 'customer')
                        <a 
                            href="{{ route('home') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ request()->routeIs('home') ? 'bg-[#FAF0CA] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-home class="w-4 h-4 stroke-[2.5]" />
                                <span>Beranda</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('customer-dashboard') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ $isCustomerDashboard ? 'bg-[#A7D7C5] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-[#A7D7C5]/40 text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-ticket class="w-4 h-4 text-[#2D6A4F] stroke-[2.5]" />
                                <span>Antrean & Tiket Saya</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('booking') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-calendar-plus class="w-4 h-4 stroke-[2.5]" />
                                <span>Pesan Antrean</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                        <a 
                            href="{{ route('profile') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black font-display font-black text-xs uppercase tracking-wider flex items-center justify-between transition-all {{ request()->routeIs('profile') ? 'bg-[#A7D7C5] text-black shadow-[2px_2px_0px_0px_#000]' : 'bg-white hover:bg-stone-50 text-stone-800' }}"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-user-cog class="w-4 h-4 stroke-[2.5]" />
                                <span>Profil & Pengaturan Akun</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>

                    @else
                        <!-- Guest Navigation Links with Dynamic Active Pill -->
                        @if (request()->routeIs('home'))
                            <a 
                                href="{{ route('home') }}#beranda" 
                                @click="mobileMenuOpen = false; activeTab = 'beranda'"
                                :class="activeTab === 'beranda' 
                                    ? 'bg-[#FAF0CA] text-black shadow-[2px_2px_0px_0px_#000] font-black' 
                                    : 'bg-white text-stone-800 font-bold hover:bg-[#FAF0CA]/50'"
                                class="px-4 py-3 rounded-xl border-2 border-black font-display text-xs uppercase tracking-wider flex items-center justify-between transition-all"
                            >
                                <div class="flex items-center gap-2.5">
                                    <x-lucide-home class="w-4 h-4 stroke-[2.5]" />
                                    <span>Beranda</span>
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4" />
                            </a>

                            <a 
                                href="{{ route('home') }}#katalog" 
                                @click="mobileMenuOpen = false; activeTab = 'katalog'"
                                :class="activeTab === 'katalog' 
                                    ? 'bg-[#FAF0CA] text-black shadow-[2px_2px_0px_0px_#000] font-black' 
                                    : 'bg-white text-stone-800 font-bold hover:bg-[#FAF0CA]/50'"
                                class="px-4 py-3 rounded-xl border-2 border-black font-display text-xs uppercase tracking-wider flex items-center justify-between transition-all"
                            >
                                <div class="flex items-center gap-2.5">
                                    <x-lucide-sparkles class="w-4 h-4 stroke-[2.5]" />
                                    <span>Katalog Layanan</span>
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4" />
                            </a>
                        @else
                            <a 
                                href="{{ route('home') }}" 
                                @click="mobileMenuOpen = false"
                                class="px-4 py-3 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA]/50 text-stone-800 font-display font-bold text-xs uppercase tracking-wider flex items-center justify-between transition-all"
                            >
                                <div class="flex items-center gap-2.5">
                                    <x-lucide-home class="w-4 h-4 stroke-[2.5]" />
                                    <span>Beranda</span>
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4" />
                            </a>

                            <a 
                                href="{{ route('home') }}#katalog" 
                                @click="mobileMenuOpen = false"
                                class="px-4 py-3 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA]/50 text-stone-800 font-display font-bold text-xs uppercase tracking-wider flex items-center justify-between transition-all"
                            >
                                <div class="flex items-center gap-2.5">
                                    <x-lucide-sparkles class="w-4 h-4 stroke-[2.5]" />
                                    <span>Katalog Layanan</span>
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4" />
                            </a>
                        @endif

                        <a 
                            href="{{ route('booking') }}" 
                            @click="mobileMenuOpen = false"
                            class="px-4 py-3 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#000] flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2.5">
                                <x-lucide-calendar-plus class="w-4 h-4 stroke-[2.5]" />
                                <span>Ambil Antrean Grooming</span>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </a>
                    @endif
                </div>

                <!-- Auth Buttons in Mobile Menu -->
                <div class="pt-2 border-t border-black/15 flex flex-col gap-2">
                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <a 
                                href="{{ route('login') }}" 
                                @click="mobileMenuOpen = false"
                                class="px-4 py-2.5 rounded-xl border-2 border-black bg-white text-black font-display font-black text-xs uppercase tracking-wider text-center shadow-[2px_2px_0px_0px_#000]"
                            >
                                Masuk
                            </a>
                            <a 
                                href="{{ route('register') }}" 
                                @click="mobileMenuOpen = false"
                                class="px-4 py-2.5 rounded-xl border-2 border-black bg-[#A7D7C5] text-black font-display font-black text-xs uppercase tracking-wider text-center shadow-[2px_2px_0px_0px_#000]"
                            >
                                Daftar
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-black bg-red-50 hover:bg-red-100 text-red-600 font-display font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-[2px_2px_0px_0px_#000] cursor-pointer"
                            >
                                <x-lucide-log-out class="w-4 h-4 stroke-[2.5]" />
                                <span>Keluar dari Akun (Logout)</span>
                            </button>
                        </form>
                    @endguest
                </div>

            </div>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    @if (session('success'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="border-2 border-black rounded-xl p-4 bg-[#A7D7C5] shadow-[4px_4px_0px_0px_#121212] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-black text-[#A7D7C5] flex items-center justify-center font-bold">
                        <x-lucide-check class="w-5 h-5 stroke-[2.5]" />
                    </div>
                    <span class="font-bold text-sm text-black">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="border-2 border-black rounded-xl p-4 bg-[#FFBA08] shadow-[4px_4px_0px_0px_#121212] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-black text-[#FFBA08] flex items-center justify-center font-bold">
                        <x-lucide-info class="w-5 h-5 stroke-[2.5]" />
                    </div>
                    <span class="font-bold text-sm text-black">{{ session('warning') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="border-2 border-black rounded-xl p-4 bg-[#F28482] shadow-[4px_4px_0px_0px_#121212] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-black text-[#F28482] flex items-center justify-center font-bold">
                        <x-lucide-alert-triangle class="w-5 h-5 stroke-[2.5]" />
                    </div>
                    <span class="font-bold text-sm text-black">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Page Content -->
    <main class="relative flex-grow">
        {{ $slot }}
    </main>

    <!-- Neo-Brutalism Botanical Footer -->
    <footer class="relative z-10 mt-20 border-t-3 border-black bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand & Template Intro -->
                <div class="space-y-4 md:col-span-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-11 h-11 bg-white border-2 border-black rounded-xl shadow-[2px_2px_0px_0px_#121212] p-1 flex items-center justify-center shrink-0">
                            <img src="{{ asset('assets/PAWSH_logo.svg') }}" alt="PAWSH! Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-display font-black text-2xl tracking-tight text-black leading-none">PAWSH!</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-[#2D6A4F]">AntreanKu Service Template Edition</span>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-stone-600 max-w-md leading-relaxed">
                        Sistem manajemen reservasi dan tiket antrean cerdas berbasis waktu. Didesain menggunakan arsitektur modular AntreanKu untuk bisnis jasa, dilengkapi model 3D interaktif dan proteksi anti double-booking.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border-2 border-black rounded-lg bg-[#FAF0CA] text-black text-[11px] font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
                        <span class="w-2 h-2 rounded-full bg-[#2D6A4F]"></span>
                        <span>09:00 - 21:00 WIB &bull; Buka Setiap Hari</span>
                    </div>
                </div>

                <!-- Col 2: Akses Pelanggan -->
                <div class="space-y-3">
                    <h4 class="font-display font-black text-sm uppercase tracking-wider text-black">Akses Pelanggan</h4>
                    <ul class="space-y-2 text-xs font-bold">
                        <li><a href="{{ route('home') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Beranda & Menu</a></li>
                        <li><a href="{{ route('booking') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Ambil Antrean Baru</a></li>
                        <li><a href="{{ route('customer-dashboard') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Dashboard & Cek Tiket</a></li>
                        <li><a href="{{ route('profile') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Profil Pengguna</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Staf & Owner -->
                <div class="space-y-3">
                    <h4 class="font-display font-black text-sm uppercase tracking-wider text-black">Akses Internal</h4>
                    <ul class="space-y-2 text-xs font-bold">
                        <li><a href="{{ route('workstation') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Konsol Meja Kasir</a></li>
                        <li><a href="{{ route('owner') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Panel Manajemen Owner</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Masuk Akun</a></li>
                        @else
                            <li><a href="{{ route('profile') }}" class="text-stone-700 hover:text-black hover:underline flex items-center gap-1.5"><x-lucide-arrow-right class="w-3.5 h-3.5 text-[#2D6A4F]" /> Kelola Akun</a></li>
                        @endguest
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t-2 border-black flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold text-stone-500">
                <p>&copy; {{ date('Y') }} AntreanKu Platform &bull; Template Bisnis Jasa (PAWSH! Grooming). Tugas Akhir Semester 1.</p>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#2D6A4F] border border-black inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-[#FFBA08] border border-black inline-block"></span>
                    <span class="text-[11px] uppercase tracking-wider font-bold text-black">Soft Neo-Brutalism &bull; Pop Botanical Edition</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- AI Pet Care & Grooming Consultant Modal -->
    <livewire:ai-consultant-modal />

    @livewireScripts
</body>
</html>
