<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 transform transition-all duration-300 hover:shadow-xl">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('search') }}" class="mb-8">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="text"
                                       name="q"
                                       value="{{ $query }}"
                                       placeholder="Search by name or username..."
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 shadow-sm transition-all duration-300 focus:shadow-md focus:scale-[1.02]"
                                       autocomplete="off">
                            </div>
                            <button type="submit"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 hover:shadow-lg shadow-md">
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Search Results -->
                    @if($query)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-6 text-gray-800 border-b-2 border-gray-100 pb-3">
                                Search Results for "{{ $query }}"
                                @if($users->count() > 0)
                                    <span class="text-blue-600">({{ $users->count() }} results)</span>
                                @endif
                            </h3>

                            @if($users->count() > 0)
                                <div class="space-y-5">
                                    @foreach($users as $user)
                                        <div class="flex items-center justify-between p-5 border-2 border-gray-100 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-[1.01] shadow-sm hover:shadow-md group">
                                            <div class="flex items-center space-x-5">
                                                <!-- Profile Picture -->
                                                <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow duration-300 border-2 border-white">
                                                    @if($user->profile_picture)
                                                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                             alt="{{ $user->name }}"
                                                             class="w-14 h-14 rounded-full object-cover border-2 border-black shadow-inner">
                                                    @else
                                                        <span class="text-gray-700 font-bold text-xl">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- User Info -->
                                                <div class="border-l-2 border-gray-100 pl-5">
                                                    <h4 class="font-bold text-gray-900 text-lg">
                                                        <a href="{{ route('profile.show', $user->username) }}"
                                                           class="hover:text-blue-600 transition-colors duration-300">
                                                            {{ $user->name }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-gray-600 text-sm font-medium">
                                                        {{ '@' . $user->username }}
                                                    </p>
                                                    @if($user->bio)
                                                        <p class="text-gray-700 text-sm mt-2 leading-relaxed border-t border-gray-100 pt-2">
                                                            {{ Str::limit($user->bio, 100) }}
                                                        </p>
                                                    @endif
                                                    <div class="flex items-center space-x-5 mt-3 text-xs text-gray-600 font-medium">
                                                        <span class="bg-gray-100 px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                                            {{ $user->followers_count }} followers
                                                        </span>
                                                        <span class="bg-gray-100 px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                                                            {{ $user->following_count }} following
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex items-center space-x-3">
                                                @if($user->is_friend)
                                                    <!-- Remove Friend
                                                    <form method="POST" action="{{ route('users.remove-friend', $user) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-5 py-2.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-red-700">
                                                            Remove Friend
                                                        </button>
                                                    </form> -->
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
                                                    <div class="flex space-x-3">
                                                        <form method="POST" action="{{ route('users.accept-friend', $user) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-5 py-2.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-green-700">
                                                                Accept
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('users.decline-friend', $user) }}" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-5 py-2.5 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-gray-700">
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
                                                                class="px-5 py-2.5 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-gray-700">
                                                            Cancel Request
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Send Friend Request -->
                                                    <form method="POST" action="{{ route('users.friend-request', $user) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-5 py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-blue-700">
                                                            Add Friend
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$user->is_following && !$user->is_friend)
                                                    <!-- Follow Button -->
                                                    <form method="POST" action="{{ route('users.follow', $user) }}" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-5 py-2.5 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-gray-700">
                                                            Follow
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-inner">
                                    <div class="text-gray-600">
                                        <svg class="mx-auto h-16 w-16 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <p class="text-xl font-semibold text-gray-800 mb-2">No users found</p>
                                        <p class="text-gray-600">Try searching with a different name or username</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-inner">
                            <div class="text-gray-600">
                                <svg class="mx-auto h-20 w-20 mb-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="text-2xl font-bold mb-3 text-gray-800">Search for Users</h3>
                                <p class="text-gray-600 text-lg max-w-md mx-auto">Enter a name or username to find and connect with other users</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>