<x-app-layout>
    <div class="bg-slate-50 py-10 sm:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <header class="mb-10">
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700">
                    {{ __('Connections') }}
                </span>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900 sm:text-4xl">
                    {{ __('Friends & Invitations') }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">
                    {{ __('Review incoming requests and manage the people you collaborate with on Mosaic.') }}
                </p>
            </header>

            <div class="space-y-10">
                <section class="rounded-2xl border border-blue-100 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-blue-100/70 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">{{ __('Friend Requests') }}</p>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('Pending invitations') }}</h2>
                        </div>
                        <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                            {{ $pendingRequests->count() }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($pendingRequests->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($pendingRequests as $user)
                                    <article class="flex flex-col gap-4 rounded-xl border border-blue-100/60 bg-blue-50/40 p-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex items-start gap-4">
                                            <div class="h-12 w-12 overflow-hidden rounded-full border border-blue-200 bg-white shadow-sm">
                                                @if($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-blue-500">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('profile.show', $user->username) }}" class="text-base font-semibold text-slate-900 hover:text-blue-600">
                                                    {{ $user->name }}
                                                </a>
                                                <p class="text-sm text-slate-500">{{ '@' . $user->username }}</p>
                                                @if($user->bio)
                                                    <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($user->bio, 90) }}</p>
                                                @endif
                                                <div class="mt-3 flex flex-wrap gap-3 text-xs text-slate-500">
                                                    <span>{{ trans_choice(':count follower|:count followers', $user->followers_count, ['count' => $user->followers_count]) }}</span>
                                                    <span>{{ trans_choice(':count following', $user->following_count, ['count' => $user->following_count]) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <form method="POST" action="{{ route('users.accept-friend', $user) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                    {{ __('Accept') }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('users.decline-friend', $user) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center rounded-md border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2">
                                                    {{ __('Decline') }}
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center rounded-xl border border-dashed border-blue-200 bg-blue-50/40 px-6 py-10 text-center text-sm text-blue-700">
                                <svg class="mb-3 h-10 w-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M18 8a3 3 0 11-6 0 3 3 0 016 0zm-9 8a3 3 0 100-6 3 3 0 000 6zm9 0a3 3 0 11-6 0 3 3 0 016 0zM15 14l1.553 1.553a2.2 2.2 0 01.647 1.557v.64A1.25 1.25 0 0116 19.75h-2.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 14l-1.553 1.553a2.2 2.2 0 00-.647 1.557v.64c0 .69.56 1.25 1.25 1.25H10" />
                                </svg>
                                <p>{{ __('You have no pending friend requests right now.') }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Your Friends') }}</p>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('People you are connected with') }}</h2>
                        </div>
                        <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            {{ $friends->count() }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($friends->isNotEmpty())
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($friends as $friend)
                                    <article class="flex h-full flex-col rounded-lg border border-slate-200 bg-slate-50 p-5 shadow-sm">
                                        <div class="flex items-start gap-4">
                                            <div class="h-12 w-12 overflow-hidden rounded-full border border-slate-200 bg-white">
                                                @if($friend->profile_picture)
                                                    <img src="{{ asset('storage/' . $friend->profile_picture) }}" alt="{{ $friend->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-slate-500">
                                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="space-y-1">
                                                <a href="{{ route('profile.show', $friend->username) }}" class="text-base font-semibold text-slate-900 hover:text-blue-600">
                                                    {{ $friend->name }}
                                                </a>
                                                <p class="text-sm text-slate-500">{{ '@' . $friend->username }}</p>
                                                @if($friend->bio)
                                                    <p class="text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($friend->bio, 90) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                                            <div class="flex gap-3">
                                                <span>{{ trans_choice(':count follower|:count followers', $friend->followers_count, ['count' => $friend->followers_count]) }}</span>
                                                <span>{{ trans_choice(':count following', $friend->following_count, ['count' => $friend->following_count]) }}</span>
                                            </div>
                                            <form method="POST" action="{{ route('users.remove-friend', $friend) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-500 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-200">
                                                    {{ __('Remove Friend') }}
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center text-sm text-slate-600">
                                {{ __('You haven’t added any friends yet. Search for people and send a request to connect.') }}
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
