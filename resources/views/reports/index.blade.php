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
                        <a href="{{ route('dashboard') }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all shadow-sm active:scale-95 group/back">
                            <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div>
                            <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                                @if($statusFilter === 'all') Total Reports
                                @elseif($statusFilter === 'pending') Active Reports
                                @elseif($statusFilter === 'resolved') Resolved Reports
                                @else My Reports @endif
                            </h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">
                                @if($statusFilter) Filtering for specific status records @else Managing your community submissions @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center px-6 py-3 bg-[#8B0000] border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition-all shadow-md active:scale-95">
                        File New Report
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Quick Filter -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('reports.citizen', ['status' => 'all']) }}" class="bg-white p-4 rounded-2xl border {{ $statusFilter === 'all' ? 'border-[#8B0000] ring-1 ring-[#8B0000]' : 'border-slate-200' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <span class="text-sm font-bold text-slate-600">Total</span>
                    <span class="text-xl font-bold text-slate-900">{{ Auth::user()->reports()->count() }}</span>
                </a>
                <a href="{{ route('reports.citizen', ['status' => 'pending']) }}" class="bg-white p-4 rounded-2xl border {{ $statusFilter === 'pending' ? 'border-amber-500 ring-1 ring-amber-500' : 'border-slate-200' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <span class="text-sm font-bold text-slate-600">In Progress</span>
                    <span class="text-xl font-bold text-slate-900">{{ Auth::user()->reports()->whereIn('status', ['Pending', 'In Progress'])->count() }}</span>
                </a>
                <a href="{{ route('reports.citizen', ['status' => 'resolved']) }}" class="bg-white p-4 rounded-2xl border {{ $statusFilter === 'resolved' ? 'border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200' }} shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                    <span class="text-sm font-bold text-slate-600">Resolved</span>
                    <span class="text-xl font-bold text-slate-900">{{ Auth::user()->reports()->where('status', 'Repaired')->count() }}</span>
                </a>
            </div>

            <div class="bg-white border border-slate-200 shadow-xl rounded-[2.5rem] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="pl-8 pr-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Issue</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                <th class="pr-8 pl-6 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="pl-8 pr-6 py-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-xl group-hover:scale-105 transition-transform">
                                                @if(str_contains($report->department, 'Health')) 🏥
                                                @elseif(str_contains($report->department, 'Engineering')) 🛣️
                                                @elseif(str_contains($report->department, 'Waste')) 🗑️
                                                @elseif(str_contains($report->department, 'Electrical')) 💡
                                                @elseif(str_contains($report->department, 'Water')) 🚰
                                                @else 📝 @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900">{{ $report->category }}</div>
                                                <div class="text-xs text-slate-500">{{ Str::limit($report->location, 40) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $report->status === 'Pending' ? 'bg-amber-100 text-amber-700' : ($report->status === 'In Progress' ? 'bg-blue-100 text-blue-700' : ($report->status === 'Repaired' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700')) }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-xs text-slate-500 font-medium">
                                        {{ $report->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="pr-8 pl-6 py-5 text-right">
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
                                        <button onclick='openReportModal(@json($repData))' class="text-slate-400 hover:text-[#8B0000] transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <p class="text-slate-400 font-medium italic">No reports found matching this criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Re-use the modal from dashboard for consistency -->
    @include('reports.detail-modal')

</x-authenticated-layout>
