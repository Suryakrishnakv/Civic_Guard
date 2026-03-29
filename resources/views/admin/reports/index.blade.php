<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -my-4 px-4 sm:px-6 lg:px-8 py-10 overflow-hidden group">
            <!-- Premium Background -->
            <div class="absolute inset-0 bg-white"></div>
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-red-50/40"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm active:scale-95 group/back">
                            <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div>
                            <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                                @if($statusFilter === 'all') All Reports
                                @elseif($statusFilter === 'pending') Pending & In Progress
                                @elseif($statusFilter === 'resolved') Resolved Reports
                                @else Case Management @endif
                            </h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">
                                @if(Auth::user()->isOfficer())
                                    Managing operations for {{ Auth::user()->department }}
                                @else
                                    Global System Records & Oversight
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md active:scale-95">
                        Broadcast Update
                    </a>
                </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Quick Filter -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.reports.manage', ['status' => 'all']) }}" class="bg-white p-6 rounded-[2rem] border {{ $statusFilter === 'all' || !$statusFilter ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-slate-100' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Database Total</span>
                        <span class="text-2xl font-bold text-slate-900 font-eagle-lake">Records Overview</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </a>
                
                <a href="{{ route('admin.reports.manage', ['status' => 'pending']) }}" class="bg-white p-6 rounded-[2rem] border {{ $statusFilter === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-100' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Immediate Action</span>
                        <span class="text-2xl font-bold text-slate-900 font-eagle-lake">Awaiting Review</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>

                <a href="{{ route('admin.reports.manage', ['status' => 'resolved']) }}" class="bg-white p-6 rounded-[2rem] border {{ $statusFilter === 'resolved' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-100' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all group">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Archived History</span>
                        <span class="text-2xl font-bold text-slate-900 font-eagle-lake">Closed Cases</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>
            </div>

            <div class="bg-white border border-slate-100 shadow-xl shadow-slate-200/40 rounded-[2.5rem] overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 via-white to-indigo-50/30 flex justify-between items-center relative">
                    <div class="absolute top-0 right-0 p-4 opacity-[0.03] pointer-events-none">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <div class="flex flex-col md:flex-row items-center gap-4 relative z-10 w-full md:w-auto">
                        <div class="flex items-center gap-4">
                            <div class="p-2.5 rounded-2xl shadow-sm border border-indigo-100 bg-white">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-eagle-lake tracking-tight">Report Registry</h3>
                                <p id="result-count" class="text-[10px] text-indigo-600/60 font-black uppercase tracking-widest mt-0.5">Filtered Results: {{ $reports->count() }} Found</p>
                            </div>
                        </div>
                        
                        <!-- Clean Omnibox Style Search -->
                        <div class="relative flex-1 w-full group ml-4">
                            <div class="relative flex items-center bg-white border border-indigo-100/50 rounded-full px-5 py-2.5 hover:shadow-md focus-within:shadow-md transition-all duration-300">
                                <!-- Search Icon -->
                                <div class="flex items-center pr-3 pt-0.5">
                                    <svg class="w-5 h-5 text-indigo-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <input type="text" id="report-search" 
                                       placeholder="Find reports by category, location, or department..." 
                                       class="block w-full text-[14px] font-medium text-slate-600 bg-transparent border-none focus:ring-0 placeholder:text-slate-400 py-1"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                         <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Issue & Department</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Reporter</th>
                                <th scope="col" class="px-6 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Proof</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider w-40">Status Update</th>
                                <th scope="col" class="pr-8 pl-6 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody id="report-table-body" class="divide-y divide-slate-50">
                            @include('admin.reports._report_list')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('report-search');
            const tableBody = document.getElementById('report-table-body');
            const resultCount = document.getElementById('result-count');
            let debounceTimer;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const query = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', query);

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableBody.innerHTML = html;
                        // Update the count - this is a bit tricky since we don't have the count in the partial
                        // We can either add it to the partial or count the rows
                        const rows = tableBody.querySelectorAll('tr:not(:has(td[colspan]))');
                        const count = rows.length;
                        resultCount.textContent = `Filtered Results: ${count} Found`;
                    })
                    .catch(error => console.error('Error:', error));
                }, 300);
            });
        });
    </script>
</x-authenticated-layout>
