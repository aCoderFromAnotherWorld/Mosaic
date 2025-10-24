<x-app-layout>
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Enhanced Background Effects -->
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute left-20 top-20 h-64 w-64 rounded-full bg-gradient-to-r from-blue-400/20 to-purple-400/15 blur-3xl animate-pulse"></div>
            <div class="absolute right-[-8rem] top-40 h-[32rem] w-[32rem] rounded-full bg-gradient-to-r from-indigo-400/15 to-pink-400/10 blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(120,119,198,0.05),_transparent_50%)]"></div>
        </div>

        <div class="relative z-10 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/40 backdrop-blur-sm px-4 py-2 text-sm font-semibold uppercase tracking-wider text-gray-700 shadow-lg mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        Edit Post
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Update Your Post</h1>
                    <p class="text-lg text-gray-600">Make changes to your post content</p>
                </div>

                <!-- Form Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden hover:shadow-blue-500/10 transition-shadow duration-300">
                    <div class="p-8 sm:p-10">
                        <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Content Section -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label for="content" class="block text-lg font-semibold text-gray-900">
                                            Post Content
                                        </label>
                                        <p class="text-sm text-gray-600">Share your thoughts and updates</p>
                                    </div>
                                </div>

                                <div class="relative">
                                    <textarea name="content"
                                              id="content"
                                              rows="8"
                                              class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-white/50 backdrop-blur-sm resize-none transition-all duration-300 placeholder-gray-400 text-gray-900 @error('content') border-red-400 focus:ring-red-500/20 @enderror"
                                              placeholder="What's on your mind? Share your story, thoughts, or updates...">{{ old('content', $post->content) }}</textarea>

                                    <!-- Character Counter -->
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-500 bg-white/80 backdrop-blur-sm px-2 py-1 rounded-lg">
                                        <span id="char-count">0</span>/500
                                    </div>
                                </div>

                                @error('content')
                                    <div class="flex items-center gap-2 text-red-600 bg-red-50 border border-red-200 rounded-xl p-4">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 010 2zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm">{{ $message }}</p>
                                    </div>
                                @enderror
                            </div>

                            <!-- Existing Media Section -->
                            @if($post->media->isNotEmpty())
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-teal-500 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Current Media</h3>
                                            <p class="text-sm text-gray-600">Attached images and videos</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($post->media as $media)
                                            <div class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white/50 backdrop-blur-sm hover:border-blue-400 transition-all duration-300 transform hover:scale-105">
                                                @if($media->media_type === 'image')
                                                    <img src="{{ asset('storage/' . $media->file_path) }}"
                                                         alt="Post media"
                                                         class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-110">
                                                @else
                                                    <video src="{{ asset('storage/' . $media->file_path) }}"
                                                           class="w-full h-32 object-cover" controls></video>
                                                @endif
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 010 2zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-amber-800">Media Note</p>
                                                <p class="text-sm text-amber-700 mt-1">Media cannot be changed after posting. If you need to update media, please delete this post and create a new one.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('posts.show', $post) }}"
                                   class="inline-flex items-center justify-center gap-2 px-8 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-blue-500/25 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Character counter for textarea
        document.getElementById('content').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('char-count').textContent = count;
            if (count > 450) {
                document.getElementById('char-count').className = 'text-red-500';
            } else {
                document.getElementById('char-count').className = 'text-gray-500';
            }
        });

        // Initialize character count on load
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('content');
            document.getElementById('char-count').textContent = textarea.value.length;
        });
    </script>
</x-app-layout>
