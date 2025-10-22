<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="height: calc(100vh - 120px);">
                <div class="h-full flex">
                    <!-- Conversations List -->
                    <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col">
                        <!-- Header -->
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Messages</h2>
                            
                            <!-- Search Users -->
                            <div class="relative">
                                <input type="text" 
                                       id="user-search" 
                                       placeholder="Search users to message..." 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden max-h-60 overflow-y-auto"></div>
                            </div>
                        </div>

                        <!-- Conversations -->
                        <div class="flex-1 overflow-y-auto">
                            @forelse($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->users->firstWhere('id', '!=', auth()->id()) ?? $conversation->users->first();
                                    $lastMessage = $conversation->messages()->latest()->first();
                                @endphp
                                @continue(!$otherUser)
                                <a href="{{ route('messages.show', $conversation) }}" 
                                   class="flex items-center p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                                    <img src="{{ $otherUser->profile_picture ? asset('storage/' . $otherUser->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name ?? 'Unknown User') . '&size=200' }}" 
                                         alt="{{ $otherUser->name ?? __('Unknown User') }}" 
                                         class="w-12 h-12 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $otherUser->name ?? __('Unknown User') }}
                                            </p>
                                            @if($lastMessage)
                                                <p class="text-xs text-gray-500">
                                                    {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($lastMessage)
                                            <p class="text-sm text-gray-600 truncate">
                                                {{ $lastMessage->user_id === auth()->id() ? 'You: ' : '' }}
                                                {{ $lastMessage->message }}
                                            </p>
                                        @endif
                                        @if($conversation->messages_count > 0)
                                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-indigo-600 rounded-full">
                                                {{ $conversation->messages_count }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No messages yet</h3>
                                    <p class="text-gray-600">Start a conversation by searching for a user above!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Empty State for Desktop -->
                    <div class="hidden md:flex flex-1 items-center justify-center bg-gray-50">
                        <div class="text-center">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Your Messages</h3>
                            <p class="text-gray-600">Select a conversation to start messaging</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('user-search').addEventListener('input', function(e) {
            const query = e.target.value;
            const resultsDiv = document.getElementById('search-results');
            
            if (query.length < 2) {
                resultsDiv.classList.add('hidden');
                return;
            }

            fetch(`/search/users?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.users && data.users.length > 0) {
                        resultsDiv.innerHTML = data.users.map(user => `
                            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="startConversation(${user.id})">
                                <div class="flex items-center space-x-3">
                                    <img src="${user.profile_picture ? '/storage/' + user.profile_picture : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&size=200'}" 
                                         alt="${user.name}" 
                                         class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">${user.name}</p>
                                        <p class="text-xs text-gray-500">@${user.username}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                        resultsDiv.classList.remove('hidden');
                    } else {
                        resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">No users found</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.classList.add('hidden');
                });
        });

        function startConversation(userId) {
            window.location.href = `/messages/create/${userId}`;
        }

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            const searchResults = document.getElementById('search-results');
            const searchInput = document.getElementById('user-search');
            
            if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
