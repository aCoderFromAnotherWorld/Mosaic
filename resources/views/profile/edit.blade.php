<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Profile Picture -->
                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=200' }}" 
                                 alt="Profile" 
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                            <input type="file" 
                                   id="profile_picture" 
                                   name="profile_picture" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        @error('profile_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Picture -->
                    <div>
                        <label for="cover_picture" class="block text-sm font-medium text-gray-700 mb-2">Cover Picture</label>
                        <div class="space-y-2">
                            @if(auth()->user()->cover_picture)
                                <img src="{{ asset('storage/' . auth()->user()->cover_picture) }}" 
                                     alt="Cover" 
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            @endif
                            <input type="file" 
                                   id="cover_picture" 
                                   name="cover_picture" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        @error('cover_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', auth()->user()->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', auth()->user()->username) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Section -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h2 class="text-lg font-medium text-gray-900 mb-2">Delete Account</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </p>
                
                <button type="button" 
                        onclick="openDeleteModal()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Account</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
                
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
