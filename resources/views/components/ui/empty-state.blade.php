@props(['icon' => 'ri-inbox-line', 'title' => 'Data tidak ditemukan', 'description' => ''])

<tr>
    <td {{ $attributes->merge(['class' => 'px-4 py-12 text-center']) }}>
        <div class="flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <i class="{{ $icon }} text-2xl text-amber-400"></i>
            </div>
            <h3 class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</h3>
            @if($description)
                <p class="text-xs text-gray-400">{{ $description }}</p>
            @endif
            
            @isset($action)
                <div class="mt-4">
                    {{ $action }}
                </div>
            @endisset
        </div>
    </td>
</tr>
