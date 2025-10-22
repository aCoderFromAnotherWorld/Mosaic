<x-guest-layout>
    <div class="space-y-3 text-center">
        <span class="mx-auto inline-flex items-center justify-center rounded-full bg-primary-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-primary-500">
            {{ __('Welcome back') }}
        </span>
        <h1 class="text-3xl font-semibold text-slate-900">{{ __('Sign in to Mosaic') }}</h1>
        <p class="text-sm text-slate-500">
            {{ __('Reconnect with your circles, share a moment, and see what is trending right now.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mt-6 text-center text-primary-500" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-2 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-left" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-primary-500 transition hover:text-primary-400">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input
                id="password"
                class="mt-2 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-left" />
        </div>

        <div class="pt-2">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-primary-500 focus:ring-primary-300"
                    name="remember"
                >
                <span class="text-sm text-slate-600">{{ __('Remember me on this device') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-3 pt-2">
            <x-primary-button class="justify-center px-6 py-3 text-base">
                {{ __('Log in') }}
            </x-primary-button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-slate-500">
                    {{ __('New to Mosaic?') }}
                    <a href="{{ route('register') }}" class="font-semibold text-primary-500 transition hover:text-primary-400">
                        {{ __('Create an account') }}
                    </a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
