<div>
    <!-- Floating AI Consultant Pill (Bottom Right) -->
    <div class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-40 print:hidden">
        <button 
            type="button" 
            wire:click="openModal" 
            class="px-4 py-3 rounded-2xl border-3 border-black bg-[#2D6A4F] text-[#FAF0CA] font-display font-black text-xs uppercase tracking-wider shadow-[4px_4px_0px_0px_#000] hover:bg-[#FFBA08] hover:text-black hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_0px_#000] active:translate-x-[2px] active:translate-y-[2px] active:shadow-[1px_1px_0px_0px_#000] transition-all flex items-center gap-2.5 cursor-pointer group"
            title="Buka Konsultan AI Anabul"
        >
            <span class="w-7 h-7 rounded-lg border-2 border-black bg-[#FFBA08] text-black flex items-center justify-center shrink-0 group-hover:bg-[#2D6A4F] group-hover:text-[#FFBA08] transition-colors">
                <x-lucide-sparkles class="w-4 h-4 stroke-[2.5]" />
            </span>
            <div class="text-left leading-tight hidden xs:block sm:block">
                <span class="block text-[9px] font-bold text-[#A7D7C5] group-hover:text-black uppercase tracking-widest">Tanya AI</span>
                <span class="font-display font-black text-xs">Konsultasi Anabul</span>
            </div>
            <span class="w-2 h-2 rounded-full bg-[#FFBA08] animate-ping ml-0.5"></span>
        </button>
    </div>

    <!-- Modal Konsultasi AI -->
    @if ($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto" @keydown.escape.window="$wire.closeModal">
            <div class="border-3 border-black rounded-3xl bg-white max-w-lg w-full p-6 sm:p-7 shadow-[8px_8px_0px_0px_#000] my-auto max-h-[90vh] flex flex-col relative overflow-hidden">
                <!-- Header Modal -->
                <div class="flex items-center justify-between pb-3 border-b-2 border-black shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl border-2 border-black bg-[#FFBA08] flex items-center justify-center shadow-[2px_2px_0px_0px_#000] shrink-0">
                            <x-lucide-sparkles class="w-5 h-5 text-black stroke-[2.5]" />
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded border border-black bg-[#A7D7C5] text-[9px] font-black uppercase tracking-wider text-black">
                                <span>Powered by Google Gemini</span>
                            </div>
                            <h3 class="font-display font-black text-lg text-black mt-0.5">Konsultan AI Grooming</h3>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeModal" 
                        class="w-8 h-8 rounded-xl border-2 border-black bg-white hover:bg-[#F28482] hover:text-white flex items-center justify-center font-black text-lg transition-all cursor-pointer shadow-[2px_2px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px]"
                    >
                        &times;
                    </button>
                </div>

                <!-- Body Modal (Scrollable) -->
                <div class="space-y-4 overflow-y-auto flex-1 pr-1 pt-4 min-h-0">
                    <!-- Penjelasan Singkat -->
                    <p class="text-xs font-semibold text-stone-600 leading-relaxed">
                        Ceritakan kondisi bulu, kulit, atau perilaku anabul Anda. AI kami akan merekomendasikan paket perawatan dan groomer yang paling sesuai dari database studio.
                    </p>

                    <!-- Quick Preset Chips -->
                    <div class="space-y-1.5">
                        <span class="text-[10px] font-black uppercase tracking-wider text-black block">Pilih Keluhan Cepat:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($presetChips as $chip)
                                <button 
                                    type="button" 
                                    wire:click="selectPreset('{{ $chip }}')"
                                    class="px-2.5 py-1 rounded-lg border-2 border-black bg-[#FAF0CA] hover:bg-[#FFBA08] text-black font-bold text-[10px] shadow-[1px_1px_0px_0px_#000] active:translate-x-[1px] active:translate-y-[1px] transition-all cursor-pointer text-left"
                                >
                                    {{ $chip }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Input Keluhan -->
                    <form wire:submit.prevent="askAi" class="space-y-3 pt-2">
                        <div class="space-y-1">
                            <label class="block text-xs font-black uppercase tracking-wider text-black">
                                Ceritakan Kondisi Anabul:
                            </label>
                            <textarea 
                                wire:model="petQuery" 
                                rows="3" 
                                placeholder="Contoh: Kucing persia saya bulunya gimbal parah di leher dan kulitnya gatal-gatal merah..."
                                class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border-2 border-black bg-[#FDFBF7] shadow-[2px_2px_0px_0px_#000] focus:shadow-[4px_4px_0px_0px_#000] focus:outline-hidden"
                            ></textarea>
                            @error('petQuery')
                                <span class="text-[11px] font-bold text-[#F28482] block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            @if ($result)
                                <button 
                                    type="button" 
                                    wire:click="resetConsultation" 
                                    class="px-3 py-2 rounded-xl border-2 border-black bg-white hover:bg-stone-100 text-stone-700 font-bold text-xs uppercase cursor-pointer"
                                >
                                    Reset
                                </button>
                            @endif

                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl border-2 border-black bg-[#FFBA08] text-black font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#2D6A4F] hover:text-[#FFBA08] active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center justify-center gap-2 cursor-pointer ml-auto"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove class="flex items-center gap-1.5">
                                    <x-lucide-sparkles class="w-4 h-4 stroke-[2.5]" />
                                    <span>Analisis dengan AI</span>
                                </span>
                                <span wire:loading class="flex items-center gap-1.5">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                    <span>Memproses Kondisi...</span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Loading State Indicator -->
                    <div wire:loading wire:target="askAi, selectPreset" class="w-full p-4 border-2 border-black rounded-2xl bg-[#BEE1E6]/40 text-center space-y-2">
                        <div class="w-8 h-8 rounded-full border-2 border-black border-t-transparent animate-spin mx-auto"></div>
                        <p class="text-xs font-bold text-black">AI sedang menganalisis gejala & mencocokkan jadwal studio...</p>
                    </div>

                    <!-- AI Recommendation Results -->
                    @if ($result && ! $errors->any())
                        <div wire:loading.remove wire:target="askAi, selectPreset" class="border-2 border-black rounded-2xl bg-[#FAF0CA] p-4 sm:p-5 shadow-[4px_4px_0px_0px_#000] space-y-4 pt-4 mt-2">
                            <div class="flex items-center justify-between pb-2 border-b-2 border-black">
                                <span class="font-display font-black text-xs uppercase tracking-wider text-[#2D6A4F] flex items-center gap-1.5">
                                    <x-lucide-sparkles class="w-3.5 h-3.5" />
                                    <span>Hasil Analisis AI ({{ $result['source'] }})</span>
                                </span>
                            </div>

                            <!-- Diagnosis -->
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-600 block">Diagnosa Kondisi:</span>
                                <p class="text-xs font-bold text-black leading-snug">
                                    {{ $result['diagnosis'] }}
                                </p>
                            </div>

                            <!-- Rekomendasi Layanan & Groomer -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div class="p-2.5 rounded-xl border-2 border-black bg-white space-y-1">
                                    <span class="text-[9px] font-black uppercase text-[#2D6A4F] block">Rekomendasi Paket:</span>
                                    <strong class="font-display font-black text-xs text-black block">{{ $result['recommended_service_name'] }}</strong>
                                    <span class="text-[10px] font-bold text-stone-700">Rp {{ number_format($result['recommended_service_price'], 0, ',', '.') }}</span>
                                </div>

                                <div class="p-2.5 rounded-xl border-2 border-black bg-white space-y-1">
                                    <span class="text-[9px] font-black uppercase text-[#2D6A4F] block">Groomer Spesialis:</span>
                                    <strong class="font-display font-black text-xs text-black block">{{ $result['recommended_staff_name'] }}</strong>
                                    <span class="text-[10px] font-semibold text-stone-600">Siap Melayani</span>
                                </div>
                            </div>

                            <!-- Alasan Klinis -->
                            <div class="p-2.5 rounded-xl border border-black bg-white/70 space-y-1 text-xs">
                                <span class="text-[10px] font-bold text-black uppercase tracking-wider block">Mengapa Paket Ini?</span>
                                <p class="text-stone-700 font-medium leading-relaxed text-[11px]">
                                    {{ $result['reasoning'] }}
                                </p>
                            </div>

                            <!-- Home Care Tip -->
                            <div class="p-2.5 rounded-xl border border-black bg-[#D8F3DC] text-xs space-y-1">
                                <span class="text-[10px] font-bold text-[#2D6A4F] uppercase tracking-wider block">Tips Perawatan di Rumah:</span>
                                <p class="text-stone-800 font-medium text-[11px] leading-relaxed">
                                    {{ $result['home_care_tip'] }}
                                </p>
                            </div>

                            <!-- CTA Apply & Book -->
                            <div class="pt-2">
                                <button 
                                    type="button" 
                                    wire:click="applyAndBook"
                                    class="w-full py-3 rounded-xl border-2 border-black bg-[#2D6A4F] text-[#FAF0CA] font-display font-black text-xs uppercase tracking-wider shadow-[3px_3px_0px_0px_#000] hover:bg-[#FFBA08] hover:text-black active:translate-x-[1px] active:translate-y-[1px] transition-all flex items-center justify-center gap-2 cursor-pointer"
                                >
                                    <x-lucide-calendar-plus class="w-4 h-4 stroke-[2.5]" />
                                    <span>Pilih Rekomendasi Ini & Ambil Jadwal</span>
                                    <x-lucide-arrow-right class="w-4 h-4 stroke-[2.5]" />
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
