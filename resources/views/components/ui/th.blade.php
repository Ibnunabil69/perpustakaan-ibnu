@props(['align' => 'left'])
@php
    $alignmentClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp
<th {{ $attributes->merge(['class' => "px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider $alignmentClass"]) }}>
    {{ $slot }}
</th>
