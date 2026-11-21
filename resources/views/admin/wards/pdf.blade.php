<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicGuard - Municipal Wards Geographic Audit Report</title>
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

    <!-- Action Bar (Hidden during printing) -->
    <div class="no-print mb-6 p-4 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800 text-sm">Print / Export Ward Audit Document</h2>
            <p class="text-xs text-slate-500">Prepared for municipal administrative review.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2 bg-[#8B0000] text-white rounded-lg font-bold text-xs hover:bg-red-800 shadow-sm">
                Print / Save as PDF
            </button>
            <a href="{{ route('admin.wards.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-slate-700 hover:bg-slate-50">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Official Header -->
    <div class="border-b-2 border-slate-800 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black uppercase tracking-wider text-slate-900">CIVICGUARD MUNICIPAL CORPORATION</h1>
                <h2 class="text-sm font-bold text-slate-600 mt-0.5">Municipal Ward Management & Geographic Resolution Audit</h2>
                <p class="text-[11px] text-slate-500 mt-1">Autonomous GIS Boundary Analysis & Civic Issue Resolution Register</p>
            </div>
            <div class="text-right text-[11px] text-slate-600 space-y-0.5">
                <div><strong>Report Date:</strong> {{ now()->format('F d, Y h:i A') }}</div>
                <div><strong>Auditor:</strong> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                <div><strong>Classification:</strong> Official Municipal Record</div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Indicators -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="border border-slate-200 p-3 rounded-lg bg-slate-50">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Total Municipal Wards</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $wards->count() }}</span>
            <span class="text-[10px] text-slate-400">100% Boundary Polygons</span>
        </div>
        <div class="border border-slate-200 p-3 rounded-lg bg-slate-50">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Total Ward Complaints</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $totalReports }}</span>
            <span class="text-[10px] text-slate-400">Recorded In All Wards</span>
        </div>
        <div class="border border-slate-200 p-3 rounded-lg bg-slate-50">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Repaired / Resolved</span>
            <span class="text-xl font-black text-emerald-700 mt-1 block">{{ $totalRepaired }}</span>
            <span class="text-[10px] text-slate-400">Completed Work Orders</span>
        </div>
        <div class="border border-slate-200 p-3 rounded-lg bg-slate-50">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Resolution Efficiency</span>
            <span class="text-xl font-black text-indigo-700 mt-1 block">{{ $overallEfficiency }}%</span>
            <span class="text-[10px] text-slate-400">(Repaired / Total) &times; 100</span>
        </div>
    </div>

    <!-- Ward Matrix Table -->
    <div class="mb-6">
        <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-2">Ward Performance & Officer Allocation Register</h3>
        <table class="w-full text-left border-collapse border border-slate-200 text-[11px]">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase">
                    <th class="p-2 border-r border-slate-200">Ward #</th>
                    <th class="p-2 border-r border-slate-200">Ward Name</th>
                    <th class="p-2 border-r border-slate-200">Assigned Officers</th>
                    <th class="p-2 border-r border-slate-200 text-center">Total</th>
                    <th class="p-2 border-r border-slate-200 text-center">Pending</th>
                    <th class="p-2 border-r border-slate-200 text-center">In Prog</th>
                    <th class="p-2 border-r border-slate-200 text-center">Repaired</th>
                    <th class="p-2 text-center">Efficiency</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($wards as $w)
                    @php
                        $tot = $w->reports->count();
                        $p = $w->reports->where('status', 'Pending')->count();
                        $ip = $w->reports->where('status', 'In Progress')->count();
                        $r = $w->reports->where('status', 'Repaired')->count();
                        $eff = $tot > 0 ? round(($r / $tot) * 100, 1) : 0;
                        $officerNames = $w->officers->pluck('name')->implode(', ') ?: 'None';
                    @endphp
                    <tr>
                        <td class="p-2 font-mono font-bold text-slate-900 border-r border-slate-200">#{{ $w->ward_number }}</td>
                        <td class="p-2 font-bold text-slate-800 border-r border-slate-200">{{ $w->name }}</td>
                        <td class="p-2 text-slate-600 border-r border-slate-200 text-[10px]">{{ $officerNames }}</td>
                        <td class="p-2 text-center font-bold border-r border-slate-200">{{ $tot }}</td>
                        <td class="p-2 text-center text-red-600 font-bold border-r border-slate-200">{{ $p }}</td>
                        <td class="p-2 text-center text-blue-600 font-bold border-r border-slate-200">{{ $ip }}</td>
                        <td class="p-2 text-center text-emerald-600 font-bold border-r border-slate-200">{{ $r }}</td>
                        <td class="p-2 text-center font-bold {{ $eff >= 70 ? 'text-emerald-700' : 'text-slate-700' }}">{{ $eff }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ward Boundary Coordinates Section -->
    <div class="mb-6">
        <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-2">Geographic Centroids & Verification Metadata</h3>
        <table class="w-full text-left border-collapse border border-slate-200 text-[10px]">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase">
                    <th class="p-1.5 border-r border-slate-200">Ward #</th>
                    <th class="p-1.5 border-r border-slate-200">Sector Name</th>
                    <th class="p-1.5 border-r border-slate-200">Centroid Coordinates</th>
                    <th class="p-1.5 border-r border-slate-200">Boundary Polygon Vertices</th>
                    <th class="p-1.5">Algorithmic Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($wards as $w)
                    <tr>
                        <td class="p-1.5 font-mono font-bold border-r border-slate-200">#{{ $w->ward_number }}</td>
                        <td class="p-1.5 font-bold border-r border-slate-200">{{ $w->name }}</td>
                        <td class="p-1.5 font-mono border-r border-slate-200">{{ $w->center_lat }}, {{ $w->center_lng }}</td>
                        <td class="p-1.5 border-r border-slate-200">{{ count($w->boundaries ?? []) }} vertices defined</td>
                        <td class="p-1.5 text-emerald-700 font-bold">Active Point-in-Polygon Shield</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Certification & Sign-off Block -->
    <div class="border-t-2 border-slate-800 pt-6 mt-8 flex justify-between items-end text-xs">
        <div>
            <p class="font-bold text-slate-800">MUNICIPAL COMMAND & CONTROL CENTER</p>
            <p class="text-slate-500 text-[10px]">Document generated from verified CivicGuard PostgreSQL database.</p>
        </div>
        <div class="text-center">
            <div class="w-48 border-b border-slate-400 mb-1"></div>
            <p class="text-[10px] text-slate-500 uppercase">Chief Municipal Administrative Officer</p>
        </div>
    </div>

    @if($autoPrint)
    <script>
        window.addEventListener('load', () => window.print());
    </script>
    @endif
</body>
</html>
