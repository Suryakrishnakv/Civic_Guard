<x-authenticated-layout>
    <x-slot name="header">
        <div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -my-4 px-4 sm:px-6 lg:px-8 py-10 overflow-hidden group">
            <!-- Premium Background with Mesh & Gradients -->
            <div class="absolute inset-0 bg-white"></div>
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/60 via-transparent to-red-50/40"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <div>
                            <h2 class="font-eagle-lake font-bold text-3xl text-slate-900 leading-tight tracking-tight">
                                {{ __('System Messages') }}
                            </h2>
                            <p class="text-sm text-slate-500 font-medium mt-1">Manage all your alerts and broadcasts</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ Auth::user()->role === 'citizen' ? route('dashboard') : route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm active:scale-95 group/back">
                        <svg class="w-4 h-4 mr-2 text-slate-400 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </a>

                    @if(Auth::user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-slate-900 border border-transparent rounded-2xl font-bold text-xs text-white uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm active:scale-95">
                            Mark All as Read
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2.5rem] overflow-hidden border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Message</th>
                                <th scope="col" class="px-6 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-50">
                            @forelse ($notifications as $notification)
                                @php
                                    $annData = [
                                        'id' => $notification->id,
                                        'title' => $notification->data['title'] ?? 'System Notification',
                                        'message' => $notification->data['message'] ?? '',
                                        'date' => $notification->created_at->diffForHumans()
                                    ];
                                @endphp
                                <tr onclick='openAnnouncement(@json($annData))' class="hover:bg-slate-50/80 transition-all cursor-pointer group {{ !$notification->read_at ? 'bg-[#8B0000]/5' : 'bg-white' }}">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-[11px] text-slate-500 font-bold font-mono uppercase tracking-tight">
                                            {{ $notification->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter mt-1">
                                            {{ $notification->created_at->format('H:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="max-w-md">
                                            <h4 class="text-sm font-black text-slate-900 group-hover:text-[#8B0000] transition-colors {{ !$notification->read_at ? 'text-slate-950 underline decoration-[#8B0000]/20 decoration-2 underline-offset-4' : 'text-slate-700' }}">
                                                {{ $notification->data['title'] ?? 'System Notification' }}
                                            </h4>
                                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-[#8B0000] text-white uppercase tracking-wider shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>
                                                New alert
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-slate-50 text-slate-400 uppercase tracking-wider border border-slate-100">
                                                Viewed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                @if(!$notification->read_at)
                                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" onclick="event.stopPropagation()">
                                                        @csrf
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Mark as Read">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onclick="event.stopPropagation()" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Delete Message">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>

                                                <div class="p-2 rounded-full text-slate-300 group-hover:text-[#8B0000] group-hover:bg-red-50 transition-all duration-300 transform group-hover:translate-x-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-slate-100">
                                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">No messages yet</h3>
                                            <p class="text-slate-500 max-w-xs mx-auto text-sm">Your inbox is empty. We'll notify you here when there are system updates or activity on your reports.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($notifications->hasPages())
                    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Announcement Modal -->
    <div id="announcementModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true" onclick="closeAnnouncementModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <div class="bg-white px-6 pt-6 pb-6 shadow-sm border-b border-slate-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-50 sm:mx-0 sm:h-12 sm:w-12 border border-indigo-100">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:ml-5 sm:text-left w-full">
                            <h3 class="text-xl leading-7 font-bold text-slate-900 font-eagle-lake" id="modalTitle">
                                Announcement Title
                            </h3>
                            <div class="mt-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" id="modalDate">Date</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-8 py-8 bg-white">
                    <div class="prose prose-slate prose-sm max-w-none">
                        <p class="text-slate-600 leading-relaxed text-base italic" id="modalMessage">
                            Message content...
                        </p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button type="button" class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-xl transition-all shadow-sm" onclick="closeAnnouncementModal()">
                        Close Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAnnouncement(data) {
            // Populate Modal
            document.getElementById('modalTitle').textContent = data.title;
            document.getElementById('modalMessage').textContent = data.message;
            document.getElementById('modalDate').textContent = data.date;
            
            // Show Modal
            document.getElementById('announcementModal').classList.remove('hidden');
            
            // Mark as Read via AJAX if unread (visual only here, or full backend call)
            if (data.id) {
                fetch(`/notifications/${data.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).catch(error => console.error('Error marking as read:', error));
            }
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').classList.add('hidden');
        }
    </script>
</x-authenticated-layout>
