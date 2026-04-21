@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full px-3 py-2 bg-white border border-amber-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all disabled:bg-gray-50 disabled:text-gray-400']) }}>
