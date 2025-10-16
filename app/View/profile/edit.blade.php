<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Profile Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Profile Picture
                            </label>
                            <div class="flex items-center space-x-6">
                                <img id="profile-preview" 
                                     src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200' }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-24 h-24 rounded-full object-cover">
                                <div>
                                    <input type="file" 
                                           name="profile_picture" 
                                           id="profile_picture" 
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewImage(event, 'profile-preview')">
                                    <label for="profile_picture" class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        Change Photo
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (MAX. 2MB)</p>
                                </div>
                            </div>
                            @error('profile_picture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cover Picture
                            </label>
                            <div class="space-y-4">
                                <div class="relative h-48 rounded-lg overflow-hidden bg-gradient-to-r from-blue-500 to-purple-600">
                                    @if($user->cover_picture)
                                        <img id="cover-preview" 
                                             src="{{ asset('storage/' . $user->cover_picture) }}" 
                                             alt="Cover" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img id="cover-preview" 
                                             src="" 
                                             alt="Cover" 
                                             class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <div>
                                    <input type="file" 
                                           name="cover_picture" 
                                           id="cover_picture" 
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewImage(event, 'cover-preview')">
                                    <label for="cover_picture" class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        Change Cover
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (MAX. 5MB)</p>
                                </div>
                            </div>
                            @error('cover_picture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">@</span>
                                <input type="text" 
                                       name="username" 
                                       id="username" 
                                       value="{{ old('username', $user->username) }}"
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Read-only) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   value="{{ $user->email }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                                   disabled>
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                Bio
                            </label>
                            <textarea name="bio" 
                                      id="bio" 
                                      rows="4" 
                                      maxlength="500"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror" 
                                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                @error('bio')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p class="text-xs text-gray-500">Maximum 500 characters</p>
                                @enderror
                                <p class="text-xs text-gray-500">
                                    <span id="bio-count">{{ strlen(old('bio', $user->bio ?? '')) }}</span>/500
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('profile.show', $user->username) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Bio character counter
        const bioTextarea = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');
        
        if (bioTextarea) {
            bioTextarea.addEventListener('input', function() {
                bioCount.textContent = this.value.length;
            });
        }
    </script>
</x-app-layout>