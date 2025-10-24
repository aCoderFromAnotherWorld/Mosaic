<x-app-layout>
    <div class="relative min-h-screen overflow-hidden bg-white text-gray-900">
        <!-- Enhanced Background Effects -->
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute left-10 top-16 h-72 w-72 rounded-full bg-gradient-to-r from-blue-500/10 to-purple-500/10 blur-3xl animate-pulse"></div>
            <div class="absolute right-[-10rem] top-52 h-[28rem] w-[28rem] rounded-full bg-gradient-to-r from-pink-500/10 to-orange-500/10 blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(148,163,184,0.05),_transparent_60%)]"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 pb-20 pt-20 sm:px-6 lg:px-8">
            <!-- Enhanced Header -->
            <header class="mb-12 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-700 shadow-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                        </svg>
                        {{ __('Connections') }}
                    </div>
                    <h1 class="text-4xl font-bold leading-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        {{ __('Your Network') }}
                    </h1>
                    <p class="max-w-2xl text-lg text-gray-600 leading-relaxed">
                        {{ __('Build meaningful connections and manage your social circle with ease.') }}
                    </p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-6 py-3 text-sm font-semibold uppercase tracking-wider text-gray-700 shadow-lg">
                    <div class="flex h-3 w-3 rounded-full bg-green-500 animate-pulse"></div>
                    {{ trans_choice(':count pending request|:count pending requests', $pendingRequests->count(), ['count' => $pendingRequests->count()]) }}
                </div>
            </header>

            <div class="space-y-10">
                <!-- Friend Requests Section -->
                <section class="rounded-3xl border border-gray-200 bg-white backdrop-blur-xl p-8 shadow-2xl hover:shadow-blue-500/10 transition-shadow duration-300">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ __('Friend Requests') }}</p>
                            <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ __('Pending Invitations') }}</h2>
                        </div>
                        <div class="rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-4 py-2 text-sm font-semibold text-gray-700">
                            {{ $pendingRequests->count() }}
                        </div>
                    </div>

                    @if($pendingRequests->count() > 0)
                        <div class="grid gap-6 lg:grid-cols-1">
                            @foreach($pendingRequests as $requestUser)
                                <article class="group flex flex-col gap-6 rounded-2xl border border-gray-200 bg-white backdrop-blur-sm p-6 transition-all duration-300 hover:border-blue-400 hover:bg-gray-50 hover:shadow-lg hover:shadow-blue-500/10 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-start gap-5">
                                        <div class="relative">
                                            <div class="h-16 w-16 overflow-hidden rounded-full border-2 border-gray-300 bg-gradient-to-br from-gray-100 to-gray-50 shadow-lg">
                                                @if($requestUser->profile_picture)
                                                    <img src="{{ asset('storage/' . $requestUser->profile_picture) }}" alt="{{ $requestUser->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-lg font-bold text-gray-600 bg-gradient-to-br from-blue-500/10 to-purple-500/10">
                                                        {{ strtoupper(substr($requestUser->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('profile.show', $requestUser->username) }}" class="text-xl font-bold text-gray-900 transition-colors hover:text-blue-600 truncate">
                                                {{ $requestUser->name }}
                                            </a>
                                            <p class="text-sm text-gray-500 mt-1">{{ '@' . $requestUser->username }}</p>
                                            @if($requestUser->bio)
                                                <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit($requestUser->bio, 120) }}</p>
                                            @endif
                                            <div class="mt-4 flex flex-wrap gap-3">
                                                <span class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-3 py-1 text-xs font-medium text-gray-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ trans_choice(':count follower|:count followers', $requestUser->followers_count, ['count' => $requestUser->followers_count]) }}
                                                </span>
                                                <span class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-3 py-1 text-xs font-medium text-gray-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                    </svg>
                                                    {{ trans_choice(':count following', $requestUser->following_count, ['count' => $requestUser->following_count]) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                        <form method="POST" action="{{ route('users.accept-friend', $requestUser) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:from-green-600 hover:to-emerald-600 hover:shadow-lg hover:shadow-green-500/25 transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('Accept') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.decline-friend', $requestUser) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-gray-100 backdrop-blur-sm px-6 py-3 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:shadow-lg hover:shadow-gray-500/10 transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('Decline') }}
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 backdrop-blur-sm p-12 text-center">
                            <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('All Caught Up!') }}</h3>
                            <p class="text-gray-500">{{ __('No pending friend requests at the moment. New requests will appear here.') }}</p>
                        </div>
                    @endif
                </section>

                <!-- Friends Section -->
                <section class="rounded-3xl border border-gray-200 bg-white backdrop-blur-xl p-8 shadow-2xl hover:shadow-purple-500/10 transition-shadow duration-300">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ __('Your Friends') }}</p>
                            <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ __('Connected People') }}</h2>
                        </div>
                        <div class="rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-4 py-2 text-sm font-semibold text-gray-700">
                            {{ $friends->count() }}
                        </div>
                    </div>

                    @if($friends->count() > 0)
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($friends as $friend)
                                <article class="group flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white backdrop-blur-sm p-6 transition-all duration-300 hover:border-purple-400 hover:bg-gray-50 hover:shadow-lg hover:shadow-purple-500/10 transform hover:scale-105">
                                    <div class="flex items-start gap-4">
                                        <div class="relative">
                                            <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-gray-300 bg-gradient-to-br from-gray-100 to-gray-50 shadow-lg">
                                                @if($friend->profile_picture)
                                                    <img src="{{ asset('storage/' . $friend->profile_picture) }}" alt="{{ $friend->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-base font-bold text-gray-600 bg-gradient-to-br from-purple-500/10 to-pink-500/10">
                                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full bg-green-500 border-2 border-white flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('profile.show', $friend->username) }}" class="text-lg font-bold text-gray-900 transition-colors hover:text-purple-600 truncate">
                                                {{ $friend->name }}
                                            </a>
                                            <p class="text-sm text-gray-500 mt-1">{{ '@' . $friend->username }}</p>
                                            @if($friend->bio)
                                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit($friend->bio, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            <span class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-3 py-1 font-medium text-gray-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ trans_choice(':count follower|:count followers', $friend->followers_count, ['count' => $friend->followers_count]) }}
                                            </span>
                                            <span class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-100 backdrop-blur-sm px-3 py-1 font-medium text-gray-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                                {{ trans_choice(':count following', $friend->following_count, ['count' => $friend->following_count]) }}
                                            </span>
                                        </div>
                                        <form method="POST" action="{{ route('users.remove-friend', $friend) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-300 bg-red-500/10 backdrop-blur-sm px-4 py-2 text-sm font-semibold text-red-600 transition-all duration-300 hover:bg-red-500/20 hover:shadow-lg hover:shadow-red-500/25 transform hover:scale-105">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('Remove Friend') }}
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 backdrop-blur-sm p-12 text-center">
                            <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('No Friends Yet') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('Start building your network by sending friend requests or accepting pending invitations.') }}</p>
                            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:from-blue-600 hover:to-purple-600 hover:shadow-lg hover:shadow-blue-500/25 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('Find People') }}
                            </a>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>