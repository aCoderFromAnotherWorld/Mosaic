<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <!-- Cover Photo -->
                <div class="h-48 sm:h-64 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                    @if($user->cover_picture)
                        <img src="{{ asset('storage/' . $user->cover_picture) }}" 
                             alt="Cover" 
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="relative px-6 pb-6">
                    <div class="sm:flex sm:items-end sm:space-x-5">
                        <!-- Profile Picture -->
                        <div class="flex -mt-16 sm:-mt-20">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200' }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white object-cover">
                        </div>

                        <!-- User Info -->
                        <div class="mt-6 sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                            <div class="sm:hidden md:block mt-6 min-w-0 flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                                <p class="text-gray-600">&#64;{{ $user->username }}</p>
                            </div>

                            <div class="mt-6 flex flex-col justify-stretch space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="inline-flex justify-center px-6 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                        Edit Profile
                                    </a>
                                @else
                                    @if($isFollowing)
                                        <form method="POST" action="{{ route('users.unfollow', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex justify-center px-6 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                                Following
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('users.follow', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('messages.store', $user) }}" class="inline-flex justify-center px-6 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                        Message
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hidden on small screens, visible on md+ -->
                    <div class="hidden sm:block md:hidden mt-6 min-w-0 flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                        <p class="text-gray-600">&#64;{{ $user->username }}</p>
                    </div>
                </div>

                <!-- Stats & Bio -->
                <div class="border-t border-gray-200 px-6 py-5">
                    <div class="flex justify-around mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $posts->total() }}</div>
                            <div class="text-sm text-gray-600">Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $followersCount }}</div>
                            <div class="text-sm text-gray-600">Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $followingCount }}</div>
                            <div class="text-sm text-gray-600">Following</div>
                        </div>
                    </div>

                    @if($user->bio)
                        <div class="mt-4">
                            <p class="text-gray-700">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Posts Grid -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Posts</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($posts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="group relative aspect-square overflow-hidden rounded-lg">
                            @if($post->media->isNotEmpty())
                                @if($post->media->first()->media_type === 'image')
                                    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" 
                                         alt="Post" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <video src="{{ asset('storage/' . $post->media->first()->file_path) }}" 
                                           class="w-full h-full object-cover"></video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-20">
                                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center p-4">
                                    <p class="text-white text-center line-clamp-6">{{ $post->content }}</p>
                                </div>
                            @endif

                            <!-- Overlay with stats -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center space-x-6 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-semibold">{{ $post->reactions->count() }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-semibold">{{ $post->comments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600">No posts yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>