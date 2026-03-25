<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -my-4 px-4 sm:px-6 lg:px-8 py-10 group">
            <!-- Premium Background (Strictly contained overflow for mesh pattern) -->
            <div class="absolute inset-0 overflow-hidden rounded-b-[3rem]">
                <div class="absolute inset-0 bg-white"></div>
                <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-emerald-50/40"></div>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm active:scale-95 group/back">
                            <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div>
                            <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">Performance Evaluation</h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">Departmental Efficiency & Workload Analysis</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Date Selection Console -->
                    <div class="flex bg-white border border-slate-200 p-1 rounded-2xl shadow-sm">
                        <!-- Legacy Quick Filters -->
                        <a href="{{ route('admin.evaluations.index', ['filter' => 'this_month']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $stats['filter_type'] === 'this_month' ? 'bg-[#8B0000] text-white shadow-lg shadow-red-900/20' : 'text-slate-500 hover:text-red-700 hover:bg-red-50' }}">
                            This Month
                        </a>
                        
                        <!-- Month Dropdown -->
                        <div class="relative group/month">
                            <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1 focus:outline-none focus:ring-0 {{ $stats['current_month'] && $stats['filter_type'] === 'custom' ? 'bg-[#8B0000] text-white shadow-lg shadow-red-900/20' : 'text-slate-500 hover:text-red-700 hover:bg-red-50' }}">
                                {{ $stats['current_month'] ? $stats['months'][$stats['current_month']] : 'Select Month' }}
                                <svg class="w-3 h-3 transition-transform group-hover/month:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="absolute top-[calc(100%+8px)] left-0 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl opacity-0 invisible group-hover/month:opacity-100 group-hover/month:visible transition-all z-50 py-2 max-h-64 overflow-y-auto custom-scrollbar ring-1 ring-slate-900/5">
                                <!-- Pointer/Arrow -->
                                <div class="absolute -top-1 left-6 w-2 h-2 bg-white border-t border-l border-slate-100 rotate-45"></div>
                                
                                <a href="{{ route('admin.evaluations.index', ['year' => $stats['current_year']]) }}" class="block px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    All Months
                                </a>
                                <div class="my-1 border-t border-slate-50"></div>
                                @foreach($stats['months'] as $num => $name)
                                    <a href="{{ route('admin.evaluations.index', ['month' => $num, 'year' => $stats['current_year']]) }}" class="block px-4 py-2.5 text-xs font-bold {{ $stats['current_month'] == $num ? 'text-red-700 bg-red-50/50' : 'text-slate-600 hover:bg-red-50 hover:text-red-700' }} transition-colors">
                                        {{ $name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Year Dropdown -->
                        <div class="relative group/year border-l border-slate-100 ml-1 pl-1">
                            <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1 focus:outline-none focus:ring-0 {{ $stats['current_year'] && $stats['filter_type'] === 'custom' ? 'bg-[#8B0000] text-white shadow-lg shadow-red-900/20' : 'text-slate-500 hover:text-red-700 hover:bg-red-50' }}">
                                {{ $stats['current_year'] ?? 'Select Year' }}
                                <svg class="w-3 h-3 transition-transform group-hover/year:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute top-[calc(100%+8px)] left-0 w-36 bg-white border border-slate-100 rounded-2xl shadow-2xl opacity-0 invisible group-hover/year:opacity-100 group-hover/year:visible transition-all z-50 py-2 ring-1 ring-slate-900/5">
                                <!-- Pointer/Arrow -->
                                <div class="absolute -top-1 left-6 w-2 h-2 bg-white border-t border-l border-slate-100 rotate-45"></div>

                                <a href="{{ route('admin.evaluations.index', ['month' => $stats['current_month']]) }}" class="block px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    All Years
                                </a>
                                <div class="my-1 border-t border-slate-50"></div>
                                @forelse($stats['available_years'] as $year)
                                    <a href="{{ route('admin.evaluations.index', ['year' => $year, 'month' => $stats['current_month']]) }}" class="block px-4 py-2.5 text-xs font-bold {{ $stats['current_year'] == $year ? 'text-red-700 bg-red-50/50' : 'text-slate-600 hover:bg-red-50 hover:text-red-700' }} transition-colors">
                                        {{ $year }}
                                    </a>
                                @empty
                                    <span class="block px-4 py-2 text-xs font-bold text-slate-400">No Data</span>
                                @endforelse
                            </div>
                        </div>

                        <a href="{{ route('admin.evaluations.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $stats['filter_type'] === 'all' ? 'bg-[#8B0000] text-white shadow-lg shadow-red-900/20' : 'text-slate-500 hover:text-red-700 hover:bg-red-50' }}">
                            All Time
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Impact</span>
                        <span class="text-3xl font-bold text-slate-900 font-eagle-lake">{{ $stats['total_received'] }}</span>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Service Requests Received</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Resolution Count</span>
                        <span class="text-3xl font-bold text-emerald-600 font-eagle-lake">{{ $stats['total_resolved'] }}</span>
                        <p class="text-[10px] text-emerald-600/60 font-medium mt-1">Successfully Completed</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Active Pipeline</span>
                        <span class="text-3xl font-bold text-amber-500 font-eagle-lake">{{ array_sum(array_column($evaluations, 'pending')) }}</span>
                        <p class="text-[10px] text-amber-500/60 font-medium mt-1">Cases Under Review</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Evaluation Table -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-100 shadow-xl shadow-slate-200/40 rounded-[2.5rem] overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-eagle-lake tracking-tight">Departmental Rankings</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Sorted by Efficiency Rate</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Department</th>
                                        <th scope="col" class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Resolved</th>
                                        <th scope="col" class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Pending</th>
                                        <th scope="col" class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Efficiency</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($evaluations as $evaluation)
                                        <tr class="hover:bg-slate-50/80 transition-all group">
                                            <td class="px-8 py-6">
                                                <div class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $evaluation['name'] }}</div>
                                            </td>
                                            <td class="px-6 py-6 text-center">
                                                <span class="text-sm font-semibold text-slate-600">{{ $evaluation['total'] }}</span>
                                            </td>
                                            <td class="px-6 py-6 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                                    {{ $evaluation['resolved'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-6 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                                    {{ $evaluation['pending'] }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-6 text-right">
                                                <div class="flex items-center justify-end gap-3">
                                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full {{ $evaluation['efficiency'] >= 75 ? 'bg-emerald-500' : ($evaluation['efficiency'] >= 40 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $evaluation['efficiency'] }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-black italic {{ $evaluation['efficiency'] >= 75 ? 'text-emerald-600' : ($evaluation['efficiency'] >= 40 ? 'text-amber-600' : 'text-rose-600') }}">
                                                        {{ $evaluation['efficiency'] }}%
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Analytics Charts -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white shadow-xl shadow-slate-200/40 rounded-[2.5rem] border border-slate-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-slate-900 font-eagle-lake tracking-tight">Workload Distribution</h3>
                        </div>
                        <div class="relative h-64">
                            <canvas id="workloadChart"></canvas>
                        </div>
                        <div class="mt-8 space-y-6">
                            @php
                                $topPerformer = $evaluations[0] ?? null;
                                // Find department with max pending reports
                                $worstPending = collect($evaluations)->sortByDesc('pending')->first();
                            @endphp

                            @if($topPerformer)
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Top performing department</span>
                                        <span class="text-xs font-black text-slate-900">{{ $topPerformer['efficiency'] }}%</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-500 truncate mr-4">{{ $topPerformer['name'] }}</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                                        <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.4)]" style="width: {{ $topPerformer['efficiency'] }}%"></div>
                                    </div>
                                </div>
                            @endif

                            @if($worstPending && $worstPending != $topPerformer)
                                <div class="space-y-2 pt-2 border-t border-slate-50">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Worst pending department</span>
                                        <span class="text-xs font-black text-slate-900">{{ $worstPending['pending'] }} Pending</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-500 truncate mr-4">{{ $worstPending['name'] }}</span>
                                    </div>
                                    @php
                                        $pendingPercent = $worstPending['total'] > 0 ? ($worstPending['pending'] / $worstPending['total'] * 100) : 0;
                                    @endphp
                                    <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                                        <div class="h-full bg-rose-500 rounded-full shadow-[0_0_8px_rgba(239,68,68,0.4)]" style="width: {{ $pendingPercent }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('workloadChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($evaluations, 'name')),
                        datasets: [
                            {
                                label: 'Total Received',
                                data: @json(array_column($evaluations, 'total')),
                                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                                borderRadius: 6,
                            },
                            {
                                label: 'Resolved',
                                data: @json(array_column($evaluations, 'resolved')),
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    font: { size: 10, weight: 'bold' },
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 11 },
                                cornerRadius: 12
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold' } }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { 
                                    font: { size: 9, weight: 'bold' },
                                    callback: function(value) {
                                        const label = this.getLabelForValue(value);
                                        return label.length > 20 ? label.substr(0, 20) + '...' : label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-authenticated-layout>
