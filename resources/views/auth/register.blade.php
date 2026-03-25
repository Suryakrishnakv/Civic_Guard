<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-slate-900 mb-2 font-eagle-lake">Create Account</h2>
        <p class="text-sm text-slate-500 font-bold">
            {{ __('Join us today! Enter your details below.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-bold text-sm text-slate-700 mb-1">{{ __('Name') }}</label>
            <input id="name" class="block w-full rounded-xl border-slate-200 bg-indigo-50 px-4 py-3 text-slate-900 shadow-sm focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm font-bold placeholder:text-slate-400" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-sm text-slate-700 mb-1">{{ __('Email') }}</label>
            <input id="email" class="block w-full rounded-xl border-slate-200 bg-indigo-50 px-4 py-3 text-slate-900 shadow-sm focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm font-bold placeholder:text-slate-400" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-bold text-sm text-slate-700 mb-1">{{ __('Password') }}</label>
            <input id="password" class="block w-full rounded-xl border-slate-200 bg-indigo-50 px-4 py-3 text-slate-900 shadow-sm focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm font-bold placeholder:text-slate-400" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-bold text-sm text-slate-700 mb-1">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="block w-full rounded-xl border-slate-200 bg-indigo-50 px-4 py-3 text-slate-900 shadow-sm focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] text-sm font-bold placeholder:text-slate-400" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <button class="flex w-full justify-center rounded-xl bg-[#8B0000] px-3 py-3 text-sm font-bold leading-6 text-white shadow-lg shadow-red-900/10 hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-all duration-200 uppercase tracking-widest">
                {{ __('Register') }}
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500 font-medium">
                Already registered?
                <a class="font-bold text-[#8B0000] hover:text-red-700 transition-colors" href="{{ route('login') }}">
                    {{ __('Log in') }}
                </a>
            </p>
        </div>
    </form>

    <x-slot name="footer">
        <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-2 bg-white border border-slate-200 rounded-full text-sm font-bold text-slate-500 hover:text-[#8B0000] hover:border-[#8B0000] transition-all duration-300 shadow-lg group">
            <span class="mr-2 transition-transform group-hover:-translate-x-1">&larr;</span>
            Back to Home
        </a>
    </x-slot>
</x-guest-layout>
