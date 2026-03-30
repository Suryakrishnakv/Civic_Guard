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
                <div class="flex items-center gap-6">
                    <div>
                        @php
                            $deptLabel = Auth::user()->isOfficer() ? Auth::user()->department : __('System Overview');
                        @endphp
                        <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                            {{ $deptLabel }}
                        </h2>
                        <div class="flex items-center gap-2 mt-1">
                            @if(Auth::user()->isAdmin())
                                <span class="text-xs font-black text-[#8B0000]/60 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded shadow-sm border border-red-100/50">Admin Console</span>
                                <span class="text-slate-300">|</span>
                            @endif
                            <p class="text-sm text-slate-500 font-medium">Real-time monitoring and management</p>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.evaluations.index') }}" class="group relative inline-flex items-center px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl font-bold text-xs text-slate-700 uppercase tracking-widest hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm active:scale-95 overflow-hidden">
                        <span class="absolute inset-0 w-full h-full bg-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <span class="relative flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Check Evaluation
                        </span>
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="group relative inline-flex items-center px-6 py-3 bg-[#8B0000] border-2 border-[#8B0000] rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 hover:border-red-800 transition-all shadow-lg shadow-red-900/20 active:scale-95 overflow-hidden">
                        <span class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                        <span class="relative flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Broadcast Center
                        </span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 left-0 -translate-y-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-[#8B0000] rounded-full blur-[120px] opacity-[0.07] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 translate-y-1/2 translate-x-1/2 w-[500px] h-[500px] bg-indigo-500 rounded-full blur-[120px] opacity-[0.07] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            <!-- Key Metrics Grid -->
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @if(Auth::user()->isAdmin())
                    <!-- Users Card -->
                    <a href="{{ route('admin.users.index') }}" class="block group">
                        <div class="bg-white border-t-4 border-t-indigo-600 shadow-xl shadow-slate-200/50 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Citizens</p>
                                    <h3 class="text-4xl font-eagle-lake font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-none">{{ $stats['total_users'] }}</h3>
                                </div>
                                <div class="p-3.5 bg-indigo-50 border border-indigo-100 rounded-2xl text-indigo-600 group-hover:rotate-12 transition-transform shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center text-[10px] text-emerald-600 bg-emerald-50 w-fit px-2.5 py-1 rounded-lg font-black uppercase tracking-wider border border-emerald-100/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                Verified Access
                            </div>
                        </div>
                    </a>
                @endif

                <!-- Total Reports -->
                <a href="{{ route('admin.reports.manage', ['status' => 'all']) }}" class="block group">
                    <div class="bg-white border-t-4 border-t-blue-600 shadow-xl shadow-slate-200/50 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reports</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-slate-900 mt-1 leading-none">{{ $stats['total'] }}</h3>
                            </div>
                             <div class="p-3.5 bg-blue-50 border border-blue-100 rounded-2xl text-blue-600 group-hover:rotate-12 transition-transform shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                         <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest flex items-center gap-1.5">
                            <span class="w-1 h-3 bg-blue-200 rounded-full"></span>
                            Full Database Records
                         </p>
                    </div>
                </a>

                <!-- Pending -->
                <a href="{{ route('admin.reports.manage', ['status' => 'pending']) }}" class="block group">
                    <div class="bg-white border-t-4 border-t-amber-500 shadow-xl shadow-slate-200/50 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending Action</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-[#8B0000] mt-1 leading-none">{{ $stats['pending'] }}</h3>
                            </div>
                             <div class="p-3.5 bg-amber-50 border border-amber-100 rounded-2xl text-amber-600 group-hover:rotate-12 transition-transform shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                         <p class="text-[10px] text-amber-600 font-black uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                            Requires Review
                         </p>
                    </div>
                </a>

                <!-- Resolved -->
                <a href="{{ route('admin.reports.manage', ['status' => 'resolved']) }}" class="block group">
                    <div class="bg-white border-t-4 border-t-emerald-500 shadow-xl shadow-slate-200/50 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resolved Cases</p>
                                <h3 class="text-4xl font-eagle-lake font-bold text-emerald-600 mt-1 leading-none">{{ $stats['resolved'] }}</h3>
                            </div>
                             <div class="p-3.5 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-600 group-hover:rotate-12 transition-transform shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full mt-2 overflow-hidden ring-1 ring-emerald-50 shadow-inner">
                            <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($stats['resolved'] / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                
                <!-- Left Column: Department Reports & Analytics -->
                <div class="xl:col-span-2 space-y-8">
                    
                    <!-- Reports List Grouped by Department (Active Cases) -->
                    <div class="space-y-8">
                         @php
                             $activeReports = $reports->whereIn('status', ['Pending', 'In Progress']);
                             $completedReports = $reports->whereIn('status', ['Repaired', 'Rejected']);
                         @endphp

                         @if($activeReports->count() > 0)
                             <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-10 bg-amber-500 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                                    <div>
                                        <h3 class="text-2xl font-eagle-lake font-bold text-slate-900 leading-tight">Active Cases</h3>
                                    </div>
                                </div>
                                <div class="px-5 py-2 bg-amber-50 rounded-2xl border border-amber-100/50 flex items-center gap-2 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span class="text-xs font-black text-amber-700 uppercase tracking-widest">{{ $activeReports->count() }} Pending</span>
                                </div>
                             </div>
                         @endif

                         @forelse($activeReports->groupBy('department') as $department => $deptReports)
                            <div class="bg-white shadow-xl shadow-slate-200/40 rounded-[2.5rem] overflow-hidden border border-slate-100 transition-all hover:shadow-2xl hover:shadow-slate-200/50 mb-10">
                                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center relative">
                                    <div class="absolute top-0 right-0 p-4 opacity-[0.03] pointer-events-none">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                                    </div>
                                    <div class="flex items-center gap-4 relative z-10">
                                        <div class="p-2.5 rounded-2xl shadow-sm border border-slate-100 bg-white group-hover:scale-105 transition-transform">
                                            @if(str_contains($department, 'Health'))
                                                <img src="{{ asset('images/departments/health.png') }}" alt="Health" class="w-10 h-10 object-contain drop-shadow-sm" onerror="this.onerror=null;this.src='{{ asset('images/departments/health.svg') }}';">
                                            @elseif(str_contains($department, 'Engineering'))
                                                <img src="{{ asset('images/departments/engineering.svg') }}" alt="Engineering" class="w-10 h-10 object-contain drop-shadow-sm">
                                            @elseif(str_contains($department, 'Waste'))
                                                <img src="{{ asset('images/departments/waste.svg') }}" alt="Waste" class="w-10 h-10 object-contain drop-shadow-sm">
                                            @elseif(str_contains($department, 'Electrical'))
                                                <img src="{{ asset('images/departments/electrical.svg') }}" alt="Electrical" class="w-10 h-10 object-contain drop-shadow-sm">
                                            @elseif(str_contains($department, 'Water'))
                                                <img src="{{ asset('images/departments/water.svg') }}" alt="Water" class="w-10 h-10 object-contain_drop-shadow-sm">
                                            @elseif(str_contains($department, 'Planning'))
                                                <img src="{{ asset('images/departments/planning.svg') }}" alt="Planning" class="w-10 h-10 object-contain drop-shadow-sm">
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900 font-eagle-lake tracking-tight">
                                                {{ $department }}
                                            </h3>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Department Operations</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-xl text-xs font-black bg-slate-900 text-white shadow-lg shadow-slate-900/10 relative z-10">
                                        {{ $deptReports->count() }} Cases
                                    </span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-100">
                                         <thead class="bg-slate-50/50">
                                            <tr>
                                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Issue & Location</th>
                                                <th scope="col" class="px-4 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Reporter</th>
                                                <th scope="col" class="px-4 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Proof</th>
                                                <th scope="col" class="px-4 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider w-32">Status</th>
                                                <th scope="col" class="pr-6 pl-4 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach ($deptReports as $report)
                                                <tr class="hover:bg-slate-50/80 transition-all group">
                                                    <td class="px-6 py-6">
                                                        <div class="text-sm font-semibold text-slate-900">{{ $report->category }}</div>
                                                        <div class="text-xs text-slate-500 mt-1 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                            {{ $report->location }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                                                {{ substr($report->user->name, 0, 1) }}
                                                            </div>
                                                            <div class="ml-3">
                                                                <div class="text-sm font-medium text-slate-900">{{ $report->user->name }}</div>
                                                                <div class="text-xs text-slate-500">{{ $report->created_at->diffForHumans() }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                                        @if($report->resolution_photo_path)
                                                            <div class="relative inline-block group/zoom">
                                                                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-400 rounded-lg blur opacity-0 group-hover/zoom:opacity-30 transition duration-500"></div>
                                                                <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                                                                    <img src="{{ $report->resolution_photo_content ?? asset('storage/' . $report->resolution_photo_path) }}" 
                                                                         alt="Proof" 
                                                                         class="w-full h-full object-cover transform transition duration-500 group-hover/zoom:scale-150 cursor-pointer"
                                                                         onclick="window.open(this.src, '_blank')">
                                                                </div>
                                                                <div class="absolute -top-1 -right-1 flex h-3 w-3">
                                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border border-white"></span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-300">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="relative min-w-[120px]">
                                                                <select name="status" onchange="this.form.submit()" class="block w-full text-xs font-semibold rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 py-1.5 pl-3 pr-8 cursor-pointer hover:bg-slate-50 transition-all duration-200">
                                                                    <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                    <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                                    <option value="Repaired" {{ $report->status == 'Repaired' ? 'selected' : '' }}>Repaired</option>
                                                                    <option value="Rejected" {{ $report->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </td>
                                                     <td class="pr-6 pl-4 py-6 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end gap-3 text-slate-400">
                                                            <a href="{{ route('reports.show', $report) }}" class="p-2 hover:bg-white rounded-xl hover:text-indigo-600 transition-all shadow-sm border border-transparent hover:border-indigo-100" title="View Details">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.237 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </a>
                                                            @if(Auth::user()->isAdmin())
                                                            <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-2 hover:bg-red-50 rounded-xl hover:text-red-500 transition-all shadow-sm border border-transparent hover:border-red-100" title="Delete Record">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                             @if($activeReports->count() == 0)
                            <div class="bg-white border border-slate-200 rounded-2xl p-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900">All Clear</h3>
                                <p class="text-slate-500 mt-2 max-w-sm mx-auto">There are no pending reports. Good work!</p>
                            </div>
                            @endif
                        @endforelse


                     </div> <!-- End space-y-8 inner -->
                </div> <!-- End xl:col-span-2 -->

                <!-- Sidebar: Critical Stats & Actions -->
                <div class="xl:col-span-1 space-y-8">
                    
                    <!-- Critical Alerts -->

                    <!-- Status Distribution Chart (Review Overview) -->
                    <div class="bg-white shadow-xl shadow-slate-200/40 rounded-[2.5rem] border border-slate-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-slate-900 font-eagle-lake tracking-tight">Status Overview</h3>
                        </div>
                         <div class="relative h-64">
                               <canvas id="statusChartDesktop"></canvas>
                               <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                   <div class="text-center">
                                       <span class="block text-4xl font-eagle-lake font-bold text-slate-900 leading-none">{{ $stats['total'] }}</span>
                                       <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Total Cases</span>
                                   </div>
                               </div>
                           </div>
                           <div class="mt-8 grid grid-cols-2 gap-4">
                                 <div class="bg-slate-50/50 rounded-2xl p-4 text-center border border-slate-100/50">
                                     <span class="block text-2xl font-eagle-lake font-bold text-slate-900">{{ number_format(($stats['resolved'] / max($stats['total'], 1)) * 100, 0) }}%</span>
                                     <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Efficiency</span>
                                 </div>
                                 <div class="bg-slate-50/50 rounded-2xl p-4 text-center border border-slate-100/50">
                                     <span class="block text-2xl font-eagle-lake font-bold text-slate-900">{{ $stats['pending'] }}</span>
                                     <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Pending</span>
                                 </div>
                           </div>
                    </div>


            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div id="announcementModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAnnouncementModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <div class="px-6 pt-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 font-eagle-lake" id="modalTitle">
                                Announcement
                            </h3>
                            <div class="mt-1">
                                <p class="text-xs font-mono text-slate-500" id="modalDate">Date</p>
                            </div>
                            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-600 whitespace-pre-line leading-relaxed" id="modalMessage">
                                    Message content...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeAnnouncementModal()">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartConfig = {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Resolved'],
                    datasets: [{
                        data: [{{ $stats['pending'] }}, {{ $stats['in_progress'] }}, {{ $stats['resolved'] }}],
                        backgroundColor: ['#f59e0b', '#3b82f6', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false 
                        }
                    },
                    cutout: '80%'
                }
            };

            const ctxDesktop = document.getElementById('statusChartDesktop');
            if (ctxDesktop) {
                new Chart(ctxDesktop.getContext('2d'), chartConfig);
            }
        });

        function openAnnouncement(id, title, message, date) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('announcementModal').classList.remove('hidden');
            
            if (id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).catch(error => console.error('Error:', error));
            }
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').classList.add('hidden');
        }
    </script>
</x-authenticated-layout>
