<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Post Header -->
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('profile.show', $post->user->username) }}">
                                <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                                     alt="{{ $post->user->name }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-100">
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $post->user->username) }}" class="text-sm sm:text-base font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                    {{ $post->user->name }}
                                </a>
                                <p class="text-xs sm:text-sm text-gray-500">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        @if($post->user_id === auth()->id())
                            <div class="relative" data-dropdown>
                                <button type="button"
                                        id="post-action-menu-trigger-{{ $post->id }}"
                                        onclick="toggleDropdown(this)"
                                        aria-haspopup="menu"
                                        aria-controls="post-action-menu-{{ $post->id }}"
                                        aria-expanded="false"
                                        class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>

                                <div id="post-action-menu-{{ $post->id }}"
                                     data-dropdown-content
                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20"
                                     role="menu"
                                     aria-labelledby="post-action-menu-trigger-{{ $post->id }}">
                                    <a href="{{ route('posts.edit', $post) }}"
                                       data-dropdown-item
                                       role="menuitem"
                                       tabindex="-1"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        Edit Post
                                    </a>
                                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                data-dropdown-item
                                                role="menuitem"
                                                tabindex="-1"
                                                onclick="return confirm('Are you sure you want to delete this post?')"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 transition-colors">
                                            Delete Post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Content -->
                @if($post->content)
                    <div class="p-4 sm:p-6">
                        <p class="text-gray-900 leading-relaxed text-sm sm:text-base whitespace-pre-wrap">{{ $post->content }}</p>
                    </div>
                @endif

                <!-- Post Media -->
                @if($post->media->isNotEmpty())
                    <div class="grid {{ $post->media->count() === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-1">
                        @foreach($post->media as $media)
                            @if($media->media_type === 'image')
                                <img src="{{ asset('storage/' . $media->file_path) }}" 
                                     alt="Post image" 
                                     class="w-full {{ $post->media->count() === 1 ? 'max-h-[600px] sm:max-h-[700px]' : 'h-48 sm:h-80' }} object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                     onclick="openImageModal('{{ asset('storage/' . $media->file_path) }}')">
                            @else
                                <video controls class="w-full {{ $post->media->count() === 1 ? 'max-h-[600px] sm:max-h-[700px]' : 'h-48 sm:h-80' }} object-cover">
                                    <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Post Stats -->
                <div class="border-t border-gray-200 px-4 sm:px-6 py-3">
                    <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500">
                        <span id="reaction-count-{{ $post->id }}">{{ $post->reactions->count() }} {{ Str::plural('reaction', $post->reactions->count()) }}</span>
                        <span>{{ $post->allComments->count() }} {{ Str::plural('comment', $post->allComments->count()) }}</span>
                    </div>
                </div>

                <!-- Post Actions -->
                    @php
                        $userReaction = $post->reactions->firstWhere('user_id', auth()->id());
                    @endphp
                    <div class="border-t border-gray-200 px-4 sm:px-6 py-3 sm:py-4">
                        <div id="reaction-container-{{ $post->id }}" class="flex items-center justify-around">
                            <!-- Like / Unlike Button -->
                            @if($userReaction)
                                <button type="button" onclick="toggleReaction({{ $post->id }}, 'unlike')" class="flex items-center space-x-1 sm:space-x-2 group">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-red-500 text-red-500 group-hover:scale-110 transition-all" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="text-sm sm:text-base font-medium text-red-500 group-hover:text-red-600 transition-colors">{{ __('Unlike') }}</span>
                                </button>
                            @else
                                <button type="button" onclick="toggleReaction({{ $post->id }}, 'like')" class="flex items-center space-x-1 sm:space-x-2 group">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-red-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-red-500 transition-colors">{{ __('Like') }}</span>
                                </button>
                            @endif

                        <!-- Comment Button -->
                        <button onclick="document.getElementById('comment-input').focus()" class="flex items-center space-x-1 sm:space-x-2 group">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-indigo-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-indigo-500 transition-colors">Comment</span>
                        </button>

                        <!-- Share Button -->
                        <button onclick="sharePost()" class="flex items-center space-x-1 sm:space-x-2 group">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-green-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-green-500 transition-colors">Share</span>
                        </button>
                    </div>
                </div>

                <!-- Add Comment Form -->
                <div class="border-t border-gray-200 px-4 sm:px-6 py-3 sm:py-4">
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <div class="flex space-x-3">
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
                            <div class="flex-1">
                                <textarea name="content" 
                                          id="comment-input"
                                          rows="2" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-transparent resize-none text-sm sm:text-base" 
                                          placeholder="Write a comment..."
                                          required></textarea>
                                <div class="mt-2 flex justify-end">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                @if($post->comments->isNotEmpty())
                    <div class="border-t border-gray-200 px-4 sm:px-6 py-3 sm:py-4 space-y-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Comments</h3>
                        
                        @foreach($post->comments as $comment)
                            <div class="space-y-3">
                                <!-- Main Comment -->
                                <div class="flex space-x-3">
                                    <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                         alt="{{ $comment->user->name }}" 
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-lg px-3 py-2">
                                            <a href="{{ route('profile.show', $comment->user->username) }}" class="text-sm sm:text-base font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                                {{ $comment->user->name }}
                                            </a>
                                            <p class="text-sm sm:text-base text-gray-700 mt-1">{{ $comment->content }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4 mt-2 text-xs sm:text-sm text-gray-500">
                                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                                            <button onclick="toggleReplyForm({{ $comment->id }})" class="hover:text-indigo-600 transition-colors font-medium">Reply</button>
                                            @if($comment->user_id === auth()->id())
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Delete this comment?')" class="hover:text-red-600 transition-colors font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Reply Form -->
                                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-3 ml-4">
                                            <form method="POST" action="{{ route('comments.store', $post) }}">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="flex space-x-2">
                                                    <textarea name="content" 
                                                              rows="2" 
                                                              class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-transparent text-sm" 
                                                              placeholder="Write a reply..."
                                                              required></textarea>
                                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm h-fit">
                                                        Reply
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Replies -->
                                        @if($comment->replies->isNotEmpty())
                                            <div class="ml-6 sm:ml-8 mt-3 space-y-3">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex space-x-3">
                                                        <img src="{{ $reply->user->profile_picture ? asset('storage/' . $reply->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
                                                             alt="{{ $reply->user->name }}" 
                                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
                                                        <div class="flex-1">
                                                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                                                <a href="{{ route('profile.show', $reply->user->username) }}" class="text-xs sm:text-sm font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                                                    {{ $reply->user->name }}
                                                                </a>
                                                                <p class="text-xs sm:text-sm text-gray-700 mt-1">{{ $reply->content }}</p>
                                                            </div>
                                                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                                                <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                                @if($reply->user_id === auth()->id())
                                                                    <form method="POST" action="{{ route('comments.destroy', $reply) }}" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" onclick="return confirm('Delete this reply?')" class="hover:text-red-600 transition-colors font-medium">
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" style="display: none;" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full p-4">
            <img id="modal-image" src="" alt="Post" class="max-w-full max-h-full rounded-lg">
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
                document.querySelectorAll('[data-dropdown-content]').forEach(function(menu) {
                    menu.classList.add('hidden');
                });
            }
        });

        function toggleReaction(postId, action) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (action === 'like') {
                fetch(`/posts/${postId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type: 'like' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateReactionUI(postId, true, data.reactions_count);
                    }
                })
                .catch(error => console.error('Error:', error));
            } else if (action === 'unlike') {
                fetch(`/posts/${postId}/react`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateReactionUI(postId, false, data.reactions_count);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function updateReactionUI(postId, hasReaction, count) {
            const container = document.getElementById('reaction-container-' + postId);
            const countElement = document.getElementById('reaction-count-' + postId);

            if (hasReaction) {
                container.innerHTML = `
                    <button type="button" onclick="toggleReaction(${postId}, 'unlike')" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-red-500 text-red-500 group-hover:scale-110 transition-all" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-red-500 group-hover:text-red-600 transition-colors">{{ __('Unlike') }}</span>
                    </button>

                    <!-- Comment Button -->
                    <button onclick="document.getElementById('comment-input').focus()" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-indigo-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-indigo-500 transition-colors">Comment</span>
                    </button>

                    <!-- Share Button -->
                    <button onclick="sharePost()" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-green-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-green-500 transition-colors">Share</span>
                    </button>
                `;
            } else {
                container.innerHTML = `
                    <button type="button" onclick="toggleReaction(${postId}, 'like')" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-red-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-red-500 transition-colors">{{ __('Like') }}</span>
                    </button>

                    <!-- Comment Button -->
                    <button onclick="document.getElementById('comment-input').focus()" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-indigo-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-indigo-500 transition-colors">Comment</span>
                    </button>

                    <!-- Share Button -->
                    <button onclick="sharePost()" class="flex items-center space-x-1 sm:space-x-2 group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-green-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-green-500 transition-colors">Share</span>
                    </button>
                `;
            }

            countElement.textContent = `${count} ${count === 1 ? 'reaction' : 'reactions'}`;
        }

        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.querySelector('textarea').focus();
            }
        }

        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this post on Mosaic',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copied to clipboard!');
            }
        }

        function openImageModal(imageSrc) {
            document.getElementById('modal-image').src = imageSrc;
            document.getElementById('image-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('image-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>
