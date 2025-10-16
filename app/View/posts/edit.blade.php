<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Post</h2>

                <form method="POST" action="{{ route('posts.update', $post) }}">
                    @csrf
                    @method('PUT')

                    <!-- Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Content
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="6" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror" 
                                  placeholder="What's on your mind?">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Existing Media -->
                    @if($post->media->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Media
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($post->media as $media)
                                    <div class="relative">
                                        @if($media->media_type === 'image')
                                            <img src="{{ asset('storage/' . $media->file_path) }}" 
                                                 alt="Post media" 
                                                 class="w-full h-32 object-cover rounded-lg">
                                        @else
                                            <video src="{{ asset('storage/' . $media->file_path) }}" 
                                                   class="w-full h-32 object-cover rounded-lg"></video>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Note: Media cannot be changed after posting. Delete and create a new post if you need to change media.</p>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('posts.show', $post) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>