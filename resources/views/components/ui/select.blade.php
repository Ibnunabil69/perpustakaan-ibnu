@props(['disabled' => false, 'icon' => null, 'name' => '', 'value' => '', 'placeholder' => 'Pilih...'])

<div x-data="{ 
    open: false, 
    value: '{{ $value }}',
    label: '{{ $placeholder }}',
    init() {
        // Coba cari label segera
        this.updateLabel();
        
        // Coba lagi secara berkala sampai ketemu (maksimal 1 detik)
        let attempts = 0;
        const checkInterval = setInterval(() => {
            this.updateLabel();
            attempts++;
            if (this.label !== '{{ $placeholder }}' || attempts > 10) {
                clearInterval(checkInterval);
            }
        }, 100);

        this.$watch('value', () => this.updateLabel());
    },
    updateLabel() {
        if (!this.$refs.options) return;
        
        const options = this.$refs.options.querySelectorAll('[data-value]');
        for (let opt of options) {
            if (opt.getAttribute('data-value') == this.value) {
                this.label = opt.innerText.trim();
                return;
            }
        }
        
        // Fallback jika tidak ketemu dan value kosong
        if (this.value === '') {
            this.label = '{{ $placeholder }}';
        }
    },
    select(val) {
        this.value = val;
        this.open = false;
        setTimeout(() => {
            this.$refs.hiddenInput.dispatchEvent(new Event('change'));
        }, 50);
    }
}" 
class="relative group w-full" 
@click.away="open = false">
    
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="{{ $name }}" x-ref="hiddenInput" :value="value" {{ $attributes->only('onchange') }}>

    <!-- Trigger Button -->
    <button type="button" 
        @click="open = !open"
        {{ $disabled ? 'disabled' : '' }}
        class="relative w-full flex items-center justify-between bg-white border border-amber-200 rounded-lg px-3.5 py-[8px] text-sm text-gray-700 font-medium hover:border-amber-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 shadow-sm transition-all disabled:bg-gray-50 disabled:cursor-not-allowed">
        
        <div class="flex items-center gap-2.5 overflow-hidden">
            @if($icon)
                <i class="{{ $icon }} text-amber-500/60 group-focus-within:text-amber-500 transition-colors"></i>
            @endif
            <span x-text="label" class="truncate"></span>
        </div>

        <i class="ri-arrow-down-s-line text-amber-400 text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-amber-600' : ''"></i>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        x-ref="options"
        class="absolute z-[100] mt-2 w-full bg-white border border-amber-200 rounded-lg shadow-xl shadow-amber-900/10 py-1.5 max-h-60 overflow-auto scrollbar-hide"
        style="display: none;">
        
        {{ $slot }}
    </div>
</div>

{{-- Untuk penggunaan di dalam slot:
<div @click="select('value')" data-value="value" class="px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 cursor-pointer transition-colors">
    Label
</div>
--}}
