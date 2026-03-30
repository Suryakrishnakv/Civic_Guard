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
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.237 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            {{ __('Report Case Details') }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium tracking-tight">Reviewing Case #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <a href="{{ Auth::user()->role !== 'citizen' ? route('admin.dashboard') : route('dashboard') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-slate-50 opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center">
                        <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[120px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8B0000] rounded-full blur-[120px] opacity-10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden group">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/30 via-white to-indigo-50/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    </div>
                     <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div>
                             <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-3xl font-bold font-eagle-lake text-slate-900 tracking-tight">{{ $report->category }}</h3>
                                <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm border
                                    {{ $report->status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                    {{ $report->status === 'In Progress' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                    {{ $report->status === 'Repaired' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                    {{ $report->status === 'Rejected' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                    {{ $report->status }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-6 text-sm text-slate-500 font-medium">
                                <span class="flex items-center gap-2">
                                    <div class="p-1.5 bg-slate-100 rounded-lg text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    {{ $report->created_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-2">
                                     <div class="p-1.5 bg-slate-100 rounded-lg text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                     </div>
                                    {{ $report->department ?? 'General' }}
                                </span>
                            </div>
                        </div>
                        <div>
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Status</h4>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest
                                    @if($report->status == 'Pending') bg-amber-100 text-amber-700 border border-amber-200
                                    @elseif($report->status == 'In Progress') bg-indigo-100 text-indigo-700 border border-indigo-200
                                    @elseif($report->status == 'Repaired') bg-emerald-100 text-emerald-700 border border-emerald-200
                                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                    {{ $report->status }}
                                </span>
                            </div>
                     </div>
                </div>

                <div class="p-10 text-slate-900 relative">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Left Column: Details -->
                        <div class="space-y-8">
                            <!-- Location -->
                            <div>
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Location</h4>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <p class="text-slate-900 font-bold">{{ $report->location }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Description</h4>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 min-h-[100px]">
                                    <p class="text-slate-900 font-medium whitespace-pre-wrap leading-relaxed">{{ $report->description }}</p>
                                </div>
                            </div>

                            <!-- Official Remarks (Visible to all if exists) -->
                            @if($report->remarks)
                            <div>
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Officer Remarks</h4>
                                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                                    <p class="text-slate-900 font-bold whitespace-pre-wrap leading-relaxed">{{ $report->remarks }}</p>
                                </div>
                            </div>
                            @endif

                             <!-- Admin/Officer Action Section -->
                            @if(Auth::user()->isOfficer() || Auth::user()->isAdmin())
                            <div class="mt-8 pt-8 border-t border-slate-100">
                                <h4 class="font-eagle-lake font-bold text-xl text-[#8B0000] mb-6">Update Status</h4>
                                <form action="{{ route('admin.reports.update', $report) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-slate-50 p-6 rounded-xl border border-slate-100"
                                      x-data="{ 
                                        status: '{{ $report->status }}', 
                                        hasPhoto: {{ $report->resolution_photo_path ? 'true' : 'false' }},
                                        isOfficer: {{ Auth::user()->isOfficer() ? 'true' : 'false' }},
                                        get needsPhoto() {
                                            return this.isOfficer && this.status === 'Repaired' && !this.hasPhoto;
                                        }
                                      }">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                                            <select name="status" x-model="status" class="block w-full rounded-lg border-slate-300 focus:border-[#8B0000] focus:ring-[#8B0000] bg-white">
                                                <option value="Pending">Pending</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Repaired">Repaired</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">Remarks / Action Taken</label>
                                        <textarea name="remarks" rows="3" class="block w-full rounded-lg border-slate-300 focus:border-[#8B0000] focus:ring-[#8B0000] bg-white" placeholder="Enter details about action taken...">{{ $report->remarks }}</textarea>
                                    </div>

                                    <div x-data="{ 
                                        photos: [],
                                        previews: [],
                                        handleFiles(e) {
                                            const newFiles = Array.from(e.target.files);
                                            this.photos = [...this.photos, ...newFiles];
                                            this.updateInput();
                                            this.generatePreviews();
                                            this.hasPhoto = this.photos.length > 0;
                                        },
                                        updateInput() {
                                            const dt = new DataTransfer();
                                            this.photos.forEach(file => dt.items.add(file));
                                            document.getElementById('resolution_photos').files = dt.files;
                                        },
                                        generatePreviews() {
                                            this.previews = [];
                                            this.photos.forEach(file => {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { this.previews.push(e.target.result); };
                                                reader.readAsDataURL(file);
                                            });
                                        },
                                        removePhoto(index) {
                                            this.photos.splice(index, 1);
                                            this.previews.splice(index, 1);
                                            this.updateInput();
                                            this.hasPhoto = this.photos.length > 0;
                                        }
                                    }">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Proof of Resolution</label>
                                            <template x-if="needsPhoto">
                                                <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest animate-pulse">Required for Repaired Status</span>
                                            </template>
                                        </div>

                                        <!-- Premium Upload Area -->
                                        <div class="relative group/upload">
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-[1.25rem] bg-white group-hover/upload:border-[#8B0000]/50 transition-all duration-300 cursor-pointer overflow-hidden" 
                                                 x-show="photos.length === 0"
                                                 onclick="document.getElementById('resolution_photos').click()">
                                                
                                                <div class="space-y-2 text-center">
                                                    <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto text-[#8B0000] group-hover/upload:scale-110 transition-transform duration-500 shadow-sm border border-red-100/50">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                    <div class="flex text-xs text-slate-500 justify-center">
                                                        <span class="font-black text-[#8B0000] uppercase tracking-widest">Upload Photos</span>
                                                        <p class="pl-1 font-medium tracking-tight">or drag and drop</p>
                                                    </div>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">HD Images allowed up to 10MB each</p>
                                                </div>
                                            </div>

                                            <!-- Preview Grid -->
                                            <div x-show="photos.length > 0" class="grid grid-cols-2 gap-3">
                                                <template x-for="(preview, index) in previews" :key="index">
                                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-video bg-slate-100">
                                                        <img :src="preview" class="w-full h-full object-cover">
                                                        <button @click.prevent="removePhoto(index)" class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                
                                                <!-- Add More mini button -->
                                                <button @click.prevent="document.getElementById('resolution_photos').click()" 
                                                        class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl hover:border-[#8B0000] hover:bg-red-50 transition-all aspect-video text-slate-400 hover:text-[#8B0000]">
                                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    <span class="text-[8px] font-black uppercase tracking-widest">Add More</span>
                                                </button>
                                            </div>

                                            <input type="file" name="resolution_photos[]" id="resolution_photos" class="hidden" accept="image/*" multiple @change="handleFiles($event)"/>
                                        </div>
                                        <x-input-error :messages="$errors->get('resolution_photos')" class="mt-2" />
                                        <x-input-error :messages="$errors->get('resolution_photos.*')" class="mt-2" />
                                    </div>

                                    <div class="flex justify-end pt-4 border-t border-slate-200">
                                        <button type="submit" 
                                            :disabled="needsPhoto"
                                            :class="needsPhoto ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-red-800 shadow-red-900/10'"
                                            class="group relative inline-flex items-center px-8 py-4 bg-[#8B0000] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest transition-all shadow-xl active:scale-95 overflow-hidden">
                                             <span class="absolute inset-0 w-full h-full bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                             <span class="relative flex items-center">
                                                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                 {{ __('Update Details') }}
                                             </span>
                                        </button>
                                    </div>
                                </form>

                                @if(Auth::user()->isAdmin())
                                <div class="mt-8 pt-6 border-t border-slate-200">
                                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-red-200 rounded-lg text-sm font-bold text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            Delete Permanently
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Right Column: Photos -->
                        <div class="space-y-8">
                            <div>
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Reported Issue Photos</h4>
                                <div class="space-y-4">
                                    @forelse($report->reportedPhotos as $photo)
                                        <div class="group relative rounded-xl overflow-hidden shadow-lg border border-slate-200 bg-slate-50">
                                            <img src="{{ $photo->photo_content ?? asset('storage/' . $photo->photo_path) }}" alt="Report Photo" class="w-full h-auto object-contain">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors pointer-events-none"></div>
                                            <a href="{{ $photo->photo_content ?? asset('storage/' . $photo->photo_path) }}" target="_blank" class="absolute bottom-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity text-sm font-bold text-slate-900">
                                                View Full Size
                                            </a>
                                        </div>
                                    @empty
                                        @if($report->photo_path)
                                            <div class="group relative rounded-xl overflow-hidden shadow-lg border border-slate-200 bg-slate-50">
                                                <img src="{{ $report->photo_content ?? asset('storage/' . $report->photo_path) }}" alt="Report Photo" class="w-full h-auto object-contain">
                                                <a href="{{ $report->photo_content ?? asset('storage/' . $report->photo_path) }}" target="_blank" class="absolute bottom-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity text-sm font-bold text-slate-900">
                                                    View Full Size
                                                </a>
                                            </div>
                                        @else
                                            <div class="rounded-xl border-2 border-dashed border-slate-200 p-12 text-center">
                                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-slate-500 font-bold">No photos provided</p>
                                            </div>
                                        @endif
                                    @endforelse
                                </div>
                            </div>

                            @if($report->resolutionPhotos->count() > 0 || $report->resolution_photo_path)
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-green-400 rounded-2xl blur opacity-25"></div>
                                <div class="relative">
                                    <h4 class="text-sm font-bold text-green-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Proof of Resolution
                                    </h4>
                                    <div class="space-y-4">
                                        @foreach($report->resolutionPhotos as $photo)
                                            <div class="group relative rounded-xl overflow-hidden shadow-lg border border-green-200 bg-white">
                                                <div class="absolute top-0 right-0 bg-green-500 text-white text-xs px-3 py-1.5 rounded-bl-xl font-bold z-10 shadow-sm">RESOLVED</div>
                                                <img src="{{ $photo->photo_content ?? asset('storage/' . $photo->photo_path) }}" alt="Resolution Proof" class="w-full h-auto object-contain">
                                                <a href="{{ $photo->photo_content ?? asset('storage/' . $photo->photo_path) }}" target="_blank" class="absolute bottom-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity text-sm font-bold text-slate-900">
                                                    View Full Size
                                                </a>
                                            </div>
                                        @endforeach
                                        
                                        @if($report->resolutionPhotos->count() === 0 && $report->resolution_photo_path)
                                            <div class="group relative rounded-xl overflow-hidden shadow-lg border border-green-200 bg-white">
                                                <div class="absolute top-0 right-0 bg-green-500 text-white text-xs px-3 py-1.5 rounded-bl-xl font-bold z-10 shadow-sm">RESOLVED</div>
                                                <img src="{{ $report->resolution_photo_content ?? asset('storage/' . $report->resolution_photo_path) }}" alt="Resolution Proof" class="w-full h-auto object-contain">
                                                <a href="{{ $report->resolution_photo_content ?? asset('storage/' . $report->resolution_photo_path) }}" target="_blank" class="absolute bottom-4 right-4 bg-white/90 p-2 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity text-sm font-bold text-slate-900">
                                                    View Full Size
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
