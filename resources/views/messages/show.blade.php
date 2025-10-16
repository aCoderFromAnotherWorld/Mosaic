<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden" style="height: calc(100vh - 120px);">
                <div class="h-full flex flex-col">
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('messages.index') }}" class="md:hidden text-gray-600 hover:text-gray-900">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <a href="{{ route('profile.show', $otherUser->username) }}">
                                <img src="{{ $otherUser->profile_picture ? asset('storage/' . $otherUser->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" 
                                     alt="{{ $otherUser->name }}" 
                                     class="w-10 h-10 rounded-full object-cover">
                            </a>
                            <div>
                                <a href="{{ route('profile.show', $otherUser->username) }}" class="font-semibold text-gray-900 hover:underline">
                                    {{ $otherUser->name }}
                                </a>
                                <p class="text-xs text-gray-500">&#64;{{ $otherUser->username }}</p>
                            </div>
                        </div>

                        <a href="{{ route('profile.show', $otherUser->username) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View Profile
                        </a>
                    </div>

                    <!-- Messages Container -->
                    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end space-x-2 max-w-xs lg:max-w-md">
                                    @if($message->user_id !== auth()->id())
                                        <img src="{{ $message->user->profile_picture ? asset('storage/' . $message->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}" 
                                             alt="{{ $message->user->name }}" 
                                             class="w-8 h-8 rounded-full object-cover">
                                    @endif
                                    
                                    <div>
                                        <div class="px-4 py-2 rounded-2xl {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-900' }}">
                                            <p class="break-words">{{ $message->message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 {{ $message->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                            {{ $message->created_at->format('g:i A') }}
                                        </p>
                                    </div>

                                    @if($message->user_id === auth()->id())
                                        <img src="{{ $message->user->profile_picture ? asset('storage/' . $message->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}" 
                                             alt="{{ $message->user->name }}" 
                                             class="w-8 h-8 rounded-full object-cover">
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-gray-600">No messages yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200 bg-white">
                        <form method="POST" action="{{ route('messages.store', $otherUser) }}" id="message-form" class="flex items-end space-x-2">
                            @csrf
                            <div class="flex-1">
                                <textarea name="message" 
                                          id="message-input"
                                          rows="1" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                          placeholder="Type a message..."
                                          required
                                          onkeydown="handleKeyPress(event)"></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom on page load
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Handle Enter key to send message
        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                document.getElementById('message-form').submit();
            }
        }

        // Auto-resize textarea
        const messageInput = document.getElementById('message-input');
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
</x-app-layout>