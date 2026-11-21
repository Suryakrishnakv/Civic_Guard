<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-3xl border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-2xl shadow-sm border border-red-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            {{ __('Define Municipal Ward') }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Use the interactive map drawer to define geographic polygon boundaries and assign officers.</p>
                    </div>
                </div>

                <a href="{{ route('admin.wards.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:text-[#8B0000] hover:border-[#8B0000] rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Wards
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="wardDrawerHandler()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.wards.store') }}" @submit="prepareSubmit($event)">
                @csrf
                <input type="hidden" name="boundaries" id="boundaries" :value="JSON.stringify(points)">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Map Boundary Drawer -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        Interactive Boundary Drawer
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Click directly on the map to place polygon boundary points (at least 3 vertices required).</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="undoPoint()" :disabled="points.length === 0" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl disabled:opacity-40 transition-colors">
                                        Undo Point
                                    </button>
                                    <button type="button" @click="clearPoints()" :disabled="points.length === 0" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold rounded-xl disabled:opacity-40 transition-colors">
                                        Clear All
                                    </button>
                                </div>
                            </div>

                            <!-- Map Canvas -->
                            <div class="relative w-full h-[450px] rounded-2xl border-2 border-indigo-200/60 overflow-hidden shadow-inner">
                                <div id="ward-drawer-map" class="w-full h-full"></div>

                                <div class="absolute top-3 left-3 z-[400] bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-md border border-slate-200 text-[11px] font-bold text-slate-700 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" :class="points.length >= 3 ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse'"></span>
                                    <span x-text="points.length + ' Vertices Added (' + (points.length >= 3 ? 'Valid Polygon' : (3 - points.length) + ' more needed') + ')'"></span>
                                </div>
                            </div>

                            <!-- Vertices Table / Pills -->
                            <div>
                                <span class="text-xs font-bold text-slate-600 block mb-2">Boundary Vertices Coordinates:</span>
                                <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto p-2 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                                    <template x-for="(pt, idx) in points" :key="idx">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white border border-slate-200 rounded-lg font-mono text-[11px] shadow-2xs">
                                            <span class="font-bold text-indigo-600" x-text="'P' + (idx + 1) + ':'"></span>
                                            <span x-text="pt[0].toFixed(5) + ', ' + pt[1].toFixed(5)"></span>
                                            <button type="button" @click="removePoint(idx)" class="text-red-500 hover:text-red-700 font-bold ml-1">&times;</button>
                                        </span>
                                    </template>
                                    <div x-show="points.length === 0" class="text-slate-400 italic text-xs py-2 px-1">
                                        No vertices placed yet. Click on the map to begin drawing ward boundaries.
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('boundaries_array')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Ward Details Form -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-6">
                            <h3 class="text-base font-bold text-slate-900 border-b pb-3">Ward Specification</h3>

                            <!-- Ward Number -->
                            <div>
                                <x-input-label for="ward_number" :value="__('Ward Number')" class="font-bold text-slate-700" />
                                <input type="number" id="ward_number" name="ward_number" required min="1" value="{{ old('ward_number', $nextWardNumber) }}" class="mt-1 block w-full border-slate-200 rounded-xl focus:ring-[#8B0000] focus:border-[#8B0000] text-sm font-bold bg-indigo-50/30">
                                <x-input-error :messages="$errors->get('ward_number')" class="mt-2" />
                            </div>

                            <!-- Ward Name -->
                            <div>
                                <x-input-label for="name" :value="__('Ward Name')" class="font-bold text-slate-700" />
                                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g., Kalamassery Central Ward" class="mt-1 block w-full border-slate-200 rounded-xl focus:ring-[#8B0000] focus:border-[#8B0000] text-sm">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description / Municipal Zone Coverage')" class="font-bold text-slate-700" />
                                <textarea id="description" name="description" rows="3" placeholder="Key landmarks, residential sectors, or boundaries..." class="mt-1 block w-full border-slate-200 rounded-xl focus:ring-[#8B0000] focus:border-[#8B0000] text-sm">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Officer Assignment -->
                            <div>
                                <x-input-label :value="__('Assign Municipal Officers')" class="font-bold text-slate-700 mb-2" />
                                <p class="text-xs text-slate-500 mb-3">Select officers responsible for this ward's complaints and field inspections.</p>
                                
                                <div class="space-y-2 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-slate-50">
                                    @forelse($officers as $officer)
                                        <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200/80 hover:bg-slate-50 cursor-pointer transition-colors">
                                            <input type="checkbox" name="officers[]" value="{{ $officer->id }}" {{ in_array($officer->id, old('officers', [])) ? 'checked' : '' }} class="rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                            <div class="flex-1 text-xs">
                                                <div class="font-bold text-slate-900">{{ $officer->name }}</div>
                                                <div class="text-slate-500 text-[11px]">{{ $officer->department }}</div>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">No municipal officers available.</p>
                                    @endforelse
                                </div>
                                <x-input-error :messages="$errors->get('officers')" class="mt-2" />
                            </div>

                            <!-- Form Actions -->
                            <div class="pt-4 border-t flex items-center justify-end gap-3">
                                <a href="{{ route('admin.wards.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 uppercase">
                                    Cancel
                                </a>
                                <button type="submit" :disabled="points.length < 3" :class="points.length < 3 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-800'" class="px-6 py-3 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                                    Save Municipal Ward
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function wardDrawerHandler() {
        return {
            points: [],
            map: null,
            polygonLayer: null,
            markersLayer: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                });
            },

            initMap() {
                this.map = L.map('ward-drawer-map', {
                    center: [10.0538, 76.3567],
                    zoom: 13
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map);

                this.markersLayer = L.layerGroup().addTo(this.map);

                // Click to add point
                this.map.on('click', (e) => {
                    this.addPoint(e.latlng.lat, e.latlng.lng);
                });
            },

            addPoint(lat, lng) {
                this.points.push([Number(lat.toFixed(6)), Number(lng.toFixed(6))]);
                this.redraw();
            },

            undoPoint() {
                this.points.pop();
                this.redraw();
            },

            removePoint(idx) {
                this.points.splice(idx, 1);
                this.redraw();
            },

            clearPoints() {
                this.points = [];
                this.redraw();
            },

            redraw() {
                this.markersLayer.clearLayers();
                if (this.polygonLayer) {
                    this.map.removeLayer(this.polygonLayer);
                    this.polygonLayer = null;
                }

                if (this.points.length === 0) return;

                // Draw markers for each vertex
                this.points.forEach((pt, idx) => {
                    const icon = L.divIcon({
                        className: 'vertex-marker',
                        html: `<div class="w-5 h-5 rounded-full bg-indigo-600 text-white font-bold text-[10px] flex items-center justify-center border-2 border-white shadow-md">${idx + 1}</div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    L.marker(pt, { icon }).addTo(this.markersLayer);
                });

                // Draw polyline or polygon
                if (this.points.length >= 3) {
                    this.polygonLayer = L.polygon(this.points, {
                        color: '#4f46e5',
                        weight: 3,
                        fillColor: '#6366f1',
                        fillOpacity: 0.25
                    }).addTo(this.map);
                } else if (this.points.length === 2) {
                    this.polygonLayer = L.polyline(this.points, {
                        color: '#4f46e5',
                        weight: 3,
                        dashArray: '5, 5'
                    }).addTo(this.map);
                }
            },

            prepareSubmit(e) {
                if (this.points.length < 3) {
                    e.preventDefault();
                    alert('Please place at least 3 points on the map to define the ward boundary polygon.');
                    return false;
                }
            }
        };
    }
    </script>
    @endpush
</x-authenticated-layout>
