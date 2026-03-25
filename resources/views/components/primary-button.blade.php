<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#8B0000] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg shadow-red-900/10 hover:scale-105 active:scale-95']) }}>
    {{ $slot }}
</button>
