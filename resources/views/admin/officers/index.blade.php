<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/60 shadow-sm px-8 py-6 group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Officer Directory
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Managed Personnel & Departments</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.officers.create') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-[#8B0000] text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-800 transition-all shadow-md overflow-hidden">
                        <span class="relative flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Officer
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

            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/30 via-white to-indigo-50/30 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Departmental Officers</h3>
                        <p class="text-[10px] text-[#8B0000] font-black uppercase tracking-widest mt-1 opacity-70">Authorized Service Panel</p>
                    </div>
                    <div class="px-5 py-2 bg-white border border-slate-100 rounded-2xl shadow-sm text-xs font-bold text-slate-600">
                        <span class="text-[#8B0000]">{{ $officers->total() }}</span> Active Officers
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Officer Identity</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Department</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Assigned Categories</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($officers as $officer)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-2xl bg-[#8B0000] flex items-center justify-center text-sm font-black text-white shadow-sm transform group-hover:rotate-6 transition-transform">
                                                {{ substr($officer->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors uppercase tracking-wide">{{ $officer->name }}</div>
                                                <div class="text-xs text-slate-400 font-medium italic">{{ $officer->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">
                                            {{ $officer->department }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-wrap gap-1">
                                            @if($officer->categories)
                                                @foreach($officer->categories as $category)
                                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                        {{ $category }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-slate-400 italic">No categories assigned</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right text-sm">
                                        <form action="{{ route('admin.officers.destroy', $officer) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this officer?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-red-50">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $officers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
