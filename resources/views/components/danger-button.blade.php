<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-rose-500 to-red-500 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:from-rose-600 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-1 active:scale-[0.98] transition-all duration-200']) }}>
    {{ $slot }}
</button>
