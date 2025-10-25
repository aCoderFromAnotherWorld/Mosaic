<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <!-- Cover Photo -->
                <div class="h-48 sm:h-64 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative overflow-hidden">
                    @if($user->cover_picture)
                        <img src="{{ asset('storage/' . $user->cover_picture) }}" 
                             alt="Cover" 
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="relative px-4 sm:px-6 pb-4 sm:pb-6">
                    <div class="sm:flex sm:items-end sm:space-x-5">
                        <!-- Profile Picture -->
                        @php
                            $profileAvatarUrl = $user->profile_picture
                                ? asset('storage/' . $user->profile_picture)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=600';
                        @endphp
                        <div class="flex -mt-16 sm:-mt-20">
                            <div class="rounded-full border-4 border-white shadow-lg overflow-hidden flex-shrink-0 mx-auto sm:mx-0 cursor-pointer transition-transform hover:scale-105 profile-avatar-wrapper"
                                 style="width: 6.5rem; height: 6.5rem;">
                                <img id="profileAvatarImage"
                                     src="{{ $profileAvatarUrl }}"
                                     alt="{{ $user->name }}"
                                     class="w-full h-full object-cover profile-avatar-img">
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="mt-4 sm:mt-6 sm:flex-1 sm:min-w-0 sm:flex sm:items-center sm:justify-end sm:space-x-6 sm:pb-1">
                            <div class="sm:hidden md:block mt-4 sm:mt-6 min-w-0 flex-1">
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                                <p class="text-gray-600">&#64;{{ $user->username }}</p>
                            </div>

                            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-stretch space-y-3 sm:space-y-0 sm:space-x-4">
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Edit Profile
                                    </a>
                                @else
                                    @if($isFriend)
                                        <form method="POST" action="{{ route('users.remove-friend', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-red-600 bg-white hover:bg-red-50 transition-colors">
                                                Remove Friend
                                            </button>
                                        </form>
                                    @elseif($hasPendingFriendRequest)
                                        <form method="POST" action="{{ route('users.cancel-friend-request', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                Cancel Request
                                            </button>
                                        </form>
                                    @elseif($hasIncomingFriendRequest)
                                        <form method="POST" action="{{ route('users.accept-friend', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                Accept Request
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.decline-friend', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                Decline
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('users.friend-request', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                                Add Friend
                                            </button>
                                        </form>
                                    @endif

                                    @if($isFollowing)
                                        <form method="POST" action="{{ route('users.unfollow', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                Following
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('users.follow', $user) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                                Follow
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('messages.create', $user) }}" class="inline-flex justify-center px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Message
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hidden on small screens, visible on md+ -->
                    <div class="hidden sm:block md:hidden mt-4 sm:mt-6 min-w-0 flex-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                        <p class="text-gray-600">&#64;{{ $user->username }}</p>
                    </div>
                    
                    @if($user->bio)
                    <div class="mt-4">
                        <p class="text-gray-700 text-sm sm:text-base">{{ $user->bio }}</p>
                    </div>
                    @endif
                </div>

                <!-- Stats & Bio -->
                <div class="border-t border-gray-200 px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex justify-around mb-4">
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $posts->total() }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $followersCount }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $followingCount }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Following</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $friendsCount }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Friends</div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Connections -->
            @if($isOwnProfile)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Connections</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Followers</h3>
                                <span class="text-sm text-gray-500">{{ $followersCount }}</span>
                            </div>
                            <div class="space-y-3">
                                @forelse($followers as $follower)
                                    @php
                                        $followerAvatar = $follower->profile_picture
                                            ? asset('storage/' . $follower->profile_picture)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($follower->name) . '&size=200';
                                    @endphp
                                    <a href="{{ route('profile.show', $follower->username) }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition">
                                        <img src="{{ $followerAvatar }}" alt="{{ $follower->name }}" class="w-10 h-10 rounded-full object-cover">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $follower->name }}</p>
                                            <p class="text-xs text-gray-500">&#64;{{ $follower->username }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500">You don't have any followers yet.</p>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Following</h3>
                                <span class="text-sm text-gray-500">{{ $followingCount }}</span>
                            </div>
                            <div class="space-y-3">
                                @forelse($following as $followedUser)
                                    @php
                                        $followingAvatar = $followedUser->profile_picture
                                            ? asset('storage/' . $followedUser->profile_picture)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($followedUser->name) . '&size=200';
                                    @endphp
                                    <a href="{{ route('profile.show', $followedUser->username) }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition">
                                        <img src="{{ $followingAvatar }}" alt="{{ $followedUser->name }}" class="w-10 h-10 rounded-full object-cover">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $followedUser->name }}</p>
                                            <p class="text-xs text-gray-500">&#64;{{ $followedUser->username }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-500">You aren't following anyone yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Posts Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Posts</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
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
                                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center p-4">
                                    <p class="text-white text-center line-clamp-6 text-sm sm:text-base">{{ $post->content }}</p>
                                </div>
                            @endif

                            <!-- Overlay with stats -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center space-x-4 sm:space-x-6 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-semibold text-sm sm:text-base">{{ $post->reactions->count() }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-semibold text-sm sm:text-base">{{ $post->comments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-8 sm:py-12">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600 text-sm sm:text-base">No posts yet</p>
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

<div id="profileAvatarModal" class="profile-avatar-modal">
    <span class="profile-avatar-modal__close">&times;</span>
    <img class="profile-avatar-modal__content" id="profileAvatarModalImage" alt="Profile avatar preview">
    <div id="profileAvatarCaption" class="profile-avatar-modal__caption"></div>
</div>

<style>
    .profile-avatar-wrapper {
        border-radius: 50%;
    }

    .profile-avatar-img {
        /* border-radius: 50%; */
        cursor: pointer;
        transition: 0.3s;
    }

    .profile-avatar-img:hover {
        opacity: 0.8;
    }

    .profile-avatar-modal {
        display: none;
        position: fixed;
        z-index: 100;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .profile-avatar-modal__content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        animation-name: avatarZoom;
        animation-duration: 0.6s;
    }

    .profile-avatar-modal__caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
        animation-name: avatarZoom;
        animation-duration: 0.6s;
    }

    @keyframes avatarZoom {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    .profile-avatar-modal__close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .profile-avatar-modal__close:hover,
    .profile-avatar-modal__close:focus {
        color: #bbb;
    }

    @media only screen and (max-width: 700px) {
        .profile-avatar-modal__content {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const triggerImage = document.getElementById('profileAvatarImage');
        const modal = document.getElementById('profileAvatarModal');
        const modalImage = document.getElementById('profileAvatarModalImage');
        const caption = document.getElementById('profileAvatarCaption');
        const closeButton = modal?.querySelector('.profile-avatar-modal__close');

        if (!triggerImage || !modal || !modalImage || !caption || !closeButton) {
            return;
        }

        triggerImage.addEventListener('click', function () {
            modal.style.display = 'block';
            modalImage.src = this.src;
            caption.innerHTML = this.alt || '';
        });

        closeButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        });
    });
</script>
