<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/60 shadow-sm px-8 py-6 group">
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/40 via-white to-red-50/40 opacity-100"></div>
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-64 h-64 bg-indigo-500 rounded-full blur-[80px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-500"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/3 w-64 h-64 bg-[#8B0000] rounded-full blur-[80px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-500"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-[#8B0000]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Registered Citizens
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Verified Community Members</p>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-slate-50 opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center">
                        <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Dashboard
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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/30 via-white to-indigo-50/30 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Active User Directory</h3>
                        <p class="text-[10px] text-[#8B0000] font-black uppercase tracking-widest mt-1 opacity-70">Security Protocol Alpha</p>
                    </div>
                    <div class="px-5 py-2 bg-white border border-slate-100 rounded-2xl shadow-sm text-xs font-bold text-slate-600">
                        <span class="text-[#8B0000]">{{ $users->total() }}</span> Verified Citizens
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Citizen Identity</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Electronic Mail</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Registry Date</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Engagement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($users as $user)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-red-50 to-indigo-50 border border-slate-100 flex items-center justify-center text-sm font-black text-[#8B0000] shadow-sm transform group-hover:rotate-6 transition-transform">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors uppercase tracking-wide">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-500 font-medium italic">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-xs font-bold font-mono text-slate-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm transition-all group-hover:bg-emerald-100">
                                            {{ $user->reports->count() }} Submissions
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
