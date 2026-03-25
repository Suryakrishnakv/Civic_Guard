@forelse ($reports as $report)
    <tr class="hover:bg-slate-50/80 transition-all group">
        <td class="px-8 py-6">
            <div class="text-sm font-semibold text-slate-900">{{ $report->category }}</div>
            <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mt-1">{{ $report->department }}</div>
            <div class="text-[11px] text-slate-500 mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ Str::limit($report->location, 30) }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
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
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @if($report->resolution_photo_path)
                <div class="relative inline-block group/zoom">
                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-400 rounded-lg blur opacity-0 group-hover/zoom:opacity-30 transition duration-500"></div>
                    <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                        <img src="{{ asset('storage/' . $report->resolution_photo_path) }}" 
                             alt="Proof" 
                             class="w-full h-full object-cover transform transition duration-500 group-hover/zoom:scale-150 cursor-pointer"
                             onclick="window.open(this.src, '_blank')">
                    </div>
                </div>
            @else
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
             <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black border uppercase tracking-wider
                {{ $report->priority === 'Low' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}
                {{ $report->priority === 'Medium' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                {{ $report->priority === 'High' ? 'bg-orange-50 text-orange-600 border-orange-100' : '' }}
                {{ $report->priority === 'Critical' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                {{ $report->priority ?? 'Normal' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="relative min-w-[120px]">
                    <select name="status" onchange="this.form.submit()" class="block w-full text-xs font-bold rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white text-slate-700 py-1.5 pl-3 pr-8 cursor-pointer hover:bg-slate-50 transition-all duration-200">
                        <option value="Pending" {{ $report->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Repaired" {{ $report->status == 'Repaired' ? 'selected' : '' }}>Repaired</option>
                        <option value="Rejected" {{ $report->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </form>
        </td>
         <td class="pr-8 pl-6 py-6 whitespace-nowrap text-right">
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
@empty
    <tr>
        <td colspan="6" class="px-8 py-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900">All Clear</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">No reports match the current filter selection.</p>
        </td>
    </tr>
@endforelse
