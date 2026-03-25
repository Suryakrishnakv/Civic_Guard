<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -my-4 px-4 sm:px-6 lg:px-8 py-8 overflow-hidden group">
            <!-- Premium Background with Mesh & Gradients -->
            <div class="absolute inset-0 bg-white"></div>
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-red-50/40"></div>
            
            <!-- Animated Glows -->
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-80 h-80 bg-indigo-500 rounded-full blur-[100px] opacity-[0.1] group-hover:opacity-[0.15] transition-opacity duration-700"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-80 h-80 bg-[#8B0000] rounded-full blur-[100px] opacity-[0.08] group-hover:opacity-[0.12] transition-opacity duration-700"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#8B0000]/5 text-[#8B0000] shadow-sm border border-[#8B0000]/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                            {{ __('Dashboard Overview') }}
                        </h2>
                    </div>
                    <p class="text-sm text-slate-500 flex items-center gap-2 font-medium px-1">
                        <span class="text-slate-400">Welcome ,</span>
                        <span class="font-bold text-[#8B0000] underline decoration-[#8B0000]/20 underline-offset-4">{{ Auth::user()->name }}</span>
                        <span class="text-slate-300">|</span>
                        <span class="flex items-center gap-1.5 text-slate-500">
                             <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Active Session
                        </span>
                    </p>
                </div>
                
                <a href="{{ route('reports.create') }}" class="group/btn relative inline-flex items-center justify-center px-8 py-4 bg-[#8B0000] text-white text-sm font-bold tracking-wider rounded-2xl shadow-2xl shadow-red-900/30 hover:shadow-red-900/50 transform hover:-translate-y-1 active:scale-95 transition-all duration-300 overflow-hidden uppercase">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-red-700 via-[#8B0000] to-red-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></span>
                    <span class="relative flex items-center">
                        <svg class="w-5 h-5 mr-2.5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Submit New Report
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[120px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8B0000] rounded-full blur-[120px] opacity-10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10 relative z-10">
            
            <!-- Subscription CTA for Citizens -->
            @if(!Auth::user()->is_subscribed)
                <div class="bg-white border border-[#8B0000]/20 rounded-2xl shadow-sm overflow-hidden relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-transparent pointer-events-none"></div>
                    <div class="px-6 py-5 relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4 text-center md:text-left">
                            <div class="w-12 h-12 rounded-full bg-[#8B0000]/10 flex items-center justify-center text-[#8B0000] shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-eagle-lake">Stay Informed!</h3>
                                <p class="text-sm text-slate-600">Subscribe to receive critical civic alerts and official broadcasts directly in your inbox.</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-2.5 bg-[#8B0000] hover:bg-red-800 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all transform active:scale-95 whitespace-nowrap">
                            Enable Email Alerts
                        </a>
                    </div>
                </div>
            @endif


            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Total Reports -->
                <a href="{{ route('reports.citizen', ['status' => 'all']) }}" class="group block">
                    <div class="bg-gradient-to-br from-white to-indigo-50/50 border-t-4 border-t-[#8B0000] border-x border-b border-slate-100 shadow-sm rounded-2xl p-6 transition-all duration-300 transform hover:scale-[1.02]">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reports</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-slate-900 mt-2 group-hover:text-[#8B0000] transition-colors leading-none">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-[#8B0000] shadow-sm group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-[11px] text-slate-500">
                            <span class="text-emerald-600 font-bold flex items-center mr-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                Updated just now
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Pending -->
                <a href="{{ route('reports.citizen', ['status' => 'pending']) }}" class="group block">
                    <div class="bg-gradient-to-br from-white to-amber-50/50 border-t-4 border-t-amber-500 border-x border-b border-slate-100 shadow-sm rounded-2xl p-6 transition-all duration-300 transform hover:scale-[1.02]">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">In Progress</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-slate-900 mt-2 group-hover:text-amber-600 transition-colors leading-none">{{ $stats['pending'] }}</h3>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-[11px] text-slate-500">
                            <span class="bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full font-bold">Attention required</span>
                        </div>
                    </div>
                </a>

                <!-- Resolved -->
                <a href="{{ route('reports.citizen', ['status' => 'resolved']) }}" class="group block">
                    <div class="bg-gradient-to-br from-white to-emerald-50/50 border-t-4 border-t-emerald-500 border-x border-b border-slate-100 shadow-sm rounded-2xl p-6 transition-all duration-300 transform hover:scale-[1.02]">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resolved</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-slate-900 mt-2 group-hover:text-emerald-600 transition-colors leading-none">{{ $stats['resolved'] }}</h3>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-[11px] text-slate-500">
                            <span class="text-emerald-600 font-bold flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Great job!
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Reports Section -->
            <div class="bg-white border border-slate-200/60 shadow-2xl shadow-slate-200/50 rounded-[2rem] overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 relative">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-eagle-lake font-bold text-slate-900 tracking-tight">Recent Reports</h3>
                        <p class="text-sm text-slate-400 font-medium mt-1">A summary of your active community submissions.</p>
                    </div>
                    @if($reports->count() > 0)
                    <a href="{{ route('reports.citizen', ['status' => 'all']) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B0000] transition-colors">
                        View All Reports
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="pl-8 pr-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Issue</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                <th scope="col" class="pr-8 pl-6 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($reports->take(8) as $report)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="pl-8 pr-6 py-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-2xl shadow-sm group-hover:scale-105 transition-transform duration-300">
                                                @if(str_contains($report->department, 'Health')) 🏥
                                                @elseif(str_contains($report->department, 'Engineering')) 🛣️
                                                @elseif(str_contains($report->department, 'Waste')) 🗑️
                                                @elseif(str_contains($report->department, 'Electrical')) 💡
                                                @elseif(str_contains($report->department, 'Water') || str_contains($report->department, 'Sewer')) 🚰
                                                @else 📝
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-base font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors">{{ $report->category }}</div>
                                                <div class="text-sm text-slate-500 flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ Str::limit($report->location, 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="relative inline-flex h-3 w-3 mr-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $report->status === 'Pending' ? 'bg-amber-400' : ($report->status === 'In Progress' ? 'bg-blue-400' : ($report->status === 'Repaired' ? 'bg-emerald-400' : 'bg-red-400')) }}"></span>
                                              <span class="relative inline-flex rounded-full h-3 w-3 {{ $report->status === 'Pending' ? 'bg-amber-500' : ($report->status === 'In Progress' ? 'bg-blue-500' : ($report->status === 'Repaired' ? 'bg-emerald-500' : 'bg-red-500')) }}"></span>
                                            </span>
                                            <span class="text-sm font-semibold {{ $report->status === 'Pending' ? 'text-amber-700' : ($report->status === 'In Progress' ? 'text-blue-700' : ($report->status === 'Repaired' ? 'text-emerald-700' : 'text-red-700')) }}">
                                                {{ $report->status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">
                                        {{ $report->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="pr-8 pl-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        @php
                                            $repData = [
                                                'id' => $report->id,
                                                'category' => $report->category,
                                                'description' => $report->description,
                                                'status' => $report->status,
                                                'location' => $report->location,
                                                'created_at' => $report->created_at->format('M d, Y h:i A'),
                                                'updated_at' => $report->updated_at->format('M d, Y h:i A'),
                                                'remarks' => $report->remarks ?? 'No remarks yet.',
                                                'photo' => $report->photo_path ? asset('storage/'.$report->photo_path) : null,
                                                'res_photo' => $report->resolution_photo_path ? asset('storage/'.$report->resolution_photo_path) : null,
                                            ];
                                        @endphp
                                        <button onclick='openReportModal(@json($repData))' class="inline-flex items-center justify-center p-2 rounded-full text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all duration-300 transform group-hover:translate-x-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">No reports yet</h3>
                                            <p class="text-slate-500 max-w-xs mx-auto">Start by submitting a new report to help us improve your community.</p>
                                            <a href="{{ route('reports.create') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-[#8B0000] hover:bg-red-800 transition-colors">
                                                File Reports
                                            </a>
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
    @include('reports.detail-modal')

</x-authenticated-layout>
