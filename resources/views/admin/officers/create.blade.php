<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Onboard Officer
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Create New Service Personnel Credentials</p>
                    </div>
                </div>
                <a href="{{ route('admin.officers.index') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm">
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
                <form method="POST" action="{{ route('admin.officers.store') }}" class="p-10 space-y-8">
                    @csrf

                    <!-- Identity Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-[#8B0000] font-bold text-sm">1</div>
                            <h3 class="font-eagle-lake font-bold text-xl text-slate-800 tracking-tight">Personal Identity</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Full Name')" class="text-slate-700 font-bold ml-1 mb-2" />
                                <x-text-input id="name" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Electronic Mail (Email)')" class="text-slate-700 font-bold ml-1 mb-2" />
                                <x-text-input id="email" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Section -->
                    <div class="space-y-6" x-data="{ selectedDept: '{{ old('department', '') }}', deptMap: {{ json_encode($departmentsWithCategories) }} }">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 font-bold text-sm">2</div>
                            <h3 class="font-eagle-lake font-bold text-xl text-slate-800 tracking-tight">Service Assignment</h3>
                        </div>

                        <div>
                            <x-input-label for="department" :value="__('Assigned Department')" class="text-slate-700 font-bold ml-1 mb-2" />
                            <select id="department" name="department" x-model="selectedDept" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl appearance-none transition-all shadow-sm" required>
                                <option value="" disabled selected>Select a Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <!-- Live categories preview -->
                        <div x-show="selectedDept && deptMap[selectedDept] && deptMap[selectedDept].length" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="space-y-4 pt-2">
                            <x-input-label :value="__('Assigned Categories / Works')" class="text-slate-700 font-bold ml-1" />
                            <p class="text-xs text-slate-500 italic mb-4">This officer will be automatically assigned to manage the following categories of works:</p>
                            
                            <div class="p-6 rounded-[2rem] bg-indigo-50/30 border border-indigo-50">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(cat, index) in deptMap[selectedDept]" :key="index">
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-[#8B0000]/10 text-[#8B0000] border border-[#8B0000]/20 uppercase tracking-widest shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            <span x-text="cat"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 font-bold text-sm">3</div>
                            <h3 class="font-eagle-lake font-bold text-xl text-slate-800 tracking-tight">Protocol & Security</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="password" :value="__('Access Password')" class="text-slate-700 font-bold ml-1 mb-2" />
                                <x-text-input id="password" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Access Password')" class="text-slate-700 font-bold ml-1 mb-2" />
                                <x-text-input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:bg-white focus:border-[#8B0000] focus:ring-[#8B0000] rounded-2xl transition-all" type="password" name="password_confirmation" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6">
                        <button type="submit" class="w-full group/btn relative inline-flex items-center justify-center px-8 py-4 bg-[#8B0000] text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-red-800 transition-all shadow-xl shadow-red-900/10 overflow-hidden transform hover:-translate-y-1 active:scale-[0.98]">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-red-600 to-[#8B0000] opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Finalize Officer Activation
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-authenticated-layout>
