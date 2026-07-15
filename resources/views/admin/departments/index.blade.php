<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Department Directory
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Configure Dynamic Services & Associated Categories</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.departments.create') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-[#8B0000] text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-800 transition-all shadow-md overflow-hidden">
                        <span class="relative flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Department
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            @if(session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm shadow-sm animate-fade-in-down">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->has('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl font-bold text-sm shadow-sm animate-fade-in-down">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/30 via-white to-indigo-50/30 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Active Services</h3>
                        <p class="text-[10px] text-[#8B0000] font-black uppercase tracking-widest mt-1 opacity-70">Dynamic Operations & Works</p>
                    </div>
                    <div class="px-5 py-2 bg-white border border-slate-100 rounded-2xl shadow-sm text-xs font-bold text-slate-600">
                        <span class="text-[#8B0000]">{{ $departments->total() }}</span> Active Departments
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Department Name</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Associated Works / Categories</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($departments as $dept)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-sm font-black text-white shadow-sm transform group-hover:rotate-6 transition-transform">
                                                {{ substr($dept->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors uppercase tracking-wide">{{ $dept->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-wrap gap-1.5">
                                            @if($dept->categories && count($dept->categories) > 0)
                                                @foreach($dept->categories as $category)
                                                    <span class="px-3 py-1 rounded-xl text-[10px] font-bold bg-[#8B0000]/10 text-[#8B0000] border border-[#8B0000]/20 uppercase tracking-widest shadow-sm">
                                                        {{ $category }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-slate-400 italic">No categories/works added yet</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end gap-3 text-slate-400">
                                            <a href="{{ route('admin.departments.edit', $dept) }}" class="p-2 hover:bg-indigo-50 rounded-xl hover:text-indigo-600 transition-colors" title="Edit Department">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-2 rounded-xl hover:bg-red-50" title="Delete Department">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
