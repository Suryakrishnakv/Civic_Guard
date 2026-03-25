<!-- Report Detail Modal -->
<div id="reportDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true" onclick="closeReportModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200">
            <!-- Header -->
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-900 font-eagle-lake" id="reportModalCategory">
                    Report Details
                </h3>
                <button onclick="closeReportModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="px-6 py-6 space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                         <span id="reportStatusDot" class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-slate-500"></span>
                        </span>
                        <span id="reportModalStatus" class="font-bold text-sm tracking-wide uppercase">PENDING</span>
                    </div>
                    <span id="reportModalDate" class="text-xs text-slate-400 font-medium">Date</span>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Issue Description</label>
                    <p id="reportModalDescription" class="text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 italic">
                        Description goes here...
                    </p>
                </div>

                <!-- Meta Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Location</label>
                        <p id="reportModalLocation" class="text-sm font-bold text-slate-900 flex items-center gap-1">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span>Loading...</span>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Updated</label>
                        <p id="reportModalUpdated" class="text-sm font-bold text-slate-900">Date</p>
                    </div>
                </div>

                <!-- Officer Remarks -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Officer Remarks</label>
                    <div class="bg-[#8B0000]/5 border border-[#8B0000]/10 p-4 rounded-xl">
                        <p id="reportModalRemarks" class="text-sm text-slate-800 leading-relaxed">
                            No remarks yet.
                        </p>
                    </div>
                </div>

                <!-- Photos -->
                <div id="reportPhotosContainer" class="hidden grid grid-cols-2 gap-4">
                    <div id="reportInitialPhoto" class="hidden space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Initial Photo</label>
                        <img src="" alt="Report Photo" class="w-full h-32 object-cover rounded-xl border border-slate-200 shadow-sm transition-transform hover:scale-105 cursor-zoom-in" onclick="window.open(this.src)">
                    </div>
                    <div id="reportResolutionPhoto" class="hidden space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resolution Proof</label>
                        <img src="" alt="Resolution Photo" class="w-full h-32 object-cover rounded-xl border border-emerald-200 shadow-sm transition-transform hover:scale-105 cursor-zoom-in" onclick="window.open(this.src)">
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3">
                <button onclick="closeReportModal()" class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-xl transition-all">
                    Close Detail
                </button>
                <a id="reportModalLink" href="#" class="px-6 py-2.5 bg-[#8B0000] hover:bg-red-800 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all">
                    Full Page View
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal(data) {
        // Populate Basic Data
        document.getElementById('reportModalCategory').textContent = data.category;
        document.getElementById('reportModalDescription').textContent = data.description;
        document.getElementById('reportModalStatus').textContent = data.status;
        document.getElementById('reportModalLocation').querySelector('span').textContent = data.location;
        document.getElementById('reportModalDate').textContent = 'Submitted on: ' + data.created_at;
        document.getElementById('reportModalUpdated').textContent = data.updated_at;
        document.getElementById('reportModalRemarks').textContent = data.remarks;
        document.getElementById('reportModalLink').href = `/reports/${data.id}`;

        // Handle Status Coloring
        const statusDot = document.getElementById('reportStatusDot');
        const statusText = document.getElementById('reportModalStatus');
        const dotCircles = statusDot.querySelectorAll('span');
        
        // Reset classes
        statusText.className = 'font-bold text-sm tracking-wide uppercase ';
        dotCircles.forEach(c => c.className = 'rounded-full ');
        dotCircles[0].className += 'animate-ping absolute inline-flex h-full w-full opacity-75 ';
        dotCircles[1].className += 'relative inline-flex h-3 w-3 ';

        if (data.status === 'Pending') {
            statusText.classList.add('text-amber-600');
            dotCircles.forEach(c => c.classList.add('bg-amber-400'));
        } else if (data.status === 'In Progress') {
            statusText.classList.add('text-blue-600');
            dotCircles.forEach(c => c.classList.add('bg-blue-400'));
        } else if (data.status === 'Repaired' || data.status === 'Resolved') {
            statusText.classList.add('text-emerald-600');
            dotCircles.forEach(c => c.classList.add('bg-emerald-400'));
        } else {
            statusText.classList.add('text-red-600');
            dotCircles.forEach(c => c.classList.add('bg-red-400'));
        }

        // Handle Photos
        const photosContainer = document.getElementById('reportPhotosContainer');
        const initialPhoto = document.getElementById('reportInitialPhoto');
        const resPhoto = document.getElementById('reportResolutionPhoto');
        
        photosContainer.classList.add('hidden');
        initialPhoto.classList.add('hidden');
        resPhoto.classList.add('hidden');

        if (data.photo || data.res_photo) {
            photosContainer.classList.remove('hidden');
            if (data.photo) {
                initialPhoto.classList.remove('hidden');
                initialPhoto.querySelector('img').src = data.photo;
            }
            if (data.res_photo) {
                resPhoto.classList.remove('hidden');
                resPhoto.querySelector('img').src = data.res_photo;
            }
        }

        // Show Modal
        document.getElementById('reportDetailModal').classList.remove('hidden');
    }

    function closeReportModal() {
        document.getElementById('reportDetailModal').classList.add('hidden');
    }
</script>
