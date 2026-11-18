<x-layouts.app :title="'403 - Akses Ditolak | PAWSH! Studio'">
    <div class="max-w-xl mx-auto px-4 py-16 sm:py-24 text-center space-y-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md border-2 border-black bg-[#FAF0CA] text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_#000]">
            <x-lucide-shield-alert class="w-4 h-4 text-[#F28482] stroke-[2.5]" />
            <span>Galat 403: Batas Otorisasi</span>
        </div>

        <div class="border-3 border-black rounded-3xl bg-white p-8 sm:p-12 shadow-[8px_8px_0px_0px_#121212] space-y-5 relative overflow-hidden">
            <div class="w-20 h-20 rounded-2xl border-3 border-black bg-[#F28482] mx-auto flex items-center justify-center font-display font-black text-3xl text-black shadow-[4px_4px_0px_0px_#000]">
                403
            </div>

            <div class="space-y-2">
                <h1 class="font-display font-black text-2xl sm:text-3xl text-black">
                    Akses Khusus / Tidak Diizinkan
                </h1>
                <p class="text-xs sm:text-sm font-semibold text-stone-600 leading-relaxed max-w-md mx-auto">
                    Halaman ini dikhususkan untuk peran pengguna tertentu (seperti Staf Kasir atau Owner). Silakan masuk menggunakan akun yang sesuai.
                </p>
            </div>

            <div class="pt-4 border-t-2 border-black flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border-2 border-black bg-white hover:bg-[#FAF0CA] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center gap-2">
                    <x-lucide-home class="w-4 h-4" />
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl border-2 border-black bg-[#FFBA08] hover:bg-[#2D6A4F] hover:text-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center gap-2">
                    <x-lucide-log-in class="w-4 h-4 stroke-[2.5]" />
                    <span>Ganti Akun Login</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
