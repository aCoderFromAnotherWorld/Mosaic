<x-app-layout>
    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-black">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute left-10 top-16 h-72 w-72 rounded-full bg-primary-500/25 blur-3xl"></div>
            <div class="absolute right-[-10rem] top-52 h-[28rem] w-[28rem] rounded-full bg-secondary-500/20 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(148,163,184,0.12),_transparent_55%)]"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-6xl px-4 pb-16 pt-16 sm:px-6 lg:px-8">
            <header class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-white/60">
                        {{ __('Connections') }}
                    </span>
                    <h1 class="mt-4 text-3xl font-semibold leading-tight text-white sm:text-4xl">
                        {{ __('Your network at a glance') }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm text-white/70">
                        {{ __('Manage incoming friend requests and stay in touch with the people you already collaborate with.') }}
                    </p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-5 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-white/60">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-primary-400"></span>
                    {{ trans_choice(':count request pending', $pendingRequests->count(), ['count' => $pendingRequests->count()]) }}
                </div>
            </header>

            <div class="space-y-8">
                <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/50">{{ __('Friend Requests') }}</p>
                            <h2 class="text-2xl font-semibold text-white">{{ __('Pending invitations') }}</h2>
                        </div>
                        <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-white/60">
                            {{ $pendingRequests->count() }}
                        </span>
                    </div>

                    @if($pendingRequests->count() > 0)
                        <div class="grid gap-4">
                            @foreach($pendingRequests as $requestUser)
                                <article class="flex flex-col gap-5 rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-primary-300/40 hover:bg-white/10 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="relative">
                                            <div class="h-12 w-12 overflow-hidden rounded-full border border-white/20 bg-white/10">
                                                @if($requestUser->profile_picture)
                                                    <img src="{{ asset('storage/' . $requestUser->profile_picture) }}" alt="{{ $requestUser->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-white/70">
                                                        {{ strtoupper(substr($requestUser->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $requestUser->username) }}" class="text-lg font-semibold text-white transition hover:text-primary-200">
                                                {{ $requestUser->name }}
                                            </a>
                                            <p class="text-sm text-white/60">{{ '@' . $requestUser->username }}</p>
                                            @if($requestUser->bio)
                                                <p class="mt-2 text-sm text-white/70">{{ \Illuminate\Support\Str::limit($requestUser->bio, 90) }}</p>
                                            @endif
                                            <div class="mt-3 flex flex-wrap gap-3 text-xs text-white/60">
                                                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1">{{ trans_choice(':count follower|:count followers', $requestUser->followers_count, ['count' => $requestUser->followers_count]) }}</span>
                                                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1">{{ trans_choice(':count following', $requestUser->following_count, ['count' => $requestUser->following_count]) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <form method="POST" action="{{ route('users.accept-friend', $requestUser) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-primary-500/20 px-4 py-2 text-sm font-semibold text-primary-100 transition hover:bg-primary-500/30">
                                                {{ __('Accept') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.decline-friend', $requestUser) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white/80 transition hover:bg-white/20">
                                                {{ __('Decline') }}
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-10 text-center text-white/60">
                            <p class="text-sm">{{ __('You are all caught up. New requests will show up here.') }}</p>
                        </div>
                    @endif
                </section>

                <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/50">{{ __('Your Friends') }}</p>
                            <h2 class="text-2xl font-semibold text-white">{{ __('People you are connected with') }}</h2>
                        </div>
                        <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold text-white/60">
                            {{ $friends->count() }}
                        </span>
                    </div>

                    @if($friends->count() > 0)
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($friends as $friend)
                                <article class="flex flex-col justify-between gap-5 rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-secondary-400/40 hover:bg-white/10">
                                    <div class="flex items-start gap-4">
                                        <div class="h-12 w-12 overflow-hidden rounded-full border border-white/20 bg-white/10">
                                            @if($friend->profile_picture)
                                                <img src="{{ asset('storage/' . $friend->profile_picture) }}" alt="{{ $friend->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-white/70">
                                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $friend->username) }}" class="text-lg font-semibold text-white transition hover:text-secondary-200">
                                                {{ $friend->name }}
                                            </a>
                                            <p class="text-sm text-white/60">{{ '@' . $friend->username }}</p>
                                            @if($friend->bio)
                                                <p class="mt-2 text-sm text-white/70">{{ \Illuminate\Support\Str::limit($friend->bio, 90) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-white/60">
                                        <div class="flex gap-3">
                                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1">{{ trans_choice(':count follower|:count followers', $friend->followers_count, ['count' => $friend->followers_count]) }}</span>
                                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1">{{ trans_choice(':count following', $friend->following_count, ['count' => $friend->following_count]) }}</span>
                                        </div>
                                        <form method="POST" action="{{ route('users.remove-friend', $friend) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-400/40 bg-red-500/10 px-4 py-2 text-xs font-semibold text-red-200 transition hover:bg-red-500/20">
                                                {{ __('Remove friend') }}
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-10 text-center text-white/60">
                            <p class="text-sm">{{ __('You haven’t added any friends yet. Start by sending a request from a profile or accept pending invitations above.') }}</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
