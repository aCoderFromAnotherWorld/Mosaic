<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Posts -->
            <div class="space-y-6">
                @forelse($posts as $post)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <!-- Post Header -->
                        <div class="p-4 sm:p-6 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('profile.show', $post->user->username) }}" class="flex-shrink-0">
                                    <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                                         alt="{{ $post->user->name }}" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-gray-100">
                                </a>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                        {{ $post->user->name }}
                                    </a>
                                    <p class="text-xs sm:text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if($post->user_id === auth()->id())
                                <div class="relative">
                                    <button type="button"
                                            onclick="toggleDropdown(this)"
                                            aria-haspopup="menu"
                                            aria-controls="post-action-menu-{{ $post->id }}"
                                            aria-expanded="false"
                                        class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>

                                    <div id="post-action-menu-{{ $post->id }}"
                                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-20 border border-gray-200"
                                         role="menu">
                                        <a href="{{ route('posts.edit', $post) }}"
                                           class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                            Edit Post
                                        </a>
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this post?')"
                                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                                Delete Post
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Post Content -->
                        @if($post->content)
                            <div class="px-4 sm:px-6 pb-3 sm:pb-4">
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $post->content }}</p>
                            </div>
                        @endif

                        <!-- Post Media -->
                        @if($post->media->isNotEmpty())
                            <div class="grid {{ $post->media->count() === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-1">
                                @foreach($post->media as $media)
                                    @if($media->media_type === 'image')
                                        <img src="{{ asset('storage/' . $media->file_path) }}" 
                                             alt="Post image" 
                                             class="w-full {{ $post->media->count() === 1 ? 'max-h-[600px] sm:max-h-[700px]' : 'h-48 sm:h-64' }} object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                             onclick="openImageModal('{{ asset('storage/' . $media->file_path) }}')">
                                    @else
                                        <video controls class="w-full {{ $post->media->count() === 1 ? 'max-h-[600px] sm:max-h-[700px]' : 'h-48 sm:h-64' }} object-cover">
                                            <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                        </video>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Post Actions -->
                        <div class="border-t border-gray-100 px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <span id="reaction-count-{{ $post->id }}">{{ $post->reactions->count() }} {{ Str::plural('reaction', $post->reactions->count()) }}</span>
                                <span>{{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}</span>
                            </div>

                            @php
                                $userReaction = $post->reactions->firstWhere('user_id', auth()->id());
                            @endphp
                            <div class="flex items-center justify-around border-t border-gray-100 pt-3">
                                <!-- Like Button -->
                                @if($userReaction)
                                    <form method="POST" action="{{ route('posts.unreact', $post) }}" class="flex items-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center space-x-2 text-red-500 hover:text-red-600 transition-colors group">
                                            <svg class="w-5 h-5 fill-red-500 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span class="font-medium">Unlike</span>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('posts.react', $post) }}" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="type" value="like">
                                        <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-red-500 transition-colors group">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span class="font-medium">Like</span>
                                        </button>
                                    </form>
                                @endif

                                <!-- Comment Button -->
                                <button onclick="document.getElementById('comment-form-{{ $post->id }}').classList.toggle('hidden')" class="flex items-center space-x-2 text-gray-600 hover:text-blue-500 transition-colors group">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span class="font-medium">Comment</span>
                                </button>

                                <!-- Share Button -->
                                <button class="flex items-center space-x-2 text-gray-600 hover:text-green-500 transition-colors group">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                    </svg>
                                    <span class="font-medium">Share</span>
                                </button>
                            </div>
                        </div>

                        <!-- Comment Form -->
                        <div id="comment-form-{{ $post->id }}" class="hidden border-t border-gray-100 px-4 sm:px-6 py-3 sm:py-4">
                            <form method="POST" action="{{ route('comments.store', $post) }}">
                                @csrf
                                <div class="flex space-x-3">
                                    <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    <div class="flex-1">
                                        <textarea name="content" 
                                                  rows="2" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" 
                                                  placeholder="Write a comment..."
                                                  required></textarea>
                                        <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Comments Section -->
                        @if($post->comments->isNotEmpty())
                            <div class="border-t border-gray-100 px-4 sm:px-6 py-3 sm:py-4 space-y-3">
                                @foreach($post->comments->take(3) as $comment)
                                    @php
                                        $commentLikesCount = $comment->likes->count();
                                        $commentLiked = $comment->isLikedBy(auth()->user());
                                        $replyFormId = "feed-reply-form-{$post->id}-{$comment->id}";
                                    @endphp
                                    <div class="flex space-x-3">
                                        <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                             alt="{{ $comment->user->name }}" 
                                             class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                        <div class="flex-1">
                                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                                <a href="{{ route('profile.show', $comment->user->username) }}" class="font-semibold text-sm text-gray-900 hover:text-indigo-600 transition-colors">
                                                    {{ $comment->user->name }}
                                                </a>
                                                <p class="text-sm text-gray-800 mt-1">{{ $comment->content }}</p>
                                            </div>
                                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                <div class="flex items-center space-x-2">
                                                    @if($commentLiked)
                                                        <form method="POST" action="{{ route('comments.unlike', $comment) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="hover:text-red-600 transition-colors font-medium">Unlike</button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('comments.like', $comment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="hover:text-gray-700 transition-colors font-medium">Like</button>
                                                        </form>
                                                    @endif
                                                    @if($commentLikesCount > 0)
                                                        <span class="text-gray-400">{{ $commentLikesCount }} {{ Str::plural('like', $commentLikesCount) }}</span>
                                                    @endif
                                                </div>
                                                <button type="button" class="hover:text-gray-700 transition-colors" onclick="toggleFeedReplyForm('{{ $replyFormId }}')">Reply</button>
                                            </div>
                                            <div id="{{ $replyFormId }}" class="hidden mt-2">
                                                <form method="POST" action="{{ route('comments.store', $post) }}">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <div class="flex space-x-2">
                                                        <textarea name="content" rows="2" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Write a reply..." required></textarea>
                                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm h-fit">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @if($comment->replies->isNotEmpty())
                                                <div class="ml-6 mt-3 space-y-3">
                                                    @foreach($comment->replies->take(2) as $reply)
                                                        <div class="flex space-x-3">
                                                            <img src="{{ $reply->user->profile_picture ? asset('storage/' . $reply->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
                                                                 alt="{{ $reply->user->name }}" 
                                                                 class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                                            <div class="flex-1">
                                                                <div class="bg-gray-50 rounded-lg px-3 py-2">
                                                                    <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-xs text-gray-900 hover:text-indigo-600 transition-colors">
                                                                        {{ $reply->user->name }}
                                                                    </a>
                                                                    <p class="text-xs text-gray-700 mt-1">{{ $reply->content }}</p>
                                                                </div>
                                                                <div class="flex items-center space-x-3 mt-1 text-[11px] text-gray-500">
                                                                    <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                                    <a href="{{ route('posts.show', $post) }}#comment-{{ $reply->id }}" class="hover:text-indigo-600 transition-colors font-medium">View thread</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if($comment->replies->count() > 2)
                                                        <a href="{{ route('posts.show', $post) }}#comment-{{ $comment->id }}" class="text-xs text-indigo-600 hover:text-indigo-500 transition-colors font-medium">
                                                            View {{ $comment->replies->count() - 2 }} more {{ Str::plural('reply', $comment->replies->count() - 2) }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @if($post->comments->count() > 3)
                                    <a href="{{ route('posts.show', $post) }}" class="text-sm text-indigo-600 hover:text-indigo-500 transition-colors font-medium">
                                        View all {{ $post->comments->count() }} comments
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No posts yet</h3>
                        <p class="text-gray-600 mb-6">Start following people to see their posts in your feed!</p>
                        <a href="{{ route('posts.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Create Your First Post
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <img id="modalImage" src="" alt="Full size image" class="max-h-full max-w-full rounded-lg">
    </div>

    <script>
        function toggleFeedReplyForm(formId) {
            const form = document.getElementById(formId);
            if (!form) {
                return;
            }

            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                const textarea = form.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            }
        }

        function toggleDropdown(button) {
            const menu = button.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('[id^="post-action-menu-"]').forEach(function(menu) {
                    menu.classList.add('hidden');
                });
            }
        });

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>
