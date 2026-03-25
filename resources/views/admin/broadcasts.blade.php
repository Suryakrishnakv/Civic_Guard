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
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight">
                            Broadcast Center
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium italic">Official Communication Hub</p>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="group/btn relative inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-slate-50 opacity-0 group-hover/btn:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center">
                        <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Exit to Dashboard
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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12 relative z-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Left Column: New Broadcast Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden group sticky top-8">
                        <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-red-50/50 via-white to-indigo-50/50 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">New Broadcast</h3>
                            <p class="text-[10px] text-[#8B0000] font-black uppercase tracking-widest mt-1 opacity-70">Official Dispatch</p>
                        </div>
                        
                        <div class="p-8">
                            @if (session('status'))
                                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold animate-fade-in-down">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Subject</label>
                                    <input type="text" name="title" placeholder="Critical Update..." class="block w-full rounded-2xl border-slate-100 bg-indigo-50/30 text-slate-900 placeholder-slate-300 focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm py-3 px-4 transition-all font-bold" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Audience</label>
                                    <select name="audience" class="block w-full rounded-2xl border-slate-100 bg-indigo-50/30 text-slate-900 focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm py-3 px-4 transition-all font-bold">
                                        <option value="citizens">Citizens Only</option>
                                        <option value="officers">Officers Only</option>
                                        <option value="all">Everyone</option>
                                    </select>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Message Body</label>
                                    <textarea name="message" rows="5" placeholder="Draft message..." class="block w-full rounded-2xl border-slate-100 bg-indigo-50/30 text-slate-900 placeholder-slate-300 focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm py-3 px-4 transition-all resize-none font-medium" required></textarea>
                                </div>
                                
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col gap-3">
                                    <label class="inline-flex items-center cursor-pointer group/check">
                                        <input type="checkbox" name="via_in_app" value="1" class="w-4 h-4 rounded-md border-slate-300 text-[#8B0000] focus:ring-[#8B0000] bg-white transition-all shadow-sm" checked>
                                        <span class="ml-3 text-xs font-bold text-slate-600 group-hover/check:text-slate-900 transition-colors">Notify In-App</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group/check">
                                        <input type="checkbox" name="via_email" value="1" class="w-4 h-4 rounded-md border-slate-300 text-[#8B0000] focus:ring-[#8B0000] bg-white transition-all shadow-sm" checked>
                                        <span class="ml-3 text-xs font-bold text-slate-600 group-hover/check:text-slate-900 transition-colors">Dispatch Email</span>
                                    </label>
                                </div>

                                <button type="submit" class="w-full group relative inline-flex items-center justify-center px-6 py-4 bg-[#8B0000] border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition-all shadow-xl shadow-red-900/10 active:scale-95 overflow-hidden">
                                    <span class="absolute inset-0 w-full h-full bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    <span class="relative flex items-center">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Send Broadcast
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: History -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-2xl shadow-slate-200/50 ring-1 ring-slate-200/60 rounded-[2.5rem] overflow-hidden">
                        <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-indigo-50/30 via-white to-red-50/30 flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold font-eagle-lake text-slate-900 tracking-tight">Communication History</h3>
                                <p class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-1 opacity-70">Authenticated Logs</p>
                            </div>
                            <div class="px-5 py-2 bg-white border border-slate-100 rounded-2xl shadow-sm text-xs font-bold text-slate-600">
                                <span class="text-[#8B0000]">{{ $sentBroadcasts->count() }}</span> Total Dispatches
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Subject</th>
                                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Message Content</th>
                                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Audience</th>
                                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Date Sent</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($sentBroadcasts as $broadcast)
                                        <tr class="group/row hover:bg-slate-50/80 transition-all duration-300">
                                            <td class="px-8 py-6 align-top">
                                                <div class="flex items-center">
                                                    <div class="w-1.5 h-6 bg-[#8B0000] rounded-full mr-4 opacity-40 group-hover/row:opacity-100 transition-opacity"></div>
                                                    <div class="text-sm font-bold text-slate-900 group-hover/row:text-[#8B0000] transition-colors line-clamp-2">
                                                        {{ $broadcast->title }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 align-top">
                                                <div class="text-[13px] text-slate-500 font-medium leading-relaxed max-w-md line-clamp-3 group-hover/row:text-slate-700 transition-colors">
                                                    {{ $broadcast->message }}
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 align-top">
                                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest border
                                                    {{ $broadcast->audience === 'citizens' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                                    {{ $broadcast->audience === 'officers' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                                    {{ $broadcast->audience === 'all' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : '' }}
                                                ">
                                                    {{ $broadcast->audience ?? 'Broadcast' }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-6 whitespace-nowrap text-right text-[11px] font-bold font-mono text-slate-400 transition-colors group-hover/row:text-slate-600">
                                                {{ $broadcast->created_at->format('M d, H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-20 text-center">
                                                <div class="flex flex-col items-center opacity-40">
                                                    <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                                    <p class="text-sm font-bold text-slate-400">Secure Vault Empty: No Sent Broadcasts</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-authenticated-layout>
