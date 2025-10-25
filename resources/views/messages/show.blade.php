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
                                <div class="flex items-end space-x-2 max-w-xs lg:max-w-md relative">
                                    @if($message->user_id !== auth()->id())
                                        <img src="{{ $message->user->profile_picture ? asset('storage/' . $message->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}"
                                             alt="{{ $message->user->name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @endif

                                    <div class="group relative">
                                        <div class="px-4 py-2 rounded-2xl {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-green-500 text-white' }}">
                                            @if($message->attachment_path)
                                                @if(str_contains($message->attachment_type, 'image'))
                                                    <img src="{{ asset('storage/' . $message->attachment_path) }}" alt="{{ $message->attachment_name }}" class="max-w-full h-auto rounded-lg mb-2 cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $message->attachment_path) }}')">
                                                @elseif(str_contains($message->attachment_type, 'video'))
                                                    <video controls class="max-w-full h-auto rounded-lg mb-2">
                                                        <source src="{{ asset('storage/' . $message->attachment_path) }}" type="{{ $message->attachment_type }}">
                                                    </video>
                                                @else
                                                    <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank" class="flex items-center space-x-2 text-blue-200 hover:text-blue-100 mb-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <span class="text-sm">{{ $message->attachment_name }}</span>
                                                        <span class="text-xs opacity-70">({{ number_format($message->attachment_size / 1024, 1) }} KB)</span>
                                                    </a>
                                                @endif
                                            @endif
                                            @if($message->message)
                                                <p class="break-words">{{ $message->message }}</p>
                                                @if($message->is_edited)
                                                    <span class="text-xs opacity-70">(edited)</span>
                                                @endif
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
                                                @if(!$message->attachment_path)
                                                    <button type="button"
                                                            onclick="startEditMessage({{ $message->id }}, '{{ addslashes($message->message ?? '') }}')"
                                                            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                        Edit
                                                    </button>
                                                @endif
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
                        <form method="POST" action="{{ route('messages.store', $otherUser) }}" id="message-form" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <!-- Attachment Preview -->
                            <div id="attachment-preview" class="hidden flex items-center space-x-2 p-2 bg-gray-50 rounded-lg border">
                                <span id="attachment-name" class="text-sm text-gray-700 flex-1"></span>
                                <button type="button" onclick="removeAttachment()" class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Message Input Row -->
                            <div class="flex items-end space-x-2">
                                <div class="flex-1 relative">
                                    <div class="flex">
                                        <textarea name="message"
                                            id="message-input"
                                            rows="1"
                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                            placeholder="Type a message..."
                                            autocomplete="off"
                                            onkeydown="handleKeyPress(event)">
                                        </textarea>

                                        <!-- Attachment Button -->
                                        <button type="button"
                                                onclick="document.getElementById('attachment-input').click()"
                                                class="right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Hidden File Input -->
                                    <input type="file"
                                           id="attachment-input"
                                           name="attachment"
                                           class="hidden"
                                           accept="image/*,video/*,.pdf,.doc,.docx,.txt,.zip,.rar"
                                           onchange="handleFileSelect(event)">
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
                            </div>
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

        // Clear message input on page load to prevent auto-fill
        window.addEventListener('load', function() {
            document.getElementById('message-input').value = '';
        });

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

        // Handle file selection
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    event.target.value = '';
                    return;
                }

                // Show attachment preview
                const preview = document.getElementById('attachment-preview');
                const nameSpan = document.getElementById('attachment-name');
                nameSpan.textContent = file.name;
                preview.classList.remove('hidden');

                // Make message optional when attachment is present
                document.getElementById('message-input').removeAttribute('required');
            }
        }

        // Remove attachment
        function removeAttachment() {
            document.getElementById('attachment-input').value = '';
            document.getElementById('attachment-preview').classList.add('hidden');
            document.getElementById('attachment-name').textContent = '';

            // Make message required again if no attachment
            const messageInput = document.getElementById('message-input');
            if (!messageInput.value.trim()) {
                messageInput.setAttribute('required', 'required');
            }
        }

        // Open image modal
        function openImageModal(src) {
            // Create modal if it doesn't exist
            let modal = document.getElementById('image-modal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'image-modal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
                modal.onclick = closeImageModal;
                modal.innerHTML = `
                    <img id="modal-image" src="" alt="Full size image" class="max-h-full max-w-full rounded-lg">
                `;
                document.body.appendChild(modal);
            }

            document.getElementById('modal-image').src = src;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Close image modal
        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }
    </script>
</x-app-layout>