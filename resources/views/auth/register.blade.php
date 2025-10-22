<x-guest-layout>
    <div class="space-y-3 text-center">
        <span class="mx-auto inline-flex items-center justify-center rounded-full bg-secondary-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-secondary-500">
            {{ __('Join the community') }}
        </span>
        <h1 class="text-3xl font-semibold text-slate-900">{{ __('Create your Mosaic account') }}</h1>
        <p class="text-sm text-slate-500">
            {{ __('Share moments, discover new creators, and build meaningful connections from day one.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
        @csrf

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    id="name"
                    class="mt-2 w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-left" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input
                    id="username"
                    class="mt-2 w-full"
                    type="text"
                    name="username"
                    :value="old('username')"
                    required
                    autocomplete="username"
                />
                <p class="mt-2 text-xs text-slate-400">{{ __('Use letters, numbers, dashes, or underscores only') }}</p>
                <x-input-error :messages="$errors->get('username')" class="mt-2 text-left" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-2 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="email"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-left" />
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    id="password"
                    class="mt-2 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-left" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                <x-text-input
                    id="password_confirmation"
                    class="mt-2 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-left" />
            </div>
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-400 sm:max-w-xs">
                {{ __('By creating an account, you agree to welcome authentic interactions and uphold our community guidelines.') }}
            </p>

            <x-primary-button class="justify-center px-6 py-3 text-base sm:ms-3">
                {{ __('Create account') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-slate-500">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="font-semibold text-primary-500 transition hover:text-primary-400">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
</x-guest-layout>
