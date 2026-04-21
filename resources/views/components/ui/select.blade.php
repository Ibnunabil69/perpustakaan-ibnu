@props(['disabled' => false, 'icon' => null])

<div class="relative">
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="{{ $icon }} text-gray-400"></i>
        </div>
    @endif
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full ' . ($icon ? 'pl-9 ' : 'pl-3 ') . 'pr-8 py-2 bg-white border border-amber-200 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all disabled:bg-gray-50']) !!}>
        {{ $slot }}
    </select>
</div>
