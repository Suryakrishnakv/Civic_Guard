<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-64 h-64 bg-indigo-500 rounded-full blur-[80px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-500"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-2xl shadow-sm border border-red-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            {{ __('Municipal Ward Management') }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Define municipal boundaries, assign officers, and monitor geographic resolution efficiency.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.wards.export.excel') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.wards.export.pdf') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print PDF Report
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.wards.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#8B0000] hover:bg-red-800 text-white rounded-xl text-xs font-bold shadow-md shadow-red-900/10 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Define New Ward
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold">{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Wards -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Municipal Wards</span>
                        <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900">{{ $totalWards }}</span>
                        <span class="text-xs font-bold text-slate-500">Active Sectors</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">100% boundary polygons defined</p>
                </div>

                <!-- Total Ward Reports -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Ward Complaints</span>
                        <div class="p-2.5 rounded-xl bg-red-50 text-[#8B0000]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900">{{ $totalReports }}</span>
                        <span class="text-xs font-bold text-slate-500">Total Cases</span>
                    </div>
                    <div class="mt-2 flex items-center gap-3 text-[11px] text-slate-500 font-medium">
                        <span class="text-amber-600 font-bold">{{ $totalPending }} Pending</span>
                        <span>•</span>
                        <span class="text-blue-600 font-bold">{{ $totalInProgress }} In Progress</span>
                    </div>
                </div>

                <!-- Overall Efficiency -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Resolution Efficiency</span>
                        <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-600">{{ $overallEfficiency }}%</span>
                        <span class="text-xs font-bold text-slate-500">City Avg</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $overallEfficiency) }}%"></div>
                    </div>
                </div>

                <!-- Top Issue Ward -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Highest Volume Ward</span>
                        <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-base font-bold text-slate-900 truncate">
                            {{ $topWard ? ('Ward #' . $topWard->ward_number . ' - ' . $topWard->name) : 'No data' }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1 font-semibold">
                            {{ $topWard ? $topWard->reports->count() . ' recorded complaints' : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Leaflet Geographic GIS Overview Map of All Wards -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold font-eagle-lake text-slate-900">Municipal Boundary & Geographic Coverage Map</h3>
                        <p class="text-xs text-slate-500">Interactive polygon boundaries and point-in-polygon verification zones across the city.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                            {{ $totalWards }} Defined Polygons
                        </span>
                    </div>
                </div>

                <div class="relative w-full h-[400px] rounded-2xl border border-slate-200 overflow-hidden shadow-inner">
                    <div id="wards-overview-map" class="w-full h-full"></div>
                </div>
            </div>

            <!-- Wards Register / Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold font-eagle-lake text-slate-900">Municipal Wards Register</h3>
                        <p class="text-xs text-slate-500">Comprehensive list of wards with performance metrics and assigned field officers.</p>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.wards.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ward number or name..." class="pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all w-64">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-colors">
                            Filter
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.wards.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-red-700 font-bold">Clear</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider">
                                <th class="py-4 px-6">Ward #</th>
                                <th class="py-4 px-6">Ward Name & Description</th>
                                <th class="py-4 px-6">Assigned Officers</th>
                                <th class="py-4 px-6 text-center">Complaints (P / IP / R)</th>
                                <th class="py-4 px-6 text-center">Efficiency Rate</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($wards as $ward)
                                @php
                                    $repCount = $ward->reports->count();
                                    $pCount = $ward->reports->where('status', 'Pending')->count();
                                    $ipCount = $ward->reports->where('status', 'In Progress')->count();
                                    $rCount = $ward->reports->where('status', 'Repaired')->count();
                                    $rejCount = $ward->reports->where('status', 'Rejected')->count();
                                    $eff = $repCount > 0 ? round(($rCount / $repCount) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <td class="py-4 px-6 font-mono font-bold text-slate-900 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 font-black text-slate-800">
                                            #{{ $ward->ward_number }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('admin.wards.show', $ward->id) }}" class="font-bold text-slate-900 text-sm hover:text-[#8B0000] transition-colors block">
                                            {{ $ward->name }}
                                        </a>
                                        <p class="text-slate-500 text-[11px] mt-0.5 line-clamp-1">
                                            {{ $ward->description ?? 'No detailed description provided.' }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @forelse($ward->officers as $officer)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                    {{ $officer->name }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 italic text-[11px]">No officers assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                                            <span class="font-bold text-slate-900" title="Total Complaints">{{ $repCount }} Total</span>
                                            <span class="text-slate-300">|</span>
                                            <span class="text-amber-600 font-bold" title="Pending">{{ $pCount }} P</span>
                                            <span class="text-blue-600 font-bold" title="In Progress">{{ $ipCount }} IP</span>
                                            <span class="text-emerald-600 font-bold" title="Repaired">{{ $rCount }} R</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="inline-block">
                                            <span class="font-black text-sm {{ $eff >= 70 ? 'text-emerald-600' : ($eff >= 40 ? 'text-amber-600' : 'text-slate-600') }}">
                                                {{ $eff }}%
                                            </span>
                                            <div class="w-20 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden mx-auto">
                                                <div class="h-1.5 rounded-full {{ $eff >= 70 ? 'bg-emerald-500' : ($eff >= 40 ? 'bg-amber-500' : 'bg-slate-400') }}" style="width: {{ min(100, $eff) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.wards.show', $ward->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="View Ward GIS Map & Reports">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.wards.inspectionReport', $ward->id) }}" target="_blank" class="p-2 text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50 rounded-lg transition-colors" title="Officer Inspection Report">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            </a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('admin.wards.edit', $ward->id) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Boundaries & Officers">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.wards.destroy', $ward->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete Ward #{{ $ward->ward_number }}? Associated complaints will become unassigned.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" title="Delete Ward">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">No municipal wards found</p>
                                        <p class="text-xs text-slate-400 mt-1">Start by defining geographic boundary polygons for municipal sectors.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const geoJsonData = {!! json_encode($geoJsonFeatures) !!};

        // Initialize Map
        const map = L.map('wards-overview-map', {
            zoomControl: true,
            scrollWheelZoom: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const colors = ['#4f46e5', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0284c7', '#be185d'];
        let bounds = L.latLngBounds([]);

        geoJsonData.forEach((feature, idx) => {
            const ward = feature.properties;
            const color = colors[idx % colors.length];

            const layer = L.geoJSON(feature, {
                style: {
                    color: color,
                    weight: 2.5,
                    opacity: 0.9,
                    fillColor: color,
                    fillOpacity: 0.18
                }
            }).addTo(map);

            // Bind Popup
            const officerList = ward.officers.length > 0 ? ward.officers.join(', ') : 'None assigned';
            const popupHtml = `
                <div class="p-2 font-sans" style="min-width: 220px;">
                    <div class="flex items-center justify-between border-b pb-1.5 mb-1.5">
                        <span class="text-xs font-black uppercase text-indigo-700">Ward #${ward.ward_number}</span>
                        <span class="text-[11px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">${ward.efficiency}% Efficiency</span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-900">${ward.name}</h4>
                    <p class="text-xs text-slate-500 mt-1">${ward.description || ''}</p>
                    <div class="mt-2 text-xs text-slate-700 space-y-0.5 bg-slate-50 p-2 rounded-lg">
                        <div><strong>Total Reports:</strong> ${ward.total_reports}</div>
                        <div><strong>Repaired:</strong> ${ward.repaired_reports}</div>
                        <div><strong>Officers:</strong> ${officerList}</div>
                    </div>
                    <a href="${ward.show_url}" class="mt-2 block w-full py-1.5 px-3 bg-[#8B0000] text-white text-center font-bold text-xs rounded-lg hover:bg-red-800">
                        View Ward GIS Map &rarr;
                    </a>
                </div>
            `;
            layer.bindPopup(popupHtml);

            // Add center marker
            if (ward.center_lat && ward.center_lng) {
                const labelIcon = L.divIcon({
                    className: 'ward-center-badge',
                    html: `
                        <div class="px-2 py-1 rounded-full bg-slate-900/90 text-white text-[10px] font-bold whitespace-nowrap shadow-md border border-white/40 flex items-center gap-1 cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: ${color}"></span>
                            Ward #${ward.ward_number}
                        </div>
                    `,
                    iconSize: [60, 20],
                    iconAnchor: [30, 10]
                });
                const centerMarker = L.marker([ward.center_lat, ward.center_lng], { icon: labelIcon }).addTo(map);
                centerMarker.bindPopup(popupHtml);
            }

            bounds.extend(layer.getBounds());
        });

        if (geoJsonData.length > 0 && bounds.isValid()) {
            map.fitBounds(bounds, { padding: [30, 30] });
        } else {
            map.setView([10.0538, 76.3567], 12);
        }
    });
    </script>
    @endpush
</x-authenticated-layout>
