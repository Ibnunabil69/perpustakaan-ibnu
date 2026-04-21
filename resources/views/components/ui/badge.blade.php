@props(['color' => 'default'])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold';
    
    $colorClasses = match($color) {
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200/60',
        'amber' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200/60',
        'rose' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200/60',
        'indigo', 'teal' => 'bg-teal-50 text-teal-700 ring-1 ring-teal-200/60',
        default => 'bg-gray-50 text-gray-700 ring-1 ring-gray-200/60',
    };
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }}>
    {{ $slot }}
</span>
