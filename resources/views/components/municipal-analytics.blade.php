@props(['analytics'])

@php
    $kpis = $analytics['kpis'] ?? [];
    $deptStats = $analytics['department_stats'] ?? [];
    $categoryStats = $analytics['category_stats'] ?? [];
    $officerStats = $analytics['officer_stats'] ?? [];
    $slaStats = $analytics['sla_stats'] ?? [];
    $monthlyTrend = $analytics['monthly_trend'] ?? [];
    $recentActivities = $analytics['recent_activities'] ?? [];
    $resTimes = $analytics['resolution_time_stats'] ?? [];
    $filters = $analytics['filters'] ?? [];
    $charts = $analytics['charts'] ?? [];
@endphp

<div class="space-y-8" x-data="municipalAnalytics()">

    <!-- Executive Municipal Filter & Export Toolbar -->
    <div class="bg-white border border-slate-200/80 shadow-xl shadow-slate-200/40 rounded-[2rem] p-6 lg:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg>
        </div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-50 text-[#8B0000] border border-red-100">
                        Municipal Intelligence
                    </span>
                    <span class="text-slate-300">|</span>
                    <span class="text-xs font-semibold text-slate-500">Live PostgreSQL Analytics</span>
                </div>
                <h3 class="text-2xl font-bold font-eagle-lake text-slate-900 mt-1">Advanced Municipal Analytics Dashboard</h3>
                <p class="text-xs text-slate-500 mt-0.5">Comprehensive real-time civic indicators, department resolution efficiency & SLA monitoring</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.analytics.export.excel', request()->all()) }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-700/20 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel / CSV
                </a>

                <a href="{{ route('admin.analytics.export.pdf', request()->all()) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white rounded-xl text-xs font-bold shadow-md shadow-slate-900/20 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print / PDF Report
                </a>
            </div>
        </div>

        <!-- Filter Controls Form -->
        <form method="GET" action="{{ url()->current() }}" class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            <!-- Year Filter -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Fiscal Year</label>
                <select name="year" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] bg-slate-50 text-slate-700 py-2 px-3">
                    <option value="all" {{ ($filters['year'] ?? '') === 'all' ? 'selected' : '' }}>All Years</option>
                    @foreach($filters['available_years'] ?? [] as $yr)
                        <option value="{{ $yr }}" {{ (string)($filters['year'] ?? '') === (string)$yr ? 'selected' : '' }}>Year {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Month</label>
                <select name="month" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] bg-slate-50 text-slate-700 py-2 px-3">
                    <option value="all" {{ ($filters['month'] ?? '') === 'all' ? 'selected' : '' }}>All Months</option>
                    @foreach($filters['months'] ?? [] as $mNum => $mLabel)
                        <option value="{{ $mNum }}" {{ (string)($filters['month'] ?? '') === (string)$mNum ? 'selected' : '' }}>{{ $mLabel }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Quick Range Filter -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Quick Date Range</label>
                <select name="range" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] bg-slate-50 text-slate-700 py-2 px-3">
                    <option value="all" {{ ($filters['range'] ?? '') === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ ($filters['range'] ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ ($filters['range'] ?? '') === 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ ($filters['range'] ?? '') === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_30_days" {{ ($filters['range'] ?? '') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="last_90_days" {{ ($filters['range'] ?? '') === 'last_90_days' ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="this_year" {{ ($filters['range'] ?? '') === 'this_year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Department</label>
                <select name="department" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-[#8B0000] focus:ring-[#8B0000] bg-slate-50 text-slate-700 py-2 px-3">
                    <option value="all" {{ ($filters['department'] ?? '') === 'all' ? 'selected' : '' }}>All Departments</option>
                    @foreach($filters['departments'] ?? [] as $dept)
                        <option value="{{ $dept }}" {{ ($filters['department'] ?? '') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Reset Button -->
            <div class="flex items-end">
                <a href="{{ url()->current() }}" class="w-full inline-flex items-center justify-center py-2 px-4 text-xs font-bold text-slate-600 hover:text-[#8B0000] bg-slate-100 hover:bg-red-50 border border-slate-200 rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- 1. Executive Municipal KPI Cards (Requirement 1 & 4) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-5">
        <!-- Total Complaints -->
        <div class="bg-white border-t-4 border-t-slate-800 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Reports</p>
                    <h4 class="text-3xl font-eagle-lake font-bold text-slate-900 mt-1 leading-none">{{ $kpis['total'] ?? 0 }}</h4>
                </div>
                <div class="p-2.5 bg-slate-100 rounded-xl text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-slate-500 font-semibold">
                <span>100% City Intake</span>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white border-t-4 border-t-amber-500 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Pending Review</p>
                    <h4 class="text-3xl font-eagle-lake font-bold text-amber-600 mt-1 leading-none">{{ $kpis['pending'] ?? 0 }}</h4>
                </div>
                <div class="p-2.5 bg-amber-50 rounded-xl text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-amber-700 font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                <span>{{ $kpis['pending_percent'] ?? 0 }}% of total</span>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white border-t-4 border-t-blue-500 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">In Progress</p>
                    <h4 class="text-3xl font-eagle-lake font-bold text-blue-600 mt-1 leading-none">{{ $kpis['in_progress'] ?? 0 }}</h4>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-blue-700 font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                <span>{{ $kpis['in_progress_percent'] ?? 0 }}% active work</span>
            </div>
        </div>

        <!-- Repaired / Resolved -->
        <div class="bg-white border-t-4 border-t-emerald-500 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Repaired</p>
                    <h4 class="text-3xl font-eagle-lake font-bold text-emerald-600 mt-1 leading-none">{{ $kpis['repaired'] ?? 0 }}</h4>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-emerald-700 font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                <span>{{ $kpis['repaired_percent'] ?? 0 }}% resolution rate</span>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white border-t-4 border-t-slate-400 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Rejected / Spam</p>
                    <h4 class="text-3xl font-eagle-lake font-bold text-slate-700 mt-1 leading-none">{{ $kpis['rejected'] ?? 0 }}</h4>
                </div>
                <div class="p-2.5 bg-slate-100 rounded-xl text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-slate-500 font-semibold">
                <span>{{ $kpis['rejected_percent'] ?? 0 }}% filtered</span>
            </div>
        </div>

        <!-- Resolution Efficiency Rate -->
        <div class="bg-gradient-to-br from-[#8B0000] to-red-900 text-white shadow-xl shadow-red-900/20 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-red-200 uppercase tracking-widest">Efficiency</p>
                    <h4 class="text-3xl font-eagle-lake font-bold mt-1 leading-none">{{ $kpis['efficiency'] ?? 0 }}%</h4>
                </div>
                <div class="p-2.5 bg-white/10 rounded-xl text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-black/20 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-400 h-1.5 rounded-full" style="width: {{ min($kpis['efficiency'] ?? 0, 100) }}%"></div>
                </div>
                <span class="text-[10px] text-red-100 font-medium mt-1.5 block">(Repaired / Total) × 100</span>
            </div>
        </div>

        <!-- Average Resolution Time -->
        <div class="bg-white border-t-4 border-t-indigo-500 shadow-lg shadow-slate-200/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Avg Resolution</p>
                    <h4 class="text-xl font-eagle-lake font-bold text-slate-900 mt-1 leading-tight">{{ $resTimes['formatted'] ?? 'N/A' }}</h4>
                </div>
                <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] text-indigo-700 font-semibold">
                <span>{{ $resTimes['count_resolved'] ?? 0 }} completed repairs</span>
            </div>
        </div>
    </div>

    <!-- 2. Interactive Charts Grid (Requirements 3, 5, 9) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Monthly Complaint Trends Chart (Line Chart with dual dataset) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-6 border-b border-slate-100 gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000]"></span>
                        <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Monthly Complaint Trends</h4>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Complaints filed vs. repaired across {{ $monthlyTrend['year'] ?? now()->year }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center text-[11px] font-bold text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-[#8B0000] mr-1.5"></span> Filed
                    </span>
                    <span class="flex items-center text-[11px] font-bold text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 mr-1.5"></span> Repaired
                    </span>
                    <span class="flex items-center text-[11px] font-bold text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-amber-400 mr-1.5"></span> Active
                    </span>
                </div>
            </div>
            <div class="relative h-72 sm:h-80">
                <canvas id="monthlyTrendChartCanvas"></canvas>
            </div>
        </div>

        <!-- Status Distribution Doughnut Chart -->
        <div class="bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-6 sm:p-8 flex flex-col justify-between">
            <div class="pb-6 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Status Distribution</h4>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Breakdown of current municipal case pipeline</p>
            </div>

            <div class="relative h-56 my-auto">
                <canvas id="statusDoughnutChartCanvas"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="text-center">
                        <span class="block text-3xl font-eagle-lake font-bold text-slate-900 leading-none">{{ $kpis['total'] ?? 0 }}</span>
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">Total Reports</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-100 text-xs">
                <div class="flex items-center justify-between p-2 rounded-xl bg-amber-50/50">
                    <span class="font-bold text-amber-700">Pending:</span>
                    <span class="font-black text-amber-900">{{ $kpis['pending'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-blue-50/50">
                    <span class="font-bold text-blue-700">In Progress:</span>
                    <span class="font-black text-blue-900">{{ $kpis['in_progress'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/50">
                    <span class="font-bold text-emerald-700">Repaired:</span>
                    <span class="font-black text-emerald-900">{{ $kpis['repaired'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-xl bg-slate-100/70">
                    <span class="font-bold text-slate-600">Rejected:</span>
                    <span class="font-black text-slate-800">{{ $kpis['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Department-wise Distribution & Efficiency Section (Requirement 2 & 9) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Department Distribution Bar Chart -->
        <div class="xl:col-span-5 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-6 sm:p-8">
            <div class="pb-6 mb-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Department Caseload</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Complaints volume distribution across 6 departments</p>
                </div>
            </div>
            <div class="relative h-72 sm:h-80">
                <canvas id="departmentDistributionChartCanvas"></canvas>
            </div>
        </div>

        <!-- Department Performance & Resolution Efficiency Matrix Table -->
        <div class="xl:col-span-7 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Department Resolution Efficiency Matrix</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Calculated as (Repaired Reports / Total Reports) × 100</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-red-50 text-[#8B0000] border border-red-100 rounded-xl">
                    {{ count($deptStats) }} Departments
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/70 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">Department</th>
                            <th class="px-3 py-4 text-center">Total</th>
                            <th class="px-3 py-4 text-center">Repaired</th>
                            <th class="px-3 py-4 text-center">Active</th>
                            <th class="px-6 py-4 text-left">Resolution Efficiency</th>
                            <th class="px-4 py-4 text-center">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($deptStats as $d)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $d['department'] }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">Avg resolution: {{ $d['avg_hours'] }}h ({{ $d['avg_days'] }} days)</div>
                                </td>
                                <td class="px-3 py-4 text-center font-bold text-slate-900">{{ $d['total'] }}</td>
                                <td class="px-3 py-4 text-center font-bold text-emerald-600">{{ $d['repaired'] }}</td>
                                <td class="px-3 py-4 text-center font-bold text-amber-600">{{ $d['active'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="h-2 rounded-full {{ $d['efficiency'] >= 70 ? 'bg-emerald-500' : ($d['efficiency'] >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                                 style="width: {{ min($d['efficiency'], 100) }}%"></div>
                                        </div>
                                        <span class="font-black text-slate-900 w-12 text-right">{{ $d['efficiency'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $d['status_level'] === 'High' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($d['status_level'] === 'Moderate' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                        {{ $d['status_level'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-medium">No department reports found in filter range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Category-wise Analysis & Officer Performance (Requirements 5 & 6) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- Category Analysis (Left 6 cols) -->
        <div class="xl:col-span-6 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Category-Wise Complaint Analysis</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Issue categories, complaint volumes & municipal severity standards</p>
                </div>
            </div>

            <div class="p-6 border-b border-slate-100">
                <div class="relative h-60">
                    <canvas id="categoryChartCanvas"></canvas>
                </div>
            </div>

            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/70 text-slate-400 font-bold uppercase tracking-wider sticky top-0 bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-3 py-3 text-center">Total</th>
                            <th class="px-3 py-3 text-center">Repaired</th>
                            <th class="px-3 py-3 text-center">Severity</th>
                            <th class="px-4 py-3 text-right">SLA Standard</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @foreach(array_slice($categoryStats, 0, 10) as $cat)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="font-bold text-slate-900">{{ $cat['name'] }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $cat['department'] }}</div>
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-slate-900">{{ $cat['total'] }}</td>
                                <td class="px-3 py-3 text-center font-bold text-emerald-600">{{ $cat['repaired'] }}</td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        {{ $cat['severity'] === 'High' ? 'bg-red-50 text-red-700 border border-red-200' : ($cat['severity'] === 'Medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $cat['severity'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600 font-semibold">{{ $cat['sla_days'] }} Days</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Officer Performance and Workload Statistics (Right 6 cols) -->
        <div class="xl:col-span-6 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Officer Workload & Resolution Efficiency</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Municipal field officers caseload balance and resolution metrics</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl">
                    {{ count($officerStats) }} Officers
                </span>
            </div>

            <div class="overflow-x-auto max-h-[480px] overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/70 text-slate-400 font-bold uppercase tracking-wider sticky top-0 bg-white">
                        <tr>
                            <th class="px-6 py-4 text-left">Officer Name & Dept</th>
                            <th class="px-3 py-4 text-center">Assigned</th>
                            <th class="px-3 py-4 text-center">Repaired</th>
                            <th class="px-4 py-4 text-right">Efficiency</th>
                            <th class="px-4 py-4 text-center">Workload Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($officerStats as $off)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $off['name'] }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $off['department'] }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $off['email'] }}</div>
                                </td>
                                <td class="px-3 py-4 text-center font-black text-slate-900 text-sm">{{ $off['total_assigned'] }}</td>
                                <td class="px-3 py-4 text-center font-bold text-emerald-600 text-sm">{{ $off['repaired'] }}</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="font-black text-slate-900 text-sm">{{ $off['efficiency'] }}%</span>
                                    <div class="w-16 ml-auto bg-slate-100 h-1.5 rounded-full mt-1 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $off['efficiency'] >= 70 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ min($off['efficiency'], 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $off['workload'] === 'Optimal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($off['workload'] === 'Moderate Workload' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                        {{ $off['workload'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">No officers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 5. SLA Overdue Summary & Recent Activities (Requirements 7 & 8) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- SLA Overdue Complaint Summary (Left 7 cols) -->
        <div class="xl:col-span-7 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-ping"></span>
                        <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">SLA Overdue Complaint Summary</h4>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Complaints that have breached their target municipal resolution window</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black px-3 py-1 rounded-xl bg-red-100 text-red-800 border border-red-200">
                        {{ $slaStats['overdue_count'] ?? 0 }} Breached
                    </span>
                    <span class="text-xs font-black px-3 py-1 rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ $slaStats['compliance_rate'] ?? 100 }}% On Time
                    </span>
                </div>
            </div>

            <!-- Overdue List Table -->
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/70 text-slate-400 font-bold uppercase tracking-wider sticky top-0 bg-white">
                        <tr>
                            <th class="px-6 py-3.5 text-left">Case ID & Issue</th>
                            <th class="px-4 py-3.5 text-left">Department</th>
                            <th class="px-3 py-3.5 text-center">Status</th>
                            <th class="px-3 py-3.5 text-center">Deadline</th>
                            <th class="px-4 py-3.5 text-right">Overdue</th>
                            <th class="px-4 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($slaStats['overdue_list'] ?? [] as $od)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-red-700">{{ $od['case_number'] }}</div>
                                    <div class="text-slate-900 font-semibold">{{ $od['category'] }}</div>
                                    <div class="text-[10px] text-slate-400">Reporter: {{ $od['reporter_name'] }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 font-medium">{{ $od['department'] }}</td>
                                <td class="px-3 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $od['status'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-center text-slate-500 font-mono text-[11px]">{{ $od['deadline'] }}</td>
                                <td class="px-4 py-3.5 text-right font-black text-red-600">
                                    +{{ $od['days_overdue'] }}d ({{ $od['hours_overdue'] }}h)
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <a href="{{ route('reports.show', $od['id']) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="font-bold text-slate-800">Excellent SLA Compliance!</p>
                                    <p class="text-xs text-slate-500 mt-1">No municipal complaints currently exceed their SLA resolution deadline.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Complaint Activities and Status Updates (Right 5 cols) -->
        <div class="xl:col-span-5 bg-white border border-slate-200/70 shadow-xl shadow-slate-200/30 rounded-[2.5rem] overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold font-eagle-lake text-slate-900 tracking-tight">Recent Activity Log</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time status transitions and actions audit trail</p>
                </div>
            </div>

            <div class="p-6 divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $act)
                    <div class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-3.5 group">
                        <div class="mt-0.5 p-2 rounded-xl shrink-0 
                            {{ $act['status'] === 'Repaired' ? 'bg-emerald-50 text-emerald-600' : ($act['status'] === 'In Progress' ? 'bg-blue-50 text-blue-600' : ($act['status'] === 'Rejected' ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-600')) }}">
                            @if($act['status'] === 'Repaired')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @elseif($act['status'] === 'In Progress')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-xs text-slate-900 truncate">{{ $act['action'] }}</span>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $act['time'] }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">
                                <span class="font-mono font-bold text-red-700">{{ $act['case_number'] }}</span> &bull; {{ $act['category'] }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5 italic">By: {{ $act['actor'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-slate-400">
                        No recent activity recorded.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<!-- Alpine & Chart.js Initialization Script -->
<script>
function municipalAnalytics() {
    return {
        charts: @json($charts),
        chartInstances: {},

        init() {
            this.$nextTick(() => {
                this.renderAllCharts();
            });
        },

        renderAllCharts() {
            this.renderTrendChart();
            this.renderStatusChart();
            this.renderDepartmentChart();
            this.renderCategoryChart();
        },

        renderTrendChart() {
            const canvas = document.getElementById('monthlyTrendChartCanvas');
            if (!canvas) return;

            const t = this.charts.trend;
            if (!t) return;

            if (this.chartInstances.trend) this.chartInstances.trend.destroy();

            const ctx = canvas.getContext('2d');
            this.chartInstances.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: t.labels,
                    datasets: [
                        {
                            label: 'Complaints Filed',
                            data: t.filed,
                            borderColor: '#8B0000',
                            backgroundColor: 'rgba(139, 0, 0, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#8B0000',
                            pointRadius: 4,
                        },
                        {
                            label: 'Complaints Repaired',
                            data: t.repaired,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4,
                        },
                        {
                            label: 'Active Cases',
                            data: t.pending,
                            borderColor: '#f59e0b',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.35,
                            pointRadius: 3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0, font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        },

        renderStatusChart() {
            const canvas = document.getElementById('statusDoughnutChartCanvas');
            if (!canvas) return;

            const s = this.charts.status;
            if (!s) return;

            if (this.chartInstances.status) this.chartInstances.status.destroy();

            const ctx = canvas.getContext('2d');
            this.chartInstances.status = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: s.labels,
                    datasets: [{
                        data: s.data,
                        backgroundColor: s.colors,
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    cutout: '76%'
                }
            });
        },

        renderDepartmentChart() {
            const canvas = document.getElementById('departmentDistributionChartCanvas');
            if (!canvas) return;

            const d = this.charts.department;
            if (!d) return;

            if (this.chartInstances.dept) this.chartInstances.dept.destroy();

            const ctx = canvas.getContext('2d');
            this.chartInstances.dept = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: d.labels,
                    datasets: [
                        {
                            label: 'Total Cases',
                            data: d.totals,
                            backgroundColor: 'rgba(15, 23, 42, 0.85)',
                            borderRadius: 6,
                        },
                        {
                            label: 'Repaired Cases',
                            data: d.repaired,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 10 } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0, font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 9 }, maxRotation: 45 }
                        }
                    }
                }
            });
        },

        renderCategoryChart() {
            const canvas = document.getElementById('categoryChartCanvas');
            if (!canvas) return;

            const c = this.charts.category;
            if (!c) return;

            if (this.chartInstances.category) this.chartInstances.category.destroy();

            const ctx = canvas.getContext('2d');
            this.chartInstances.category = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: c.labels,
                    datasets: [{
                        label: 'Report Volume',
                        data: c.totals,
                        backgroundColor: '#8B0000',
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0, font: { size: 10 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }
    };
}
</script>
