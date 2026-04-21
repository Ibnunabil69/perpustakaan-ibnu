<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-3.5 py-2 bg-white border border-amber-200 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-1 disabled:opacity-50 active:scale-[0.98] transition-all duration-200']) }}>
    {{ $slot }}
</button>
