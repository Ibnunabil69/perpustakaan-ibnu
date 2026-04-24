@props(['type' => 'button', 'variant' => 'primary', 'href' => null, 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-1.5 rounded-lg font-medium transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-3.5 py-2 text-sm',
        'lg' => 'px-4 py-2 text-sm',
        'icon' => 'w-[38px] h-[38px] text-sm',
        default => 'px-3.5 py-2 text-sm',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-sm shadow-amber-200/50 focus:ring-amber-400',
        'secondary' => 'bg-white hover:bg-amber-50 text-gray-700 border border-amber-200 focus:ring-amber-300 shadow-sm',
        'danger' => 'bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white shadow-sm focus:ring-rose-400',
        'outline' => 'bg-transparent border border-amber-200 text-gray-700 hover:bg-amber-50 focus:ring-amber-300',
        'action' => 'bg-transparent text-gray-400 hover:text-amber-600 hover:bg-amber-50',
        'danger-action' => 'bg-transparent text-gray-400 hover:text-rose-600 hover:bg-rose-50',
        default => 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-sm shadow-amber-200/50 focus:ring-amber-400',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
        {{ $slot }}
    </button>
@endif
