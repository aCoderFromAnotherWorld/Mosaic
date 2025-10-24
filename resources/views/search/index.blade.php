<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-black-800 leading-tight">
            {{ __('Search Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('search') }}" class="mb-6">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="text"
                                       name="q"
                                       value="{{ $query }}"
                                       placeholder="Search by name or username..."
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-900 dark:text-gray-100"
                                       autocomplete="off">
                            </div>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Search Results -->
                    @if($query)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-4">
                                Search Results for "{{ $query }}"
                                @if($users->count() > 0)
                                    ({{ $users->count() }} results)
                                @endif
                            </h3>

                            @if($users->count() > 0)
                                <div class="space-y-4">
                                    @foreach($users as $user)
                                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <div class="flex items-center space-x-4">
                                                <!-- Profile Picture -->
                                                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                    @if($user->profile_picture)
                                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                             alt="{{ $user->name }}"
                                                             class="w-12 h-12 rounded-full object-cover">
                                                    @else
                                                        <span class="text-gray-600 dark:text-gray-400 font-semibold text-lg">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- User Info -->
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        <a href="{{ route('profile.show', $user->username) }}"
                                                           class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $user->name }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                                        {{ '@' . $user->username }}
                                                    </p>
                                                    @if($user->bio)
                                                        <p class="text-gray-700 dark:text-gray-300 text-sm mt-1">
                                                            {{ Str::limit($user->bio, 100) }}
                                                        </p>
                                                    @endif
                                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        <span>{{ $user->followers_count }} followers</span>
                                                        <span>{{ $user->following_count }} following</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex items-center space-x-2">
                                                @if($user->is_friend)
                                                    <!-- Remove Friend -->
                                                    <form method="POST" action="{{ route('users.remove-friend', $user) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                            Remove Friend
                                                        </button>
                                                    </form>
                                                @elseif($user->has_received_request)
                                                    <!-- Accept/Decline Friend Request -->
                                                    <div class="flex space-x-2">
                                                        <form method="POST" action="{{ route('users.accept-friend', $user) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                                                Accept
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('users.decline-friend', $user) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                                Decline
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($user->has_pending_request)
                                                    <!-- Cancel Friend Request -->
                                                    <form method="POST" action="{{ route('users.cancel-friend-request', $user) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                            Cancel Request
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Send Friend Request -->
                                                    <form method="POST" action="{{ route('users.friend-request', $user) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                            Add Friend
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$user->is_following && !$user->is_friend)
                                                    <!-- Follow Button -->
                                                    <form method="POST" action="{{ route('users.follow', $user) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                            Follow
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <p class="text-lg">No users found</p>
                                        <p class="text-sm">Try searching with a different name or username</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="text-xl font-semibold mb-2">Search for Users</h3>
                                <p>Enter a name or username to find and connect with other users</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
