<div class="max-w-md mx-auto px-4 py-12 relative">
    <!-- Header -->
    <div class="text-center space-y-2 mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#A7D7C5] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
            <x-lucide-user-plus class="w-4 h-4 text-[#2D6A4F]" />
            <span class="text-black">Pendaftaran Pelanggan</span>
        </div>
        <h1 class="font-display text-3xl font-black text-black tracking-tight">
            Buat Akun Pelanggan
        </h1>
        <p class="text-xs font-semibold text-stone-600">
            Daftar hanya dengan Nama, Nomor WhatsApp, dan Kata Sandi tanpa email ribet.
        </p>
    </div>

    <!-- Register Card -->
    <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-8 shadow-[8px_8px_0px_0px_#121212] space-y-6 relative overflow-hidden">
        <div class="absolute -top-3 -right-3 w-16 h-16 text-[#FFBA08]/20 pointer-events-none rotate-12">
            <x-lucide-paw-print class="w-full h-full" />
        </div>

        <form wire:submit.prevent="registerCustomer" class="space-y-4 relative z-10">
            <!-- Nama Lengkap -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Nama Lengkap Pemilik *
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="Contoh: Budi Santoso"
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
                @error('name')
                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nomor WhatsApp -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Nomor WhatsApp / HP Aktif *
                </label>
                <input 
                    type="text" 
                    wire:model="phone" 
                    placeholder="Contoh: 081234567890"
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
                @error('phone')
                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Kata Sandi -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Buat Kata Sandi *
                </label>
                <input 
                    type="password" 
                    wire:model="password" 
                    placeholder="Minimal 6 karakter..."
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
                @error('password')
                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Kata Sandi -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Ulangi Kata Sandi *
                </label>
                <input 
                    type="password" 
                    wire:model="password_confirmation" 
                    placeholder="Ulangi kata sandi di atas..."
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] transition-all hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <x-lucide-check-circle-2 class="w-4 h-4 stroke-[2.5]" />
                    <span>Daftar & Masuk Otomatis</span>
                </button>
            </div>
        </form>

        <!-- Login Link -->
        <div class="text-center pt-2 border-t-2 border-stone-200 text-xs font-semibold text-stone-700 relative z-10">
            <span>Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" class="font-bold text-black underline ml-1 hover:text-[#2D6A4F]">
                Masuk ke Akun
            </a>
        </div>
    </div>
</div>
