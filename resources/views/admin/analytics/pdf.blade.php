<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicGuard - Municipal Analytics Executive Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 text-sm p-6 sm:p-10 font-sans">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="no-print max-w-6xl mx-auto mb-8 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-bold text-slate-600 hover:text-[#8B0000] transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.analytics.export.excel', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download Excel / CSV
            </a>
            <button onclick="window.print()" class="inline-flex items-center px-5 py-2 bg-[#8B0000] hover:bg-red-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print / Save as PDF
            </button>
        </div>
    </div>

    <div class="max-w-6xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-md border border-slate-200">
        <!-- Official Municipal Header -->
        <div class="border-b-2 border-slate-900 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="w-16 h-16 rounded-full border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">CivicGuard Municipal Corporation</h1>
                    <p class="text-xs font-bold text-[#8B0000] tracking-widest uppercase">Executive Municipal Asset & Complaint Analytics Report</p>
                    <p class="text-xs text-slate-500 mt-0.5">Automated Public Infrastructure Monitoring & SLA Compliance Audit</p>
                </div>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-900">Generated:</span> {{ now()->format('M d, Y h:i A') }}</div>
                <div><span class="font-bold text-slate-900">Report Officer:</span> {{ Auth::user()->name }}</div>
                <div><span class="font-bold text-slate-900">Municipal Scope:</span> City Central District</div>
                <div><span class="font-bold text-slate-900">Status:</span> Official Record</div>
            </div>
        </div>

        <!-- 1. Executive Summary KPIs -->
        <div class="mb-10">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000] mr-2"></span>
                1. Executive Key Performance Indicators
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Complaints</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">{{ $analytics['kpis']['total'] }}</p>
                    <p class="text-[10px] text-slate-500 mt-1">100% Total Volume</p>
                </div>
                <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200">
                    <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Active Complaints</p>
                    <p class="text-3xl font-black text-amber-600 mt-1">{{ $analytics['kpis']['active'] }}</p>
                    <p class="text-[10px] text-amber-700/80 mt-1">{{ $analytics['kpis']['pending'] }} Pending / {{ $analytics['kpis']['in_progress'] }} In Progress</p>
                </div>
                <div class="bg-emerald-50/60 p-4 rounded-2xl border border-emerald-200">
                    <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Repaired / Resolved</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $analytics['kpis']['repaired'] }}</p>
                    <p class="text-[10px] text-emerald-700/80 mt-1">{{ $analytics['kpis']['repaired_percent'] }}% Resolution Share</p>
                </div>
                <div class="bg-indigo-50/60 p-4 rounded-2xl border border-indigo-200">
                    <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">Resolution Efficiency</p>
                    <p class="text-3xl font-black text-indigo-600 mt-1">{{ $analytics['kpis']['efficiency'] }}%</p>
                    <p class="text-[10px] text-indigo-700/80 mt-1">Benchmark: {{ $analytics['resolution_time_stats']['formatted'] }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                    <span class="text-xs text-slate-600 font-medium">Average Resolution Time:</span>
                    <span class="text-xs font-bold text-slate-900">{{ $analytics['resolution_time_stats']['formatted'] }}</span>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                    <span class="text-xs text-slate-600 font-medium">SLA Compliance Rate:</span>
                    <span class="text-xs font-bold {{ $analytics['sla_stats']['compliance_rate'] >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $analytics['sla_stats']['compliance_rate'] }}%
                    </span>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                    <span class="text-xs text-slate-600 font-medium">Overdue Complaints:</span>
                    <span class="text-xs font-bold text-red-600">{{ $analytics['sla_stats']['overdue_count'] }} Cases</span>
                </div>
            </div>
        </div>

        <!-- 2. Department-wise Performance Matrix -->
        <div class="mb-10">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000] mr-2"></span>
                2. Department-Wise Complaint Distribution & Resolution Efficiency
            </h2>
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-3 py-3 text-center">Total</th>
                            <th class="px-3 py-3 text-center">Repaired</th>
                            <th class="px-3 py-3 text-center">Active</th>
                            <th class="px-3 py-3 text-center">Rejected</th>
                            <th class="px-4 py-3 text-right">Efficiency Rate</th>
                            <th class="px-3 py-3 text-center">Rating</th>
                            <th class="px-4 py-3 text-right">Avg Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($analytics['department_stats'] as $dept)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $dept['department'] }}</td>
                                <td class="px-3 py-3 text-center font-semibold">{{ $dept['total'] }}</td>
                                <td class="px-3 py-3 text-center font-bold text-emerald-600">{{ $dept['repaired'] }}</td>
                                <td class="px-3 py-3 text-center font-bold text-amber-600">{{ $dept['active'] }}</td>
                                <td class="px-3 py-3 text-center text-slate-500">{{ $dept['rejected'] }}</td>
                                <td class="px-4 py-3 text-right font-black text-slate-900">{{ $dept['efficiency'] }}%</td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                        {{ $dept['status_level'] === 'High' ? 'bg-emerald-100 text-emerald-800' : ($dept['status_level'] === 'Moderate' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $dept['status_level'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600">{{ $dept['avg_hours'] }}h</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-[11px] text-slate-500 mt-2 italic">* Efficiency Formula: (Repaired Reports / Total Reports) × 100</p>
        </div>

        <!-- 3. Category-wise Analysis -->
        <div class="mb-10 page-break">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000] mr-2"></span>
                3. Category-Wise Complaint Analysis
            </h2>
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-3 py-3 text-center">Complaints</th>
                            <th class="px-3 py-3 text-center">Resolved</th>
                            <th class="px-3 py-3 text-center">Active</th>
                            <th class="px-3 py-3 text-center">Severity</th>
                            <th class="px-4 py-3 text-right">SLA Standard</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach(array_slice($analytics['category_stats'], 0, 10) as $cat)
                            <tr>
                                <td class="px-4 py-2.5 font-bold text-slate-900">{{ $cat['name'] }}</td>
                                <td class="px-4 py-2.5 text-slate-600">{{ $cat['department'] }}</td>
                                <td class="px-3 py-2.5 text-center font-bold">{{ $cat['total'] }}</td>
                                <td class="px-3 py-2.5 text-center text-emerald-600 font-bold">{{ $cat['repaired'] }}</td>
                                <td class="px-3 py-2.5 text-center text-amber-600 font-bold">{{ $cat['pending'] + $cat['in_progress'] }}</td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        {{ $cat['severity'] === 'High' ? 'bg-red-100 text-red-800' : ($cat['severity'] === 'Medium' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800') }}">
                                        {{ $cat['severity'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-slate-600">{{ $cat['sla_days'] }} Days</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Officer Performance & Workload Statistics -->
        <div class="mb-10">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000] mr-2"></span>
                4. Municipal Officer Workload & Performance
            </h2>
            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Officer</th>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-3 py-3 text-center">Assigned Cases</th>
                            <th class="px-3 py-3 text-center">Repaired</th>
                            <th class="px-3 py-3 text-center">Active Workload</th>
                            <th class="px-4 py-3 text-right">Efficiency</th>
                            <th class="px-3 py-3 text-center">Workload Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($analytics['officer_stats'] as $officer)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $officer['name'] }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $officer['department'] }}</td>
                                <td class="px-3 py-3 text-center font-bold">{{ $officer['total_assigned'] }}</td>
                                <td class="px-3 py-3 text-center font-bold text-emerald-600">{{ $officer['repaired'] }}</td>
                                <td class="px-3 py-3 text-center font-bold text-amber-600">{{ $officer['active'] }}</td>
                                <td class="px-4 py-3 text-right font-black text-slate-900">{{ $officer['efficiency'] }}%</td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                        {{ $officer['workload'] === 'Optimal' ? 'bg-emerald-100 text-emerald-800' : ($officer['workload'] === 'Moderate Workload' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $officer['workload'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. SLA Overdue Cases Summary -->
        @if(count($analytics['sla_stats']['overdue_list']) > 0)
        <div class="mb-10">
            <h2 class="text-sm font-black text-red-700 uppercase tracking-widest mb-4 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-red-600 mr-2"></span>
                5. Critical SLA Overdue Complaints
            </h2>
            <div class="overflow-x-auto border border-red-200 rounded-2xl bg-red-50/20">
                <table class="min-w-full divide-y divide-red-100 text-xs">
                    <thead class="bg-red-50 text-red-900 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Case ID</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-3 py-3 text-center">Status</th>
                            <th class="px-3 py-3 text-center">Filed Date</th>
                            <th class="px-3 py-3 text-center">Deadline</th>
                            <th class="px-4 py-3 text-right">Time Overdue</th>
                            <th class="px-4 py-3 text-left">Assigned Officer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50 bg-white">
                        @foreach($analytics['sla_stats']['overdue_list'] as $od)
                            <tr>
                                <td class="px-4 py-2.5 font-bold text-red-600">{{ $od['case_number'] }}</td>
                                <td class="px-4 py-2.5 font-bold text-slate-900">{{ $od['category'] }}</td>
                                <td class="px-4 py-2.5 text-slate-600">{{ $od['department'] }}</td>
                                <td class="px-3 py-2.5 text-center font-bold text-amber-600">{{ $od['status'] }}</td>
                                <td class="px-3 py-2.5 text-center text-slate-500">{{ $od['created_at'] }}</td>
                                <td class="px-3 py-2.5 text-center text-red-600 font-semibold">{{ $od['deadline'] }}</td>
                                <td class="px-4 py-2.5 text-right font-black text-red-700">{{ $od['days_overdue'] }} Days ({{ $od['hours_overdue'] }}h)</td>
                                <td class="px-4 py-2.5 text-slate-600">{{ $od['assigned_officer'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Municipal Sign-off Footer -->
        <div class="border-t-2 border-slate-200 pt-8 mt-12 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div>
                <p class="font-bold text-slate-900">CivicGuard Municipal Operations Dashboard</p>
                <p>Official automated audit report. Data synchronized with live municipal database.</p>
            </div>
            <div class="text-right">
                <div class="h-10 border-b border-slate-300 w-48 mb-1"></div>
                <p class="text-[10px] uppercase font-bold text-slate-400">Authorized Municipal Officer</p>
            </div>
        </div>
    </div>

    @if($autoPrint)
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 500);
        });
    </script>
    @endif
</body>
</html>
