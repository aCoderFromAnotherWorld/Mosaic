<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Post Header -->
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('profile.show', $post->user->username) }}">
                            <img src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                                 alt="{{ $post->user->name }}" 
                                 class="w-12 h-12 rounded-full object-cover">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold text-gray-900 hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>

                    @if($post->user_id === auth()->id())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-10">
                                <a href="{{ route('posts.edit', $post) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Edit Post
                                </a>
                                <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        Delete Post
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Post Content -->
                @if($post->content)
                    <div class="px-4 pb-4">
                        <p class="text-gray-800 text-lg whitespace-pre-wrap">{{ $post->content }}</p>
                    </div>
                @endif

                <!-- Post Media -->
                @if($post->media->isNotEmpty())
                    <div class="grid {{ $post->media->count() === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-1">
                        @foreach($post->media as $media)
                            @if($media->media_type === 'image')
                                <img src="{{ asset('storage/' . $media->file_path) }}" 
                                     alt="Post image" 
                                     class="w-full {{ $post->media->count() === 1 ? 'max-h-[700px]' : 'h-80' }} object-cover">
                            @else
                                <video controls class="w-full {{ $post->media->count() === 1 ? 'max-h-[700px]' : 'h-80' }} object-cover">
                                    <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Post Stats -->
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $post->reactions->count() }} {{ Str::plural('reaction', $post->reactions->count()) }}</span>
                        <span>{{ $post->allComments->count() }} {{ Str::plural('comment', $post->allComments->count()) }}</span>
                    </div>
                </div>

                <!-- Post Actions -->
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex items-center justify-around">
                        <!-- Like Button -->
                        <form method="POST" action="{{ route('posts.react', $post) }}" class="inline-block">
                            @csrf
                            <input type="hidden" name="type" value="like">
                            <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition">
                                <svg class="w-6 h-6 {{ $post->reactions->where('user_id', auth()->id())->isNotEmpty() ? 'fill-blue-600 text-blue-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span class="font-medium">Like</span>
                            </button>
                        </form>

                        <!-- Comment Button -->
                        <button onclick="document.getElementById('comment-input').focus()" class="flex items-center space-x-2 text-gray-600 hover:text-green-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="font-medium">Comment</span>
                        </button>

                        <!-- Share Button -->
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="font-medium">Share</span>
                        </button>
                    </div>
                </div>

                <!-- Add Comment Form -->
                <div class="border-t border-gray-200 px-4 py-4">
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <div class="flex space-x-3">
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1">
                                <textarea name="content" 
                                          id="comment-input"
                                          rows="2" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                          placeholder="Write a comment..."
                                          required></textarea>
                                <div class="mt-2 flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                @if($post->comments->isNotEmpty())
                    <div class="border-t border-gray-200 px-4 py-4 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                        
                        @foreach($post->comments as $comment)
                            <div class="space-y-3">
                                <!-- Main Comment -->
                                <div class="flex space-x-3">
                                    <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                         alt="{{ $comment->user->name }}" 
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    <div class="flex-1">
                                        <div class="bg-gray-100 rounded-lg px-4 py-3">
                                            <a href="{{ route('profile.show', $comment->user->username) }}" class="font-semibold text-gray-900 hover:underline">
                                                {{ $comment->user->name }}
                                            </a>
                                            <p class="text-gray-800 mt-1">{{ $comment->content }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                                            <button onclick="toggleReplyForm({{ $comment->id }})" class="hover:underline font-medium">Reply</button>
                                            @if($comment->user_id === auth()->id())
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Delete this comment?')" class="hover:underline font-medium text-red-600">
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
                                                              class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" 
                                                              placeholder="Write a reply..."
                                                              required></textarea>
                                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm h-fit">
                                                        Reply
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Replies -->
                                        @if($comment->replies->isNotEmpty())
                                            <div class="ml-8 mt-3 space-y-3">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex space-x-3">
                                                        <img src="{{ $reply->user->profile_picture ? asset('storage/' . $reply->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
                                                             alt="{{ $reply->user->name }}" 
                                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                                        <div class="flex-1">
                                                            <div class="bg-gray-100 rounded-lg px-3 py-2">
                                                                <a href="{{ route('profile.show', $reply->user->username) }}" class="font-semibold text-sm text-gray-900 hover:underline">
                                                                    {{ $reply->user->name }}
                                                                </a>
                                                                <p class="text-sm text-gray-800 mt-1">{{ $reply->content }}</p>
                                                            </div>
                                                            <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                                                <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                                @if($reply->user_id === auth()->id())
                                                                    <form method="POST" action="{{ route('comments.destroy', $reply) }}" class="inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" onclick="return confirm('Delete this reply?')" class="hover:underline font-medium text-red-600">
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

    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.querySelector('textarea').focus();
            }
        }
    </script>
</x-app-layout>