<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/60 shadow-sm px-8 py-6 group">
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-64 h-64 bg-indigo-500 rounded-full blur-[80px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-500"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/3 w-64 h-64 bg-[#8B0000] rounded-full blur-[80px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-500"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Interactive GIS Command Center
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">
                            @if(Auth::user()->isOfficer())
                                Monitoring reports for: <span class="font-bold text-[#8B0000]">{{ Auth::user()->department }}</span>
                            @elseif(Auth::user()->isAdmin())
                                City-wide municipal asset monitoring, spatial hotspots & complaint clustering
                            @else
                                Community issue explorer & municipal tracking
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ Auth::user()->role === 'citizen' ? route('dashboard') : route('admin.dashboard') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm overflow-hidden">
                        <span class="absolute inset-0 w-full h-full bg-slate-50 opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                        <span class="relative flex items-center">
                            <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Dashboard
                        </span>
                    </a>
                    @if(Auth::user()->role === 'citizen')
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-800 transition-colors shadow-md shadow-red-900/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Report Issue
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-6">
            <x-gis-map 
                id="fullscreen-gis-map"
                height="h-[620px] sm:h-[720px] lg:h-[780px]"
                :departments="$departments"
                :categories="$categories"
                :canToggleHeatmap="Auth::user()->isAdmin() || Auth::user()->isOfficer()"
                :lockDepartment="Auth::user()->isOfficer()"
                :defaultDepartment="Auth::user()->isOfficer() ? Auth::user()->department : 'all'"
                title="City Infrastructure GIS Map"
                subtitle="Live OpenStreetMap spatial view with real-time status filtering and density heatmap"
            />
        </div>
    </div>
</x-authenticated-layout>
