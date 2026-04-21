<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm shadow-amber-200/50 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 active:scale-[0.98] transition-all duration-200']) }}>
    {{ $slot }}
</button>
