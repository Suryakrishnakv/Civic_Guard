@props([
    'id' => 'gis-map',
    'height' => 'h-[460px] sm:h-[540px] lg:h-[600px]',
    'departments' => [],
    'categories' => [],
    'canToggleHeatmap' => false,
    'defaultDepartment' => null,
    'lockDepartment' => false,
    'showLegend' => true,
    'showFilters' => true,
    'title' => 'Interactive Complaint GIS Map',
    'subtitle' => 'Real-time spatial monitoring of civic complaints'
])

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden relative group"
     x-data="gisMapComponent({
         mapId: '{{ $id }}',
         dataUrl: '{{ route('reports.mapData') }}',
         canHeatmap: {{ $canToggleHeatmap ? 'true' : 'false' }},
         defaultDepartment: '{{ $defaultDepartment ?? '' }}',
         lockDepartment: {{ $lockDepartment ? 'true' : 'false' }}
     })"
     x-init="initMap()">

    <!-- Header & Controls Bar -->
    <div class="p-5 sm:p-7 border-b border-slate-100 bg-gradient-to-r from-red-50/40 via-white to-indigo-50/40 relative">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#8B0000] text-white flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold font-eagle-lake text-slate-900 tracking-tight">
                            {{ $title }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $subtitle }}</p>
                    </div>
                </div>
            </div>

            <!-- View Controls & Quick Actions -->
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                <!-- Heatmap Toggle (Admin / Officers) -->
                <template x-if="canHeatmap">
                    <div class="inline-flex p-1 bg-slate-100 rounded-2xl border border-slate-200 text-xs font-bold shadow-inner">
                        <button type="button"
                                @click="setViewMode('markers')"
                                :class="viewMode === 'markers' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3.5 py-1.5 rounded-xl transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#8B0000]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                            <span>Markers</span>
                        </button>
                        <button type="button"
                                @click="setViewMode('heatmap')"
                                :class="viewMode === 'heatmap' ? 'bg-[#8B0000] text-white shadow-md shadow-red-900/30' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3.5 py-1.5 rounded-xl transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                            <span>Density Heatmap</span>
                        </button>
                    </div>
                </template>

                <!-- Locate Me Button (enableHighAccuracy GPS) -->
                <button type="button"
                        @click="locateUser()"
                        :disabled="isLocating"
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 hover:text-[#8B0000] rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                    <svg class="w-4 h-4 text-emerald-600" :class="{ 'animate-spin': isLocating }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span x-text="isLocating ? 'Locating...' : 'Locate Me'"></span>
                </button>

                <!-- Reset / Fit All Markers Button -->
                <button type="button"
                        @click="resetView()"
                        class="p-2 bg-white border border-slate-200 text-slate-500 hover:text-slate-800 rounded-xl hover:bg-slate-50 transition-all shadow-sm"
                        title="Fit All Markers to View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>
            </div>
        </div>

        @if($showFilters)
        <!-- Multi-Parameter Filter Toolbar (Requirement 4) -->
        <div class="mt-5 pt-4 border-t border-slate-200/60 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Location Search (Nominatim) -->
            <div class="relative lg:col-span-1">
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           @keydown.enter.prevent="searchLocation()"
                           placeholder="Search place / landmark..."
                           class="w-full pl-9 pr-8 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all placeholder:text-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button type="button"
                            x-show="searchQuery"
                            @click="searchQuery = ''; searchLocation(true)"
                            class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Department Filter -->
            @if(!$lockDepartment && count($departments) > 0)
            <div>
                <select x-model="filters.department"
                        @change="fetchReports()"
                        class="w-full py-2 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] text-slate-700">
                    <option value="all">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Category Filter -->
            <div>
                <select x-model="filters.category"
                        @change="fetchReports()"
                        class="w-full py-2 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] text-slate-700">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter with Exact Color Coding (Requirement 2) -->
            <div>
                <select x-model="filters.status"
                        @change="fetchReports()"
                        class="w-full py-2 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] text-slate-700">
                    <option value="all">All Statuses</option>
                    <option value="Pending">🔴 Pending (Red)</option>
                    <option value="In Progress">🔵 In Progress (Blue)</option>
                    <option value="Repaired">🟢 Repaired (Green)</option>
                    <option value="Rejected">⚪ Rejected (Grey)</option>
                </select>
            </div>

            <!-- Submission Date Filter (Requirement 4) -->
            <div>
                <select x-model="filters.date"
                        @change="fetchReports()"
                        class="w-full py-2 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] text-slate-700">
                    <option value="all">All Submission Dates</option>
                    <option value="today">Submitted Today</option>
                    <option value="week">Past 7 Days</option>
                    <option value="month">Past 30 Days</option>
                    <option value="year">Past Year</option>
                </select>
            </div>
        </div>
        @endif
    </div>

    <!-- Map Canvas & Overlays -->
    <div class="relative w-full {{ $height }} bg-slate-100 overflow-hidden">
        <!-- Leaflet Map Container (Requirement 1 & 9) -->
        <div id="{{ $id }}" class="w-full h-full z-10"></div>

        <!-- Loading Spinner -->
        <div x-show="isLoading"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-white/70 backdrop-blur-xs z-30 flex items-center justify-center">
            <div class="flex items-center gap-3 px-5 py-3 bg-white rounded-2xl shadow-xl border border-slate-100">
                <svg class="animate-spin w-5 h-5 text-[#8B0000]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span class="text-xs font-bold text-slate-800">Querying PostgreSQL Data...</span>
            </div>
        </div>

        <!-- Safe Handling for Zero or Missing GPS Complaints (Requirement 8) -->
        <div x-show="!isLoading && validMarkersCount === 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 bg-slate-50/85 backdrop-blur-xs z-25 flex flex-col items-center justify-center p-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-slate-400 mb-3 border border-slate-200 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <h4 class="text-sm font-bold text-slate-800">No Complaints with Valid Coordinates Found</h4>
            <p class="text-xs text-slate-500 max-w-sm mt-1">
                No complaint records with valid GPS coordinates match your current filters or department permissions.
            </p>
            <button type="button" @click="resetFilters()" class="mt-4 px-4 py-2 bg-[#8B0000] text-white rounded-xl text-xs font-bold hover:bg-red-800 transition-colors shadow-sm">
                Reset Filters
            </button>
        </div>

        <!-- Color Legend (Requirement 2): Pending (Red), In Progress (Blue), Repaired (Green), Rejected (Grey) -->
        @if($showLegend)
        <div class="absolute bottom-4 left-4 z-20 pointer-events-auto">
            <div class="bg-white/95 backdrop-blur-md px-4 py-3 rounded-2xl shadow-xl border border-slate-200/80 text-xs font-bold text-slate-700 flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-1.5 border-r border-slate-200 pr-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                    <span class="text-slate-900 font-extrabold" x-text="validMarkersCount + ' On Map'">0 On Map</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/50"></span>
                    <span>Pending (<span x-text="stats.pending || 0">0</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></span>
                    <span>In Progress (<span x-text="stats.in_progress || 0">0</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                    <span>Repaired (<span x-text="stats.repaired || 0">0</span>)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-gray-500 shadow-sm shadow-gray-500/50"></span>
                    <span>Rejected (<span x-text="stats.rejected || 0">0</span>)</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function gisMapComponent(config) {
    return {
        mapId: config.mapId,
        dataUrl: config.dataUrl,
        canHeatmap: config.canHeatmap,
        lockDepartment: config.lockDepartment,
        defaultDepartment: config.defaultDepartment,
        
        map: null,
        markersLayer: null,
        heatLayer: null,
        userLocationMarker: null,
        
        viewMode: 'markers', // 'markers' or 'heatmap'
        isLoading: false,
        isLocating: false,
        searchQuery: '',
        validMarkersCount: 0,
        
        filters: {
            department: config.defaultDepartment || 'all',
            category: 'all',
            status: 'all',
            date: 'all',
            ward_id: 'all'
        },
        
        reports: [],
        stats: { total: 0, pending: 0, in_progress: 0, repaired: 0, rejected: 0 },

        initMap() {
            this.$nextTick(() => {
                // Initialize Leaflet Map (Requirement 1 & 9)
                // Center on default city coordinates: 10.0538, 76.3567
                this.map = L.map(this.mapId, {
                    center: [10.0538, 76.3567],
                    zoom: 12,
                    zoomControl: false,
                    scrollWheelZoom: true
                });

                L.control.zoom({ position: 'topright' }).addTo(this.map);

                // OpenStreetMap Standard Tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map);

                this.markersLayer = L.layerGroup().addTo(this.map);

                // Dynamically fetch complaints from PostgreSQL via Laravel route (Requirement 7)
                this.fetchReports();
            });
        },

        fetchReports() {
            this.isLoading = true;
            
            const params = new URLSearchParams();
            if (this.filters.department && this.filters.department !== 'all') {
                params.append('department', this.filters.department);
            }
            if (this.filters.category && this.filters.category !== 'all') {
                params.append('category', this.filters.category);
            }
            if (this.filters.status && this.filters.status !== 'all') {
                params.append('status', this.filters.status);
            }
            if (this.filters.ward_id && this.filters.ward_id !== 'all') {
                params.append('ward_id', this.filters.ward_id);
            }
            if (this.filters.date && this.filters.date !== 'all') {
                params.append('date', this.filters.date);
            }

            fetch(`${this.dataUrl}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.reports = data.reports || [];
                this.stats = data.stats || { total: 0, pending: 0, in_progress: 0, repaired: 0, rejected: 0 };
                this.renderData();
                this.isLoading = false;
            })
            .catch(err => {
                console.error("GIS Map fetch error:", err);
                this.isLoading = false;
            });
        },

        renderData() {
            if (!this.map) return;

            this.markersLayer.clearLayers();
            if (this.heatLayer) {
                this.map.removeLayer(this.heatLayer);
                this.heatLayer = null;
            }

            if (this.viewMode === 'heatmap') {
                this.renderHeatmap();
            } else {
                this.renderMarkers();
            }
        },

        renderMarkers() {
            const bounds = [];
            let count = 0;

            this.reports.forEach(report => {
                // Requirement 8: Handle missing GPS coordinates and invalid locations safely
                if (
                    report.latitude === null || report.longitude === null ||
                    report.latitude === undefined || report.longitude === undefined ||
                    isNaN(Number(report.latitude)) || isNaN(Number(report.longitude)) ||
                    Number(report.latitude) < -90 || Number(report.latitude) > 90 ||
                    Number(report.longitude) < -180 || Number(report.longitude) > 180 ||
                    (Number(report.latitude) === 0 && Number(report.longitude) === 0)
                ) {
                    return;
                }

                const lat = parseFloat(report.latitude);
                const lng = parseFloat(report.longitude);
                bounds.push([lat, lng]);
                count++;

                // Requirement 2: Different colored markers:
                // Pending (Red), In Progress (Blue), Repaired (Green), Rejected (Grey)
                const icon = this.createStatusIcon(report.status);
                const marker = L.marker([lat, lng], { icon: icon });

                // Requirements 3 & 5: Rich popup with Report ID, category, description, address, status, photographs & View Details link
                const popupContent = this.createPopupContent(report);
                marker.bindPopup(popupContent, {
                    maxWidth: 320,
                    minWidth: 260,
                    autoPan: true,
                    className: 'civic-gis-popup'
                });

                this.markersLayer.addLayer(marker);
            });

            this.validMarkersCount = count;

            // Fit bounds smoothly if valid points exist
            if (bounds.length > 0 && !this.userLocationMarker) {
                this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 15 });
            }
        },

        renderHeatmap() {
            if (typeof L.heatLayer !== 'function') {
                console.warn('L.heatLayer is not loaded.');
                return;
            }

            const heatPoints = [];
            this.reports.forEach(r => {
                if (
                    r.latitude !== null && r.longitude !== null &&
                    !isNaN(Number(r.latitude)) && !isNaN(Number(r.longitude)) &&
                    (Number(r.latitude) !== 0 || Number(r.longitude) !== 0)
                ) {
                    let intensity = 0.5;
                    if (r.status === 'Pending') intensity = 0.9;
                    if (r.status === 'In Progress') intensity = 0.7;
                    if (r.status === 'Repaired') intensity = 0.3;
                    heatPoints.push([parseFloat(r.latitude), parseFloat(r.longitude), intensity]);
                }
            });

            this.validMarkersCount = heatPoints.length;

            this.heatLayer = L.heatLayer(heatPoints, {
                radius: 28,
                blur: 18,
                maxZoom: 16,
                gradient: {
                    0.2: '#3b82f6', // Blue
                    0.4: '#10b981', // Green
                    0.6: '#f59e0b', // Amber
                    0.9: '#ef4444'  // Red
                }
            }).addTo(this.map);
        },

        // Requirement 2:
        // Pending = Red (#ef4444)
        // In Progress = Blue (#3b82f6)
        // Repaired = Green (#10b981)
        // Rejected = Grey (#6b7280)
        createStatusIcon(status) {
            let color = '#ef4444'; // Red for Pending
            let ringColor = 'rgba(239, 68, 68, 0.3)';
            let iconSvg = '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';

            if (status === 'In Progress') {
                color = '#3b82f6'; // Blue for In Progress
                ringColor = 'rgba(59, 130, 246, 0.3)';
                iconSvg = '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
            } else if (status === 'Repaired') {
                color = '#10b981'; // Green for Repaired
                ringColor = 'rgba(16, 185, 129, 0.3)';
                iconSvg = '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
            } else if (status === 'Rejected') {
                color = '#6b7280'; // Grey for Rejected
                ringColor = 'rgba(107, 114, 128, 0.3)';
                iconSvg = '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }

            const html = `
                <div class="relative flex items-center justify-center transform -translate-x-1/2 -translate-y-full cursor-pointer hover:scale-115 transition-transform duration-200">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-lg border-2 border-white" style="background-color: ${color}; box-shadow: 0 4px 14px ${ringColor}">
                        ${iconSvg}
                    </div>
                    <div class="w-2 h-2 rotate-45 border-r border-b border-white -mt-1 mx-auto" style="background-color: ${color}"></div>
                </div>
            `;

            return L.divIcon({
                className: 'custom-gis-pin',
                html: html,
                iconSize: [32, 38],
                iconAnchor: [16, 38],
                popupAnchor: [0, -38]
            });
        },

        // Requirements 3 & 5:
        // Popup containing: Report ID, category, description, address, status, complaint photographs and "View Details" link
        createPopupContent(report) {
            let statusPill = '<span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-800 border border-red-200">Pending</span>';
            if (report.status === 'In Progress') {
                statusPill = '<span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-200">In Progress</span>';
            } else if (report.status === 'Repaired') {
                statusPill = '<span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Repaired</span>';
            } else if (report.status === 'Rejected') {
                statusPill = '<span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-800 border border-gray-200">Rejected</span>';
            }

            // Photographs display (Requirement 3)
            let photosHtml = '';
            const allPhotos = (report.photos && report.photos.length > 0) ? report.photos : (report.photo_url ? [report.photo_url] : []);
            if (allPhotos.length > 0) {
                photosHtml = `
                    <div class="mt-2.5">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Photographs (${allPhotos.length})</div>
                        <div class="grid grid-cols-2 gap-1.5 rounded-xl overflow-hidden">
                            ${allPhotos.slice(0, 2).map(p => `
                                <a href="${p}" target="_blank" class="block aspect-video bg-slate-100 overflow-hidden group/img relative">
                                    <img src="${p}" alt="Complaint Photo" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-300">
                                </a>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            const addressText = report.address || report.location || 'Location specified on map';

            return `
                <div class="p-1 text-slate-800 font-sans">
                    <!-- Report ID and Status Pill -->
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-extrabold text-[#8B0000] font-mono">${report.case_number || ('Report #' + report.id)}</span>
                        </div>
                        ${statusPill}
                    </div>

                    <!-- Category & Department -->
                    <div class="mt-2">
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="text-sm font-bold text-slate-900 leading-tight">${report.category}</h4>
                            ${report.ward_name ? `<span class="text-[9px] font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 shrink-0">${report.ward_name}</span>` : ''}
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">${report.department || 'Municipal'}</p>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">${report.full_description || report.description || 'No description provided.'}</p>

                    <!-- Address -->
                    <div class="mt-2.5 pt-2 border-t border-slate-100 text-[11px] text-slate-600 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span class="line-clamp-2">${addressText}</span>
                    </div>

                    ${photosHtml}

                    <!-- Footer & Requirement 5: View Details Link -->
                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-medium">${report.time_ago || report.created_at}</span>
                        <a href="${report.detail_url}" class="inline-flex items-center gap-1 px-3 py-1 bg-[#8B0000] hover:bg-red-800 text-white rounded-lg text-xs font-bold transition-colors shadow-xs">
                            <span>View Details</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            `;
        },

        setViewMode(mode) {
            if (this.viewMode === mode) return;
            this.viewMode = mode;
            this.renderData();
        },

        locateUser() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            this.isLocating = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.isLocating = false;
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    if (this.userLocationMarker) {
                        this.map.removeLayer(this.userLocationMarker);
                    }

                    const userIcon = L.divIcon({
                        className: 'user-gps-pulse',
                        html: `
                            <div class="relative flex items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-blue-400 opacity-75"></span>
                                <div class="w-4 h-4 rounded-full bg-blue-600 border-2 border-white shadow-lg"></div>
                            </div>
                        `,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    this.userLocationMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.map);
                    this.userLocationMarker.bindPopup('<b class="text-xs">Your Current GPS Location</b>').openPopup();

                    this.map.flyTo([lat, lng], 15, { animate: true, duration: 1.2 });
                },
                (err) => {
                    this.isLocating = false;
                    console.warn("GPS error:", err);
                    alert("Could not access your location. Please check browser permissions.");
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        },

        searchLocation(clearOnly = false) {
            if (clearOnly || !this.searchQuery.trim()) {
                this.searchQuery = '';
                return;
            }
            this.isLoading = true;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`)
                .then(res => res.json())
                .then(data => {
                    this.isLoading = false;
                    if (data && data.length > 0) {
                        const first = data[0];
                        const lat = parseFloat(first.lat);
                        const lng = parseFloat(first.lon);
                        this.map.flyTo([lat, lng], 14, { animate: true });
                    } else {
                        alert('No locations found for "' + this.searchQuery + '"');
                    }
                })
                .catch(err => {
                    this.isLoading = false;
                    console.error("Geocoding search error:", err);
                });
        },

        resetFilters() {
            this.filters.department = this.defaultDepartment || 'all';
            this.filters.category = 'all';
            this.filters.status = 'all';
            this.filters.date = 'all';
            this.searchQuery = '';
            this.fetchReports();
        },

        resetView() {
            if (this.reports.length > 0) {
                const bounds = this.reports
                    .filter(r => r.latitude && r.longitude && (r.latitude != 0 || r.longitude != 0))
                    .map(r => [parseFloat(r.latitude), parseFloat(r.longitude)]);
                if (bounds.length > 0) {
                    this.map.fitBounds(bounds, { padding: [50, 50] });
                }
            } else {
                this.map.setView([10.0538, 76.3567], 12);
            }
        }
    };
}
</script>

<style>
.civic-gis-popup .leaflet-popup-content-wrapper {
    border-radius: 1.25rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(226, 232, 240, 0.9);
    padding: 0.25rem;
}
.civic-gis-popup .leaflet-popup-tip {
    background: white;
}
</style>
@endpush
