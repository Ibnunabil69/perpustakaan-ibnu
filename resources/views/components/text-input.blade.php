@props(['disabled' => false])

@php
    $isPassword = $attributes->get('type') === 'password';
@endphp

<div class="relative w-full" @if($isPassword) x-data="{ show: false }" @endif>
    <input {{ $disabled ? 'disabled' : '' }} 
        @if($isPassword) :type="show ? 'text' : 'password'" @endif
        {!! $attributes->merge(['class' => 'block w-full ' . ($isPassword ? 'pr-10 ' : 'pr-3 ') . 'px-3 py-2 bg-white border border-amber-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all disabled:bg-gray-50 disabled:text-gray-400']) !!}>
    
    @if($isPassword)
        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-500 transition-colors focus:outline-none">
            <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
        </button>
    @endif
</div>
