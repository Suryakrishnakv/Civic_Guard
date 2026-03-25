<x-authenticated-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <h3 class="text-xl font-bold mb-4 font-eagle-lake">Welcome , {{ auth()->user()->name }}!</h3>
                    <p class="text-slate-600">
                        You are logged in as <span class="font-bold text-[#8B0000] capitalize px-2 py-1 bg-red-50 rounded-md border border-red-100">{{ auth()->user()->role ?? 'user' }}</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
