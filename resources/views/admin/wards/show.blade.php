<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-2xl shadow-sm border border-red-100 text-[#8B0000]">
                        <span class="font-black text-2xl font-mono">#{{ $ward->ward_number }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                                Municipal Ward #{{ $ward->ward_number }}
                            </span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $efficiency >= 70 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $efficiency }}% Efficiency
                            </span>
                        </div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight mt-1">
                            {{ $ward->name }}
                        </h2>
                        <p class="text-xs text-slate-500 font-medium">{{ $ward->description ?? 'Municipal geographic zone and field maintenance sector.' }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.wards.inspectionReport', $ward->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Ward Inspection Sheet (PDF)
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.wards.edit', $ward->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Boundaries
                        </a>
                    @endif
                    <a href="{{ route('admin.wards.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:text-[#8B0000] rounded-xl text-xs font-bold shadow-sm transition-all">
                        &larr; All Wards
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Ward KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Total -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Complaints</span>
                    <span class="text-2xl font-black text-slate-900 mt-2 block">{{ $totalCount }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">In this ward</span>
                </div>

                <!-- Pending -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-red-500 block">Pending Review</span>
                    <span class="text-2xl font-black text-red-600 mt-2 block">{{ $pendingCount }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Awaiting action</span>
                </div>

                <!-- In Progress -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-500 block">Under Action</span>
                    <span class="text-2xl font-black text-blue-600 mt-2 block">{{ $inProgressCount }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Field team deployed</span>
                </div>

                <!-- Repaired -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-500 block">Repaired</span>
                    <span class="text-2xl font-black text-emerald-600 mt-2 block">{{ $repairedCount }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Successfully resolved</span>
                </div>

                <!-- Efficiency -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-500 block">Resolution Rate</span>
                    <span class="text-2xl font-black text-indigo-600 mt-2 block">{{ $efficiency }}%</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Efficiency score</span>
                </div>

                <!-- SLA Overdue -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-500 block">SLA Overdue</span>
                    <span class="text-2xl font-black {{ $overdueCount > 0 ? 'text-amber-600' : 'text-slate-400' }} mt-2 block">{{ $overdueCount }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Requires escalation</span>
                </div>
            </div>

            <!-- Ward GIS Interactive Map & Heatmap -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold font-eagle-lake text-slate-900">Ward Boundary & Complaints Geographic Visualizer</h3>
                        <p class="text-xs text-slate-500">Polygon boundaries with color-coded complaint markers and incident density heatmap.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Heatmap Toggle Button -->
                        <button type="button" id="toggle-heatmap-btn" class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold border border-indigo-200 transition-colors">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            <span id="heatmap-btn-text">Show Complaint Heatmap</span>
                        </button>

                        <!-- Legend -->
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Pending</span>
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> In Progress</span>
                            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Repaired</span>
                        </div>
                    </div>
                </div>

                <div class="relative w-full h-[450px] rounded-2xl border border-slate-200 overflow-hidden shadow-inner">
                    <div id="single-ward-map" class="w-full h-full"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Complaints Register for this Ward (8 cols) -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold font-eagle-lake text-slate-900">Ward Complaints Register</h3>
                            <p class="text-xs text-slate-500">Detailed list of civic reports recorded inside this ward polygon.</p>
                        </div>

                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.wards.show', $ward->id) }}" class="flex flex-wrap items-center gap-2">
                            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border-slate-200 rounded-xl py-1.5 px-3 font-medium">
                                <option value="all">All Statuses</option>
                                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Repaired" {{ request('status') === 'Repaired' ? 'selected' : '' }}>Repaired</option>
                                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>

                            <select name="category" onchange="this.form.submit()" class="text-xs bg-slate-50 border-slate-200 rounded-xl py-1.5 px-3 font-medium max-w-[140px]">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto px-6 pb-6">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Case ID</th>
                                    <th class="py-3 px-4">Category & Location</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Officer</th>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4 text-right">View</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($reports as $rep)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                            #{{ str_pad($rep->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-slate-900">{{ $rep->category }}</div>
                                            <div class="text-slate-400 text-[11px] line-clamp-1 mt-0.5">{{ $rep->location }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase
                                                {{ $rep->status === 'Pending' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $rep->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $rep->status === 'Repaired' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $rep->status === 'Rejected' ? 'bg-slate-100 text-slate-800' : '' }}">
                                                {{ $rep->status }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                            {{ $rep->assignedOfficer?->name ?? 'Unassigned' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap text-[11px]">
                                            {{ $rep->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <a href="{{ route('reports.show', $rep->id) }}" class="font-bold text-indigo-600 hover:text-indigo-900 hover:underline">
                                                Details &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400">
                                            No complaints matching this filter in Ward #{{ $ward->ward_number }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>

                <!-- Assigned Officers Card & Ward Overview (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Assigned Officers -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Assigned Field Officers
                            </h3>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                {{ $ward->officers->count() }} Officers
                            </span>
                        </div>

                        <div class="space-y-3">
                            @forelse($ward->officers as $officer)
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                            {{ substr($officer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">{{ $officer->name }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $officer->department }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold text-indigo-700 bg-indigo-100/70 px-2 py-0.5 rounded-md">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs italic">
                                    No officers assigned to this ward.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Geographic Boundary Specifications -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-slate-900 border-b pb-3">Geographic Boundary Metadata</h3>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">Ward Centroid (Lat, Lng):</span>
                                <span class="font-mono font-bold text-slate-800">{{ $ward->center_lat }}, {{ $ward->center_lng }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">Boundary Vertices:</span>
                                <span class="font-bold text-indigo-600">{{ count($ward->boundaries ?? []) }} Points</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">Average Resolution:</span>
                                <span class="font-bold text-slate-800">{{ $avgResolutionHours }} hrs</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-slate-500 font-medium">Algorithm:</span>
                                <span class="font-bold text-emerald-700">Ray-Casting GIS In-Polygon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const wardBoundaries = {!! json_encode($ward->boundaries ?? []) !!};
        const mapReports = {!! json_encode($mapReports) !!};
        const centerLat = {{ $ward->center_lat ?? 10.0538 }};
        const centerLng = {{ $ward->center_lng ?? 76.3567 }};

        // Initialize Map
        const map = L.map('single-ward-map', {
            center: [centerLat, centerLng],
            zoom: 14
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Draw Ward Boundary Polygon
        let polygonLayer = null;
        if (wardBoundaries.length >= 3) {
            polygonLayer = L.polygon(wardBoundaries, {
                color: '#4f46e5',
                weight: 3,
                fillColor: '#6366f1',
                fillOpacity: 0.15
            }).addTo(map);

            map.fitBounds(polygonLayer.getBounds(), { padding: [30, 30] });
        }

        // Marker Colors
        const colorMap = {
            'Pending': '#ef4444',
            'In Progress': '#3b82f6',
            'Repaired': '#10b981',
            'Rejected': '#6b7280'
        };

        const markersLayer = L.layerGroup().addTo(map);
        const heatPoints = [];

        mapReports.forEach(rep => {
            const color = colorMap[rep.status] || '#ef4444';
            heatPoints.push([rep.latitude, rep.longitude, rep.status === 'Pending' ? 1.0 : 0.6]);

            const markerIcon = L.divIcon({
                className: 'custom-complaint-pin',
                html: `
                    <div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.4);"></div>
                `,
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });

            const marker = L.marker([rep.latitude, rep.longitude], { icon: markerIcon }).addTo(markersLayer);

            const popupHtml = `
                <div class="p-2 font-sans" style="min-width: 200px;">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-slate-800">#${String(rep.id).padStart(5, '0')}</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded text-white" style="background-color: ${color};">${rep.status}</span>
                    </div>
                    <div class="font-bold text-xs text-slate-900">${rep.category}</div>
                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">${rep.description}</p>
                    <div class="text-[10px] text-slate-400 mt-1">${rep.location}</div>
                    ${rep.photo ? `<img src="${rep.photo}" class="w-full h-24 object-cover rounded mt-2 border">` : ''}
                    <a href="${rep.url}" class="mt-2 block w-full py-1 bg-slate-900 text-white text-center font-bold text-[11px] rounded hover:bg-slate-800">
                        View Details &rarr;
                    </a>
                </div>
            `;
            marker.bindPopup(popupHtml);
        });

        // Heatmap Toggle Layer
        let heatLayer = null;
        let isHeatmapActive = false;
        const toggleBtn = document.getElementById('toggle-heatmap-btn');
        const btnText = document.getElementById('heatmap-btn-text');

        toggleBtn.addEventListener('click', function () {
            if (isHeatmapActive) {
                if (heatLayer) map.removeLayer(heatLayer);
                markersLayer.addTo(map);
                btnText.textContent = 'Show Complaint Heatmap';
                isHeatmapActive = false;
            } else {
                if (!heatLayer) {
                    heatLayer = L.heatLayer(heatPoints, {
                        radius: 35,
                        blur: 20,
                        maxZoom: 17,
                        gradient: { 0.2: 'blue', 0.5: 'lime', 0.8: 'yellow', 1.0: 'red' }
                    });
                }
                map.addLayer(heatLayer);
                btnText.textContent = 'Hide Heatmap';
                isHeatmapActive = true;
            }
        });
    });
    </script>
    @endpush
</x-authenticated-layout>
