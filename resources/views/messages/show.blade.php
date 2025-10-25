o<x-app-layout>
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
                                <div class="flex items-end space-x-2 max-w-xs lg:max-w-md relative">
                                    @if($message->user_id !== auth()->id())
                                        <img src="{{ $message->user->profile_picture ? asset('storage/' . $message->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}"
                                             alt="{{ $message->user->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @endif

                                    <div class="group relative">
                                        <div class="px-4 py-2 rounded-2xl {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-green-500 text-white' }}">
                                            <p class="break-words">{{ $message->message }}</p>
                                            @if($message->is_edited)
                                                <span class="text-xs opacity-70">(edited)</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 {{ $message->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                            {{ $message->created_at->format('g:i A') }}
                                        </p>

                                        @if($message->user_id === auth()->id())
                                            <button type="button"
                                                    onclick="toggleMessageMenu(this, {{ $message->id }})"
                                                    class="absolute -left-8 top-2 text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>

                                            <div id="message-menu-{{ $message->id }}"
                                                 class="hidden absolute w-32 bg-white rounded-lg shadow-lg py-1 z-10 border border-gray-200"
                                                 style="left: -144px; top: 8px;">
                                                <button type="button"
                                                        onclick="startEditMessage({{ $message->id }}, '{{ addslashes($message->message) }}')"
                                                        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                    Edit
                                                </button>
                                                <button type="button"
                                                        onclick="deleteMessage({{ $message->id }})"
                                                        class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                    Delete
                                                </button>
                                            </div>
                                        @endif
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
                            <button type="submit" id="send-button" class="bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                            <button type="button" id="update-button" class="hidden bg-green-600 text-white p-3 rounded-lg hover:bg-green-700 transition flex-shrink-0" onclick="submitEdit()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <button type="button" id="cancel-button" class="hidden bg-gray-600 text-white p-3 rounded-lg hover:bg-gray-700 transition flex-shrink-0" onclick="cancelEdit()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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

        // Toggle message menu
        function toggleMessageMenu(button, messageId) {
            const menu = document.getElementById(`message-menu-${messageId}`);

            // Close all other menus
            document.querySelectorAll('[id^="message-menu-"]').forEach(m => {
                if (m.id !== `message-menu-${messageId}`) {
                    m.classList.add('hidden');
                }
            });

            // Toggle current menu
            menu.classList.toggle('hidden');
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.group')) {
                document.querySelectorAll('[id^="message-menu-"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Start editing message
        let editingMessageId = null;

        function startEditMessage(messageId, currentMessage) {
            editingMessageId = messageId;
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const updateButton = document.getElementById('update-button');
            const cancelButton = document.getElementById('cancel-button');

            // Populate textarea with current message
            messageInput.value = currentMessage;
            messageInput.focus();

            // Show update and cancel buttons, hide send button
            sendButton.classList.add('hidden');
            updateButton.classList.remove('hidden');
            cancelButton.classList.remove('hidden');

            // Close menu
            toggleMessageMenu(null, messageId);
        }

        // Submit edit
        function submitEdit() {
            if (!editingMessageId) return;

            const messageInput = document.getElementById('message-input');
            const newMessage = messageInput.value.trim();

            if (newMessage === '') {
                alert('Message cannot be empty');
                return;
            }

            // Create a temporary form to handle the request properly
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/messages/${editingMessageId}`;
            form.style.display = 'none';

            // Add CSRF token
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(tokenInput);

            // Add method spoofing for PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);

            // Add message
            const messageInputField = document.createElement('input');
            messageInputField.type = 'hidden';
            messageInputField.name = 'message';
            messageInputField.value = newMessage;
            form.appendChild(messageInputField);

            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Cancel edit
        function cancelEdit() {
            editingMessageId = null;
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const updateButton = document.getElementById('update-button');
            const cancelButton = document.getElementById('cancel-button');

            // Clear textarea and reset buttons
            messageInput.value = '';
            sendButton.classList.remove('hidden');
            updateButton.classList.add('hidden');
            cancelButton.classList.add('hidden');
        }

        // Delete message
        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message?')) {
                fetch(`/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete message');
                });
            }
        }
    </script>
</x-app-layout>