<nav class="bg-white/95 backdrop-blur-md border-b border-gray-100/80 shadow-sm sticky top-0 z-50">
    @php
        $pendingFriendRequests = Auth::user()->friendRequests()->count();
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('feed') }}" class="group inline-flex items-center gap-3">
                        <!-- <span class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 text-lg font-semibold text-white shadow-lg transition group-hover:scale-105">
                            Mosaic
                        </span> -->
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent transition group-hover:tracking-tight">
                            <!-- {{ config('app.name', 'Mosaic') }} -->
                              Mosaic
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1 ml-8">
                    <x-nav-link :href="route('feed')" :active="request()->routeIs('feed')" class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200 hover:bg-blue-50 hover:text-blue-600">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="font-medium">{{ __('Feed') }}</span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('search')" :active="request()->routeIs('search')" class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200 hover:bg-green-50 hover:text-green-600">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="font-medium">{{ __('Search') }}</span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('friends.index')" :active="request()->routeIs('friends.*')" class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200 hover:bg-cyan-50 hover:text-cyan-600">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8a3 3 0 11-6 0 3 3 0 016 0zm-9 8a3 3 0 100-6 3 3 0 000 6zm9 0a3 3 0 11-6 0 3 3 0 016 0zM15 14l1.553 1.553a2.2 2.2 0 01.647 1.557v.64A1.25 1.25 0 0116 19.75h-2.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-1.553 1.553a2.2 2.2 0 00-.647 1.557v.64c0 .69.56 1.25 1.25 1.25H10" />
                            </svg>
                            <span class="font-medium">{{ __('Friends') }}</span>
                            @if($pendingFriendRequests > 0)
                                <span class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-cyan-500 px-1 text-[0.65rem] font-semibold text-white">
                                    {{ $pendingFriendRequests }}
                                </span>
                            @endif
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')" class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200 hover:bg-purple-50 hover:text-purple-600">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="font-medium">{{ __('Create') }}</span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')" class="group relative">
                        <div class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all duration-200 hover:bg-orange-50 hover:text-orange-600">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="font-medium">{{ __('Messages') }}</span>
                        </div>
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side - Notifications and Profile -->
            <div class="flex items-center space-x-3">
                <!-- Create Post Button (Mobile) -->
                <a href="{{ route('posts.create') }}" class="md:hidden flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>

                <!-- Notifications -->
                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown(this)"
                            aria-haspopup="menu"
                            aria-controls="notifications-dropdown"
                            aria-expanded="false"
                            class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-all duration-200 hover:scale-105 group">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z" />
                        </svg>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-semibold shadow-lg animate-pulse">
                                {{ min(Auth::user()->unreadNotifications()->count(), 9) }}{{ Auth::user()->unreadNotifications()->count() > 9 ? '+' : '' }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown"
                         class="hidden absolute right-0 mt-2 w-96 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-200/50 z-50 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                @if(Auth::user()->unreadNotifications()->count() > 0)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        {{ Auth::user()->unreadNotifications()->count() }} new
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            @php
                                $recentNotifications = Auth::user()->notifications()->with('sender')->latest()->take(6)->get();
                            @endphp
                            @if($recentNotifications->count() > 0)
                                @foreach($recentNotifications as $notification)
                                    <a href="{{ route('notifications.index') }}" 
                                       class="block px-6 py-4 hover:bg-gray-50/80 border-b border-gray-100/50 last:border-b-0 transition-colors duration-150 group"
                                       :class="{ 'bg-blue-50/50': !$notification->is_read }">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0 relative">
                                                @if($notification->sender)
                                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-200" 
                                                         src="{{ $notification->sender->profile_picture ? asset('storage/' . $notification->sender->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($notification->sender->name) . '&background=0D8ABC&color=fff' }}" 
                                                         alt="{{ $notification->sender->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-sm">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                @if(!$notification->is_read)
                                                    <div class="absolute -top-1 -right-1 h-3 w-3 bg-blue-500 rounded-full border-2 border-white"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 leading-relaxed">
                                                    {!! Str::limit($notification->message, 80) !!}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="px-6 py-12 text-center">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">No notifications yet</p>
                                    <p class="text-gray-400 text-xs mt-1">We'll notify you when something happens</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        @if($recentNotifications->count() > 0)
                            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50">
                                <a href="{{ route('notifications.index') }}" 
                                   class="block text-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                    View all notifications
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown(this)"
                            aria-haspopup="menu"
                            aria-controls="profile-dropdown"
                            aria-expanded="false"
                            class="flex items-center space-x-3 p-1 rounded-2xl bg-gray-50 hover:bg-blue-50 transition-all duration-200 hover:scale-105 group border-2 border-transparent hover:border-blue-200">
                        <div class="flex items-center space-x-3">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-110 transition-transform duration-200"
                                 src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff&size=128' }}"
                                 alt="{{ Auth::user()->name }}">
                            <div class="hidden lg:block text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ '@' . (Auth::user()->username ?? Auth::user()->email) }}</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Profile Dropdown Content -->
                    <div id="profile-dropdown"
                         class="hidden absolute right-0 mt-2 w-64 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-200/50 z-50 overflow-hidden">
                        <!-- User Info -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm" 
                                     src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff&size=128' }}" 
                                     alt="{{ Auth::user()->name }}">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ '@' . (Auth::user()->username ?? Auth::user()->email) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('profile.show', Auth::user()->username) }}" 
                               class="flex items-center px-6 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Profile
                            </a>

                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center px-6 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-green-500 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z" />
                                </svg>
                                Notifications
                                @if(Auth::user()->unreadNotifications()->count() > 0)
                                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                        {{ Auth::user()->unreadNotifications()->count() }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-6 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors duration-150 group">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-purple-500 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-6 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 group border-t border-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-red-500 group-hover:text-red-600 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(button) {
            const menu = button.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('#notifications-dropdown, #profile-dropdown').forEach(function(menu) {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</nav>
