<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Edit Department
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Update Department name & Category assignments</p>
                    </div>
                </div>
                <a href="{{ route('admin.departments.index') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm">
                    <span class="relative flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Directory
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen bg-[#f8fafc]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden">
                <form method="POST" action="{{ route('admin.departments.update', $department) }}" class="p-10 space-y-8" 
                      x-data="{ categories: {{ json_encode(old('categories_array', $department->categories ?? [])) }}, newCategory: '' }">
                    @csrf
                    @method('PUT')

                    <!-- Department Identity Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-[#8B0000] font-bold text-sm">1</div>
                            <h3 class="font-eagle-lake font-bold text-xl text-slate-800 tracking-tight">Department Identity</h3>
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Department Name')" class="text-slate-700 font-bold ml-1 mb-2" />
                            <x-text-input id="name" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" type="text" name="name" :value="old('name', $department->name)" required autofocus placeholder="e.g. Health & Sanitation Department" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Associated Works / Categories Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 font-bold text-sm">2</div>
                            <h3 class="font-eagle-lake font-bold text-xl text-slate-800 tracking-tight">Categories & Responsibilities (Works)</h3>
                        </div>

                        <div class="space-y-4">
                            <x-input-label :value="__('Add Category / Work Type')" class="text-slate-700 font-bold ml-1" />
                            <p class="text-xs text-slate-500 italic mb-4">Type the name of a public issue category that this department handles and press Enter or click 'Add'.</p>
                            
                            <div class="flex gap-3">
                                <x-text-input x-model="newCategory" @keydown.enter.prevent="if (newCategory.trim()) { if (!categories.includes(newCategory.trim())) { categories.push(newCategory.trim()); } newCategory = ''; }" placeholder="e.g. Water Leakage" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" />
                                <button type="button" @click="if (newCategory.trim()) { if (!categories.includes(newCategory.trim())) { categories.push(newCategory.trim()); } newCategory = ''; }" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 shadow-lg shadow-indigo-600/10 transition duration-200">
                                    Add
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('categories_array')" class="mt-2" />
                        </div>

                        <!-- Tag List Area -->
                        <div class="space-y-3">
                            <x-input-label :value="__('Configured Categories')" class="text-slate-700 font-bold ml-1" />
                            
                            <div class="p-6 rounded-[2rem] bg-indigo-50/30 border border-indigo-50 min-h-[100px]">
                                <div class="flex flex-wrap gap-2.5">
                                    <template x-if="categories.length === 0">
                                        <p class="text-xs text-slate-400 italic">No categories added yet. Add at least one related work category.</p>
                                    </template>
                                    
                                    <template x-for="(cat, idx) in categories" :key="idx">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white text-slate-800 border border-slate-200/80 shadow-sm transition-all hover:border-[#8B0000]/30">
                                            <span x-text="cat"></span>
                                            <button type="button" @click="categories.splice(idx, 1)" class="w-4 h-4 rounded-full bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold transition-colors">
                                                &times;
                                            </button>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Input to serialize array -->
                        <input type="hidden" name="categories" :value="JSON.stringify(categories)">
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6">
                        <button type="submit" class="w-full group/btn relative inline-flex items-center justify-center px-8 py-4 bg-[#8B0000] text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-red-800 transition-all shadow-xl shadow-red-900/10 overflow-hidden transform hover:-translate-y-1 active:scale-[0.98]">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-red-600 to-[#8B0000] opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Save changes
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-authenticated-layout>
