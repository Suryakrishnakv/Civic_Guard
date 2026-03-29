<x-authenticated-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 px-4">
            <div>
                <h2 class="font-eagle-lake font-bold text-4xl text-slate-900 leading-tight">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="text-base text-slate-500 mt-2 font-medium">Configure your personal identity and account security.</p>
            </div>
            <a href="{{ Auth::user()->role === 'citizen' ? route('dashboard') : route('admin.dashboard') }}" class="group/btn relative inline-flex items-center px-8 py-3 bg-white border-2 border-slate-200 rounded-2xl font-bold text-sm text-slate-600 uppercase tracking-widest hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm">
                <span class="relative flex items-center">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover/btn:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 relative min-h-screen bg-[#f8fafc] overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[120px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8B0000] rounded-full blur-[120px] opacity-10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2.5rem] p-12 relative overflow-hidden">
                    <header class="mb-10">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            {{ __('Profile Information') }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                        @csrf
                        @method('patch')

                        <div class="space-y-2">
                            <x-input-label for="name" :value="__('Name')" class="text-slate-900 font-bold text-base" />
                            <x-text-input id="name" name="name" type="text" class="block w-full py-4" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="email" :value="__('Email')" class="text-slate-900 font-bold text-base" />
                            <x-text-input id="email" name="email" type="email" class="block w-full py-4" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex items-center gap-6 pt-4">
                            <button type="submit" class="inline-flex items-center px-8 py-3.5 bg-[#8B0000] border border-transparent rounded-2xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-800 transition-all shadow-xl shadow-red-900/20 active:scale-95">
                                {{ __('Save') }}
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm font-bold text-emerald-600 flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2.5rem] p-12 relative overflow-hidden">
                    <header class="mb-10">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            {{ __('Update Password') }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
                            {{ __("Ensure your account is using a long, random password to stay secure.") }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
                        @csrf
                        @method('put')

                        <div class="space-y-2">
                            <x-input-label for="current_password" :value="__('Current Password')" class="text-slate-900 font-bold text-base" />
                            <x-text-input id="current_password" name="current_password" type="password" class="block w-full py-4" autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="password" :value="__('New Password')" class="text-slate-900 font-bold text-base" />
                            <x-text-input id="password" name="password" type="password" class="block w-full py-4" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-900 font-bold text-base" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full py-4" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-6 pt-4">
                            <button type="submit" class="inline-flex items-center px-8 py-3.5 bg-[#8B0000] border border-transparent rounded-2xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-800 transition-all shadow-xl shadow-red-900/20 active:scale-95">
                                {{ __('Save') }}
                            </button>

                            @if (session('status') === 'password-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm font-bold text-emerald-600 flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            @if(Auth::user()->role === 'citizen')
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2.5rem] p-12 relative overflow-hidden mb-8"
                 x-data="{ 
                    isSubscribed: {{ $user->is_subscribed ? 'true' : 'false' }}, 
                    statusMessage: '',
                    isUpdating: false,
                    async toggleSubscription() {
                        if (this.isUpdating) return;
                        this.isUpdating = true;
                        try {
                            const response = await fetch('{{ route('subscription.toggle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await response.json();
                            this.isSubscribed = data.is_subscribed;
                            this.statusMessage = data.message;
                            setTimeout(() => this.statusMessage = '', 3000);
                        } catch (error) {
                            console.error('Error toggling subscription:', error);
                        } finally {
                            this.isUpdating = false;
                        }
                    }
                 }">
                <header class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            {{ __('Notification Preferences') }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
                            {{ __("Manage how you receive official alerts and updates.") }}
                        </p>
                    </div>
                    <div class="p-4 bg-indigo-50/50 rounded-2xl text-indigo-600 border border-indigo-100 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                </header>

                <div class="flex items-center justify-between p-6 bg-slate-50/80 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-6">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white border-2 border-slate-100 text-indigo-600 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">Email Notifications</h4>
                            <p class="text-sm text-slate-500 mt-1 font-medium">Receive official broadcasts and critical alerts via email.</p>
                        </div>
                    </div>

                    <button @click.prevent="toggleSubscription()" 
                            :disabled="isUpdating"
                            :class="isSubscribed ? 'bg-[#8B0000]' : 'bg-slate-300'"
                            class="relative inline-flex h-8 w-14 flex-shrink-0 cursor-pointer rounded-full border-4 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" 
                            role="switch" 
                            :aria-checked="isSubscribed">
                        <span aria-hidden="true" 
                              :class="isSubscribed ? 'translate-x-6' : 'translate-x-0'"
                              class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>

                <div x-show="statusMessage"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="mt-4 text-sm text-center font-bold"
                     :class="isSubscribed ? 'text-emerald-600' : 'text-slate-500'"
                     x-text="statusMessage">
                </div>
            </div>
            @endif
            
            <!-- Resolved History Section -->
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2.5rem] p-10 relative overflow-hidden">
                    <header class="flex justify-between items-center mb-10">
                        <div class="flex items-center gap-6">
                            <div class="p-4 bg-emerald-50/50 rounded-2xl text-emerald-600 border border-emerald-100 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                                    {{ __('Resolved History') }}
                                </h3>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Archive of completed reports</p>
                            </div>
                        </div>
                        <span class="px-5 py-2 rounded-2xl text-xs font-black bg-emerald-600 text-white shadow-lg shadow-emerald-600/20">
                            Total Resolved reports : {{ $reports->count() }}
                        </span>
                    </header>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Issue</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Reported</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Resolved</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-50">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900">{{ $report->category }}</div>
                                        <div class="text-xs text-slate-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ Str::limit($report->location, 25) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-indigo-600 font-bold font-mono">
                                            {{ $report->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Reported</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $report->department }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-slate-600 font-bold font-mono">
                                            {{ $report->updated_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Completed</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                            {{ $report->status === 'Repaired' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('reports.show', $report) }}" class="text-[#8B0000] hover:text-red-900 text-sm font-bold hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center" colspan="6">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="font-bold text-slate-900">No resolved history found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-authenticated-layout>
