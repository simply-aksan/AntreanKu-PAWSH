<div class="max-w-md mx-auto px-4 py-12 relative">
    <!-- Header -->
    <div class="text-center space-y-2 mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#FAF0CA] font-black text-xs uppercase tracking-wider shadow-[2px_2px_0px_0px_#121212]">
            <x-lucide-lock class="w-4 h-4 text-[#2D6A4F]" />
            <span class="text-black">Autentikasi AntreanKu</span>
        </div>
        <h1 class="font-display text-3xl font-black text-black tracking-tight">
            Masuk ke Akun
        </h1>
        <p class="text-xs font-semibold text-stone-600">
            Masuk dengan nomor WhatsApp atau Email untuk mengakses dashboard Anda.
        </p>
    </div>

    <!-- Login Card -->
    <div class="border-3 border-black rounded-3xl bg-white p-6 sm:p-8 shadow-[8px_8px_0px_0px_#121212] space-y-6 relative overflow-hidden">
        <div class="absolute -top-3 -right-3 w-16 h-16 text-[#A7D7C5]/20 pointer-events-none rotate-12">
            <x-lucide-paw-print class="w-full h-full" />
        </div>

        <form wire:submit.prevent="authenticate" class="space-y-4 relative z-10">
            <!-- Login Field (Phone or Email) -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Nomor WhatsApp / HP atau Email *
                </label>
                <input 
                    type="text" 
                    wire:model="login" 
                    placeholder="Contoh: 081234567890 atau email@test.com"
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
                @error('login')
                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-1.5">
                <label class="block font-bold uppercase tracking-wider text-black text-xs">
                    Kata Sandi (Password) *
                </label>
                <input 
                    type="password" 
                    wire:model="password" 
                    placeholder="Masukkan kata sandi..."
                    class="w-full text-sm font-medium px-4 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                >
                @error('password')
                    <span class="text-xs font-bold text-[#F28482] block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs font-bold pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-stone-800">
                    <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded border-2 border-black accent-[#2D6A4F]">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 rounded-xl border-3 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] transition-all hover:bg-[#2D6A4F] hover:text-[#FFBA08] hover:translate-x-[-1px] hover:translate-y-[-1px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] flex items-center justify-center gap-2 cursor-pointer"
                >
                    <x-lucide-log-in class="w-4 h-4 stroke-[2.5]" />
                    <span>Masuk Sekarang</span>
                </button>
            </div>
        </form>

        <!-- Demo Accounts Hint Card with 1-Click Autofill -->
        <div class="p-3.5 border-2 border-black rounded-xl bg-[#FAF0CA] space-y-2 text-xs relative z-10 shadow-[2px_2px_0px_0px_#000]">
            <div class="flex items-center justify-between">
                <span class="font-display font-black uppercase text-[#2D6A4F] text-[10px]">Autofill Akun Demo Pengujian:</span>
                <span class="text-[9px] font-bold text-stone-500 uppercase">Klik untuk isi</span>
            </div>
            <div class="grid grid-cols-3 gap-1.5 pt-1">
                <button 
                    type="button" 
                    wire:click="fillCredentials('owner@pawsh.test')"
                    class="px-2 py-1.5 rounded-lg border-2 border-black bg-white hover:bg-[#FFBA08] text-black font-display font-black text-[10px] uppercase shadow-[1px_1px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer text-center"
                >
                    Owner
                </button>
                <button 
                    type="button" 
                    wire:click="fillCredentials('sari@pawsh.test')"
                    class="px-2 py-1.5 rounded-lg border-2 border-black bg-white hover:bg-[#A7D7C5] text-black font-display font-black text-[10px] uppercase shadow-[1px_1px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer text-center"
                >
                    Kasir
                </button>
                <button 
                    type="button" 
                    wire:click="fillCredentials('081234567890')"
                    class="px-2 py-1.5 rounded-lg border-2 border-black bg-white hover:bg-[#BEE1E6] text-black font-display font-black text-[10px] uppercase shadow-[1px_1px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer text-center"
                >
                    Pelanggan
                </button>
            </div>
            <div class="text-[10px] font-medium text-stone-600 text-center pt-0.5">
                Kata sandi default: <code class="font-bold text-black">password</code>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-2 border-t-2 border-stone-200 text-xs font-semibold text-stone-700 relative z-10">
            <span>Belum memiliki akun pelanggan?</span>
            <a href="{{ route('register') }}" class="font-bold text-black underline ml-1 hover:text-[#2D6A4F]">
                Daftar Pelanggan Baru
            </a>
        </div>
    </div>
</div>
