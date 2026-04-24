@props(['value' => ''])

<div @click="select('{{ $value }}')" 
     data-value="{{ $value }}"
     class="px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 cursor-pointer transition-colors flex items-center justify-between group/opt">
    <span class="truncate">{{ $slot }}</span>
    <i class="ri-check-line text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity" x-show="value == '{{ $value }}'"></i>
</div>
