<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Post</h2>

                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            What's on your mind?
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="6" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('content') border-red-500 @enderror resize-none" 
                                  placeholder="Share your thoughts...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Media Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Add Photos or Videos
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 sm:p-8 text-center hover:border-indigo-500 transition-colors cursor-pointer" onclick="document.getElementById('media').click()">
                            <input type="file" 
                                   name="media[]" 
                                   id="media" 
                                   multiple 
                                   accept="image/*,video/*"
                                   class="hidden"
                                   onchange="previewMedia(event)">
                            <div>
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">Click to upload photos or videos</p>
                                <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF, MP4 up to 50MB</p>
                            </div>
                        </div>
                        @error('media.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview Area -->
                        <div id="mediaPreview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('feed') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                            Publish Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewMedia(event) {
            const preview = document.getElementById('mediaPreview');
            preview.innerHTML = '';
            
            const files = event.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    
                    if (file.type.startsWith('image/')) {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        `;
                    } else if (file.type.startsWith('video/')) {
                        div.innerHTML = `
                            <video src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-200"></video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 rounded-lg">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                </svg>
                            </div>
                        `;
                    }
                    
                    preview.appendChild(div);
                }
                
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>