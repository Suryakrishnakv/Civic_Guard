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
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            {{ __('Report Asset Damage') }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">Pinpoint the civic issue on the map for rapid municipal resolution.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-slate-50 opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center">
                        <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen bg-[#f8fafc] overflow-hidden"
         x-data="reportFormHandler()">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[120px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8B0000] rounded-full blur-[120px] opacity-10 pointer-events-none"></div>

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden group">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/50 via-white to-indigo-50/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </div>
                    <header class="relative z-10">
                        <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Submit New Civic Report</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Select location on the map, provide details and photo proofs.</p>
                    </header>
                </div>
                
                <div class="p-8 sm:p-10 text-slate-900 relative">
                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Hidden GPS Coordinates & Full Address -->
                        <input type="hidden" name="latitude" id="latitude" x-model="latitude">
                        <input type="hidden" name="longitude" id="longitude" x-model="longitude">
                        <input type="hidden" name="address" id="address" x-model="address">

                        <!-- Category -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <x-input-label for="category" :value="__('Select Issue Category')" class="text-slate-700 font-bold" />
                                <span class="text-[11px] font-semibold text-slate-400">Step 1</span>
                            </div>
                            <select id="category" name="category" required x-model="category" @change="onCategoryChange()" class="block w-full border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-xl shadow-sm transition-all duration-200 bg-indigo-50/40 font-bold text-slate-900 py-3">
                                <option value="" disabled selected>Choose a category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Smart Interactive GIS Map Picker -->
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <x-input-label :value="__('Pinpoint Location on Map')" class="text-slate-700 font-bold" />
                                    <p class="text-xs text-slate-500">Drag marker, click map, or auto-detect with GPS.</p>
                                </div>

                                <!-- GPS Geolocation Button (enableHighAccuracy) -->
                                <button type="button" 
                                        @click="detectGPSLocation()" 
                                        :disabled="isLocating"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-[#8B0000] text-slate-700 hover:text-[#8B0000] rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                                    <svg class="w-4 h-4 text-emerald-600" :class="{ 'animate-spin': isLocating }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span x-text="isLocating ? 'Detecting GPS...' : 'Use My GPS Location'"></span>
                                </button>
                            </div>

                            <!-- Place Search Bar -->
                            <div class="relative">
                                <input type="text"
                                       x-model="searchQuery"
                                       @keydown.enter.prevent="searchNominatim()"
                                       placeholder="Search place, street, or landmark (Press Enter to search)..."
                                       class="w-full pl-9 pr-24 py-2.5 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                
                                <button type="button" 
                                        @click="searchNominatim()" 
                                        class="absolute right-1.5 top-1.5 px-3 py-1.5 bg-[#8B0000] text-white rounded-lg text-[11px] font-bold hover:bg-red-700 transition-colors">
                                    Search
                                </button>
                            </div>

                            <!-- Leaflet Map Canvas -->
                            <div class="relative w-full h-[320px] sm:h-[360px] rounded-2xl border-2 border-slate-200/90 overflow-hidden shadow-inner">
                                <div id="report-picker-map" class="w-full h-full"></div>

                                <!-- Status Badge Overlay -->
                                <div class="absolute top-3 right-3 z-[400] bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-md border border-slate-200/80 text-[11px] font-bold text-slate-700 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>200m Duplicate Shield Active</span>
                                </div>
                            </div>

                            <!-- Coordinate Badges -->
                            <div class="flex flex-wrap items-center justify-between gap-3 text-xs bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-500">Selected Coordinates:</span>
                                    <span class="font-mono font-bold text-[#8B0000]" x-text="latitude ? latitude.toFixed(6) : 'Not set'"></span>
                                    <span class="text-slate-300">|</span>
                                    <span class="font-mono font-bold text-[#8B0000]" x-text="longitude ? longitude.toFixed(6) : 'Not set'"></span>
                                </div>
                                <span class="text-[11px] text-slate-400 italic">Click or drag pin to update</span>
                            </div>

                            <!-- Location Address Text Input -->
                            <div>
                                <x-input-label for="location" :value="__('Location Name / Street Address')" class="text-slate-700 font-bold" />
                                <x-text-input id="location" x-model="location" class="block mt-1 w-full border-slate-300 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-xl shadow-sm hover:border-red-300 transition-colors text-sm" type="text" name="location" required placeholder="e.g., Near Metro Pillar 340, Kalamassery" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- 200m Nearby Duplicate Prevention Alert Box (Requirement 7) -->
                            <div x-show="nearbyComplaints.length > 0"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="p-5 bg-amber-50/90 border border-amber-200 rounded-2xl shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-amber-900">
                                            Notice: <span x-text="nearbyComplaints.length"></span> active complaint(s) found within 200 metres!
                                        </h4>
                                        <p class="text-xs text-amber-700 mt-0.5">Please check if your issue is already reported to prevent duplicate filings.</p>

                                        <!-- Duplicate List Carousel/Cards -->
                                        <div class="mt-3 space-y-2 max-h-48 overflow-y-auto pr-1">
                                            <template x-for="item in nearbyComplaints" :key="item.id">
                                                <div class="p-3 bg-white rounded-xl border border-amber-100 flex items-center justify-between gap-3 shadow-xs">
                                                    <div class="flex items-center gap-3">
                                                        <template x-if="item.photo_url">
                                                            <img :src="item.photo_url" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                                        </template>
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs font-bold text-slate-900" x-text="item.category"></span>
                                                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800" x-text="item.status"></span>
                                                            </div>
                                                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5" x-text="item.description"></p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right shrink-0">
                                                        <span class="text-[10px] font-bold text-[#8B0000] block" x-text="item.distance_meters + 'm away'"></span>
                                                        <a :href="item.detail_url" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:underline">View Case &rarr;</a>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" class="text-slate-700 font-bold" />
                            <textarea id="description" name="description" x-model="description" rows="3" required class="mt-1 block w-full border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-xl shadow-sm transition-all duration-200 ease-in-out hover:border-slate-300 bg-indigo-50/40 font-medium text-slate-900 placeholder-slate-400 text-sm" placeholder="Describe the damage, landmark hints, or specific hazards..."></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Photo Uploads -->
                        <div>
                            <x-input-label for="photo" :value="__('Upload Evidence Photographs (At least one required)')" class="text-slate-700 font-bold" />
                            
                            <!-- Initial Upload Area (Visible when no photos) -->
                            <div x-show="photos.length === 0" 
                                 class="mt-1 flex justify-center px-6 pt-6 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-[#8B0000] transition-colors cursor-pointer bg-slate-50/60" 
                                 onclick="document.getElementById('photo').click()">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label class="relative cursor-pointer bg-transparent rounded-md font-bold text-[#8B0000] hover:text-red-700 focus-within:outline-none">
                                            <span>Upload images</span>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-400">PNG, JPG, JPEG up to 10MB each</p>
                                </div>
                            </div>

                            <!-- Preview Grid and Add More Option -->
                            <div x-show="photos.length > 0" class="mt-4 space-y-4">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    <template x-for="(preview, index) in previews" :key="index">
                                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-100">
                                            <img :src="preview" class="w-full h-full object-cover">
                                            <button @click.prevent="removePhoto(index)" class="absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <!-- Add More Button -->
                                    <button @click.prevent="document.getElementById('photo').click()" 
                                            class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl hover:border-[#8B0000] hover:bg-red-50 transition-all aspect-square text-slate-400 hover:text-[#8B0000]">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        <span class="text-xs font-bold uppercase tracking-widest">Add More</span>
                                    </button>
                                </div>
                            </div>

                            <input type="file" name="photos[]" id="photo" accept="image/*" multiple class="hidden" @change="handlePhotos($event.target)"/>
                            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                            <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end items-center gap-6 pt-6 border-t border-slate-100">
                            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors tracking-wide uppercase">
                                Cancel
                            </a>
                            <button type="submit" 
                                    :disabled="!category || !location || !description || photos.length === 0"
                                    :class="(!category || !location || !description || photos.length === 0) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-red-800 shadow-red-900/10'"
                                    class="group relative inline-flex items-center px-10 py-4 bg-[#8B0000] border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest transition-all shadow-xl active:scale-95 overflow-hidden">
                                 <span class="absolute inset-0 w-full h-full bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                 <span class="relative flex items-center">
                                     <svg class="w-5 h-5 mr-3 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                     {{ __('Submit Report') }}
                                 </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function reportFormHandler() {
        return {
            category: '',
            location: '',
            description: '',
            latitude: 10.0538,
            longitude: 76.3567,
            address: '',
            photos: [],
            previews: [],
            
            map: null,
            marker: null,
            radiusCircle: null,
            isLocating: false,
            searchQuery: '',
            nearbyComplaints: [],
            checkDuplicateTimeout: null,

            init() {
                this.$nextTick(() => {
                    this.initPickerMap();
                });
            },

            initPickerMap() {
                const defaultLat = this.latitude || 10.0538;
                const defaultLng = this.longitude || 76.3567;

                this.map = L.map('report-picker-map', {
                    center: [defaultLat, defaultLng],
                    zoom: 14,
                    zoomControl: false
                });

                L.control.zoom({ position: 'topright' }).addTo(this.map);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map);

                // Create Draggable Pin (Requirement 2)
                const pinIcon = L.divIcon({
                    className: 'report-picker-pin',
                    html: `
                        <div class="relative flex items-center justify-center -translate-x-1/2 -translate-y-full cursor-grab">
                            <div class="w-9 h-9 rounded-full bg-[#8B0000] text-white flex items-center justify-center shadow-xl border-2 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            </div>
                            <div class="w-2.5 h-2.5 bg-[#8B0000] rotate-45 border-r border-b border-white -mt-1 mx-auto"></div>
                        </div>
                    `,
                    iconSize: [36, 42],
                    iconAnchor: [18, 42]
                });

                this.marker = L.marker([defaultLat, defaultLng], {
                    draggable: true,
                    icon: pinIcon
                }).addTo(this.map);

                // 200m Duplicate Detection Radius Circle (Requirement 7)
                this.radiusCircle = L.circle([defaultLat, defaultLng], {
                    radius: 200,
                    color: '#8B0000',
                    fillColor: '#ef4444',
                    fillOpacity: 0.12,
                    weight: 1.5,
                    dashArray: '4, 4'
                }).addTo(this.map);

                // Drag end event
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.updateCoordinates(pos.lat, pos.lng, true);
                });

                // Click on map to place pin
                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.updateCoordinates(e.latlng.lat, e.latlng.lng, true);
                });

                // Reverse geocode initial position
                this.reverseGeocode(defaultLat, defaultLng);
                this.checkDuplicates(defaultLat, defaultLng);
            },

            updateCoordinates(lat, lng, fetchAddress = false) {
                this.latitude = lat;
                this.longitude = lng;
                this.radiusCircle.setLatLng([lat, lng]);

                if (fetchAddress) {
                    this.reverseGeocode(lat, lng);
                }

                // Debounced duplicate check
                clearTimeout(this.checkDuplicateTimeout);
                this.checkDuplicateTimeout = setTimeout(() => {
                    this.checkDuplicates(lat, lng);
                }, 400);
            },

            detectGPSLocation() {
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

                        this.marker.setLatLng([lat, lng]);
                        this.map.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
                        this.updateCoordinates(lat, lng, true);
                    },
                    (err) => {
                        this.isLocating = false;
                        console.warn("GPS error:", err);
                        alert("Could not access your location. Please check browser permissions or select on map.");
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            },

            searchNominatim() {
                if (!this.searchQuery.trim()) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const first = data[0];
                            const lat = parseFloat(first.lat);
                            const lng = parseFloat(first.lon);

                            this.marker.setLatLng([lat, lng]);
                            this.map.flyTo([lat, lng], 15, { animate: true });
                            this.location = first.display_name.split(',').slice(0, 3).join(',');
                            this.address = first.display_name;
                            this.updateCoordinates(lat, lng, false);
                        } else {
                            alert('Location not found.');
                        }
                    })
                    .catch(err => console.error("Search error:", err));
            },

            reverseGeocode(lat, lng) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.display_name) {
                            this.address = data.display_name;
                            if (!this.location || this.location.startsWith('Lat:')) {
                                this.location = data.display_name.split(',').slice(0, 3).join(',');
                            }
                        }
                    })
                    .catch(err => console.warn("Reverse geocode failed:", err));
            },

            checkDuplicates(lat, lng) {
                const params = new URLSearchParams({
                    latitude: lat,
                    longitude: lng,
                    radius: 200
                });
                if (this.category) {
                    params.append('category', this.category);
                }

                fetch(`{{ route('reports.checkDuplicate') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    this.nearbyComplaints = data.reports || [];
                })
                .catch(err => console.error("Duplicate check error:", err));
            },

            onCategoryChange() {
                if (this.latitude && this.longitude) {
                    this.checkDuplicates(this.latitude, this.longitude);
                }
            },

            handlePhotos(input) {
                const newFiles = Array.from(input.files);
                this.photos = [...this.photos, ...newFiles];
                this.updateInput();
                this.generatePreviews();
            },

            updateInput() {
                const dt = new DataTransfer();
                this.photos.forEach(file => dt.items.add(file));
                document.getElementById('photo').files = dt.files;
            },

            generatePreviews() {
                this.previews = [];
                this.photos.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => this.previews.push(e.target.result);
                    reader.readAsDataURL(file);
                });
            },

            removePhoto(index) {
                this.photos.splice(index, 1);
                this.previews.splice(index, 1);
                this.updateInput();
            }
        };
    }
    </script>
    @endpush
</x-authenticated-layout>
