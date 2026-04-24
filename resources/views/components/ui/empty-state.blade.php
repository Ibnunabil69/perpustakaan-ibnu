@props(['icon' => 'ri-inbox-line', 'title' => 'Data tidak ditemukan', 'description' => ''])

<tr>
    <td {{ $attributes->merge(['class' => 'px-4 py-20 text-center']) }}>
        <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
            <div class="relative mb-6">
                <!-- Soft Glow -->
                <div class="absolute inset-x-0 -bottom-4 h-8 bg-amber-400/20 blur-xl rounded-full"></div>
                
                <!-- Icon Container -->
                <div class="relative w-20 h-20 rounded-2xl bg-gradient-to-br from-white to-amber-50 border border-amber-200/50 shadow-sm flex items-center justify-center overflow-hidden ring-8 ring-amber-50/50">
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-amber-200/20 rounded-full blur-md"></div>
                    <i class="{{ $icon }} text-4xl text-amber-500"></i>
                </div>
            </div>
            <h3 class="text-base font-semibold text-gray-800 tracking-tight">{{ $title }}</h3>
            @if($description)
                <p class="text-sm text-gray-400 mt-2 leading-relaxed">{{ $description }}</p>
            @else
                <p class="text-sm text-gray-400 mt-2 leading-relaxed italic">Sepertinya belum ada data transaksi yang tersimpan saat ini.</p>
            @endif
            
            @isset($action)
                <div class="mt-6">
                    {{ $action }}
                </div>
            @endisset
        </div>
    </td>
</tr>
