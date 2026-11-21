<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicGuard - Ward #{{ $ward->ward_number }} Field Inspection Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 font-sans p-6 max-w-5xl mx-auto text-xs leading-relaxed">

    <!-- Action Bar -->
    <div class="no-print mb-6 p-4 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800 text-sm">Ward Field Inspection Sheet</h2>
            <p class="text-xs text-slate-500">Official field work order and inspection verification sheet for municipal officers.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2 bg-emerald-700 text-white rounded-lg font-bold text-xs hover:bg-emerald-800 shadow-sm">
                Print Inspection Sheet
            </button>
            <a href="{{ route('admin.wards.show', $ward->id) }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-slate-700 hover:bg-slate-50">
                Back to Ward GIS
            </a>
        </div>
    </div>

    <!-- Official Header -->
    <div class="border-b-2 border-slate-800 pb-4 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-black uppercase text-slate-900">MUNICIPAL FIELD WORK & INSPECTION REGISTER</h1>
                <h2 class="text-sm font-bold text-[#8B0000] mt-0.5">Ward #{{ $ward->ward_number }}: {{ $ward->name }}</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $ward->description }}</p>
            </div>
            <div class="text-right text-[11px] text-slate-600 space-y-0.5">
                <div><strong>Inspection Date:</strong> {{ now()->format('F d, Y') }}</div>
                <div><strong>Officer:</strong> {{ Auth::user()->name }} ({{ Auth::user()->department ?? 'Municipal Administration' }})</div>
                <div><strong>GPS Centroid:</strong> {{ $ward->center_lat }}, {{ $ward->center_lng }}</div>
            </div>
        </div>
    </div>

    <!-- Ward Summary -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="p-2.5 bg-slate-50 border border-slate-200 rounded">
            <span class="text-[10px] text-slate-500 uppercase block font-bold">Total Ward Cases</span>
            <span class="text-base font-black text-slate-900">{{ $ward->reports->count() }}</span>
        </div>
        <div class="p-2.5 bg-red-50 border border-red-200 rounded">
            <span class="text-[10px] text-red-700 uppercase block font-bold">Pending Field Review</span>
            <span class="text-base font-black text-red-700">{{ $ward->reports->where('status', 'Pending')->count() }}</span>
        </div>
        <div class="p-2.5 bg-blue-50 border border-blue-200 rounded">
            <span class="text-[10px] text-blue-700 uppercase block font-bold">In Progress / Work In Hand</span>
            <span class="text-base font-black text-blue-700">{{ $ward->reports->where('status', 'In Progress')->count() }}</span>
        </div>
        <div class="p-2.5 bg-emerald-50 border border-emerald-200 rounded">
            <span class="text-[10px] text-emerald-700 uppercase block font-bold">Repaired / Completed</span>
            <span class="text-base font-black text-emerald-700">{{ $ward->reports->where('status', 'Repaired')->count() }}</span>
        </div>
    </div>

    <!-- Assigned Officers -->
    <div class="mb-4 p-2 bg-slate-50 border border-slate-200 rounded text-[11px]">
        <strong>Authorized Field Officers:</strong>
        {{ $ward->officers->pluck('name')->implode(', ') ?: 'No officers assigned' }}
    </div>

    <!-- Active Complaints Field Verification Register -->
    <div class="mb-6">
        <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-2">Active Field Complaints Requiring Inspection ({{ $activeReports->count() }} Cases)</h3>
        <table class="w-full text-left border-collapse border border-slate-200 text-[10px]">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase">
                    <th class="p-1.5 border-r border-slate-200 w-16">Case ID</th>
                    <th class="p-1.5 border-r border-slate-200">Category & Description</th>
                    <th class="p-1.5 border-r border-slate-200">Location & Coordinates</th>
                    <th class="p-1.5 border-r border-slate-200 w-20 text-center">Status</th>
                    <th class="p-1.5 border-r border-slate-200 w-24 text-center">SLA Deadline</th>
                    <th class="p-1.5 border-r border-slate-200 w-20 text-center">Verified?</th>
                    <th class="p-1.5 w-32">Officer Field Remarks</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($activeReports as $rep)
                    <tr>
                        <td class="p-1.5 font-mono font-bold border-r border-slate-200">#{{ str_pad($rep->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-1.5 border-r border-slate-200">
                            <strong>{{ $rep->category }}</strong>
                            <p class="text-slate-500 mt-0.5 line-clamp-2">{{ $rep->description }}</p>
                        </td>
                        <td class="p-1.5 border-r border-slate-200">
                            <div>{{ $rep->location }}</div>
                            @if($rep->latitude && $rep->longitude)
                                <div class="font-mono text-slate-400 text-[9px] mt-0.5">{{ $rep->latitude }}, {{ $rep->longitude }}</div>
                            @endif
                        </td>
                        <td class="p-1.5 text-center font-bold border-r border-slate-200 {{ $rep->status === 'Pending' ? 'text-red-700' : 'text-blue-700' }}">
                            {{ $rep->status }}
                        </td>
                        <td class="p-1.5 text-center text-slate-600 border-r border-slate-200">
                            {{ $rep->sla_deadline ? \Carbon\Carbon::parse($rep->sla_deadline)->format('M d, Y') : 'Standard SLA' }}
                        </td>
                        <td class="p-1.5 text-center border-r border-slate-200">
                            <span class="inline-block w-4 h-4 border border-slate-400 rounded"></span>
                        </td>
                        <td class="p-1.5 border-slate-200 text-slate-300">
                            _________________
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-slate-400">
                            No active pending or in-progress complaints in this ward. All issues currently resolved!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Field Inspection Notes & Officer Signatures -->
    <div class="border border-slate-300 rounded p-4 mb-6">
        <h4 class="font-bold text-slate-800 text-xs uppercase mb-2">Field Officer Verification & Action Notes:</h4>
        <div class="space-y-4">
            <div class="border-b border-dashed border-slate-300 pb-4"></div>
            <div class="border-b border-dashed border-slate-300 pb-4"></div>
        </div>
    </div>

    <div class="border-t border-slate-400 pt-6 mt-6 flex justify-between items-end text-xs">
        <div>
            <p class="font-bold text-slate-800">Inspecting Officer Sign & Seal:</p>
            <p class="text-slate-500 text-[10px] mt-1">{{ Auth::user()->name }} ({{ Auth::user()->department ?? 'Field Operations' }})</p>
        </div>
        <div class="text-center">
            <div class="w-48 border-b border-slate-400 mb-1"></div>
            <p class="text-[10px] text-slate-500 uppercase">Supervising Engineer Approval</p>
        </div>
    </div>

    @if($autoPrint)
    <script>
        window.addEventListener('load', () => window.print());
    </script>
    @endif
</body>
</html>
