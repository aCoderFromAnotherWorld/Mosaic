<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('feed') }}" class="flex items-center">
                        <x-mosaic-logo class="block h-8 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                        {{ __('Feed') }}
                    </x-nav-link>
                    <x-nav-link :href="route('search')" :active="request()->routeIs('search')">
                        {{ __('Search') }}
                    </x-nav-link>
                    <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                        {{ __('Create Post') }}
                    </x-nav-link>
                    <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        {{ __('Messages') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Notifications and Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"></path>
                        </svg>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ Auth::user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                         style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                            </div>
                            @php
                                $recentNotifications = Auth::user()->notifications()->with('sender')->latest()->take(5)->get();
                            @endphp
                            @if($recentNotifications->count() > 0)
                                @foreach($recentNotifications as $notification)
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($notification->sender)
                                                    <img class="h-8 w-8 rounded-full object-cover border border-gray-200" src="{{ $notification->sender->profile_picture ? asset('storage/' . $notification->sender->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($notification->sender->name) }}" alt="{{ $notification->sender->name }}">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 truncate">
                                                    {!! Str::limit($notification->message, 60) !!}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if(!$notification->is_read)
                                                <div class="flex-shrink-0">
                                                    <div class="h-2 w-2 bg-indigo-500 rounded-full"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                        View all notifications
                                    </a>
                                </div>
                            @else
                                <div class="px-4 py-8 text-center text-sm text-gray-500">
                                    No new notifications
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="ms-3 relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50">
                        <a href="{{ route('profile.show', Auth::user()->username) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Profile') }}
                        </a>
                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Notifications') }}
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Settings') }}
                        </a>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                {{ __('Feed') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search')" :active="request()->routeIs('search')">
                {{ __('Search') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                {{ __('Create Post') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                {{ __('Messages') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.show', Auth::user()->username)">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')">
                    {{ __('Notifications') }}
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>