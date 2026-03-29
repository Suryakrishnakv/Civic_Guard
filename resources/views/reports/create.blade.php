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
                        <p class="text-sm text-slate-500 mt-1 font-medium">Help us keep our community safe and functional.</p>
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

    <div class="py-12 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[120px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8B0000] rounded-full blur-[120px] opacity-10 pointer-events-none"></div>

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden group">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/50 via-white to-indigo-50/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </div>
                    <header class="relative z-10">
                        <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Submit New Report</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Provide accurate details for faster resolution by our teams.</p>
                    </header>
                </div>
                
                <div class="p-10 text-slate-900 relative">

                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6"
                          x-data="{ 
                            category: '', 
                            location: '', 
                            description: '', 
                            photos: [],
                            previews: [],
                            handlePhotos(input) {
                                const newFiles = Array.from(input.files);
                                // Combine with existing photos if you want 'Add more' behavior
                                // But standard input[type=file] replaces on change.
                                // We can maintain an internal state and use DataTransfer to sync back.
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
                                    reader.onload = (e) => {
                                        this.previews.push(e.target.result);
                                    };
                                    reader.readAsDataURL(file);
                                });
                            },
                            removePhoto(index) {
                                this.photos.splice(index, 1);
                                this.previews.splice(index, 1);
                                this.updateInput();
                            }
                          }">
                        @csrf

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Category')" class="text-slate-700 font-bold" />
                            <select id="category" name="category" required x-model="category" class="mt-1 block w-full border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-xl shadow-sm transition-all duration-200 ease-in-out hover:border-slate-300 bg-indigo-50 font-bold text-slate-900">
                                <option value="" disabled selected>Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Location -->
                        <div>
                            <x-input-label for="location" :value="__('Location')" class="text-slate-700 font-bold" />
                            <x-text-input id="location" x-model="location" class="block mt-1 w-full border-slate-300 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-lg shadow-sm hover:border-red-300 transition-colors" type="text" name="location" required placeholder="e.g., Main Street, Near Central Park" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                             <x-input-label for="description" :value="__('Description')" class="text-slate-700 font-bold" />
                            <textarea id="description" name="description" x-model="description" rows="4" required class="mt-1 block w-full border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] rounded-xl shadow-sm transition-all duration-200 ease-in-out hover:border-slate-300 bg-indigo-50 font-bold text-slate-900 placeholder-slate-400" placeholder="Describe the issue in detail..."></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Photo -->
                        <div>
                            <x-input-label for="photo" :value="__('Upload Photos (At least one required)')" class="text-slate-700 font-bold" />
                            
                            <!-- Initial Upload Area (Visible when no photos) -->
                            <div x-show="photos.length === 0" 
                                 class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-lg hover:border-[#8B0000] transition-colors cursor-pointer bg-slate-50/50" 
                                 onclick="document.getElementById('photo').click()">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label class="relative cursor-pointer bg-transparent rounded-md font-bold text-[#8B0000] hover:text-red-700 focus-within:outline-none">
                                            <span>Upload images</span>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-400">PNG, JPG, GIF up to 10MB each</p>
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

                        <div class="flex justify-end items-center gap-6 pt-8 border-t border-slate-100">
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
                                     {{ __('Send Report') }}
                                 </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-authenticated-layout>
