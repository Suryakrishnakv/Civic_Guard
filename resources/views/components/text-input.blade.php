@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-200 bg-indigo-50/50 text-slate-900 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-full shadow-sm transition-all duration-200 ease-in-out hover:border-slate-300 font-bold px-6']) !!}>
