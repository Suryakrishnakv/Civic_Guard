<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -my-4 px-4 sm:px-6 lg:px-8 py-8 overflow-hidden group">
            <!-- Premium Background with Mesh & Gradients -->
            <div class="absolute inset-0 bg-white"></div>
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-red-50/40"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-black text-[#8B0000] uppercase tracking-widest bg-red-50 px-2.5 py-0.5 rounded shadow-sm border border-red-100">
                            Municipal Intelligence
                        </span>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs font-semibold text-slate-500">Executive Command</span>
                    </div>
                    <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                        Advanced Municipal Analytics Dashboard
                    </h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">Real-time civic KPIs, department efficiency, SLA tracking and longitudinal trends</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Main Dashboard
                    </a>
                    <a href="{{ route('admin.reports.map') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        Live GIS Map
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 relative min-h-screen bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
            <x-municipal-analytics :analytics="$analytics" />
        </div>
    </div>
</x-authenticated-layout>
