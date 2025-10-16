<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">My Posts</h2>
                <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create New Post
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Post Media Thumbnail -->
                        @if($post->media->isNotEmpty())
                            <a href="{{ route('posts.show', $post) }}" class="block">
                                @if($post->media->first()->media_type === 'image')
                                    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" 
                                         alt="Post thumbnail" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <video src="{{ asset('storage/' . $post->media->first()->file_path) }}" 
                                           class="w-full h-48 object-cover"></video>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('posts.show', $post) }}" class="block bg-gradient-to-br from-blue-500 to-purple-600 h-48 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </a>
                        @endif

                        <!-- Post Info -->
                        <div class="p-4">
                            @if($post->content)
                                <p class="text-gray-800 mb-3 line-clamp-2">{{ $post->content }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                        </svg>
                                        {{ $post->reactions->count() }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        {{ $post->comments->count() }}
                                    </span>
                                </div>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('posts.show', $post) }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('posts.edit', $post) }}" class="flex-1 text-center bg-blue-100 text-blue-700 py-2 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="w-full bg-red-100 text-red-700 py-2 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No posts yet</h3>
                        <p class="text-gray-600 mb-6">Start sharing your thoughts with the world!</p>
                        <a href="{{ route('posts.create') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
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
</x-app-layout>