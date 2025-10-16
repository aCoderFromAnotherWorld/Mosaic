<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden" style="height: calc(100vh - 120px);">
                <div class="h-full flex">
                    <!-- Conversations List -->
                    <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col">
                        <!-- Header -->
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Messages</h2>
                        </div>

                        <!-- Conversations -->
                        <div class="flex-1 overflow-y-auto">
                            @forelse($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->users->first();
                                    $lastMessage = $conversation->messages()->latest()->first();
                                @endphp
                                <a href="{{ route('messages.show', $conversation) }}" 
                                   class="flex items-center p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                                    <img src="{{ $otherUser->profile_picture ? asset('storage/' . $otherUser->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" 
                                         alt="{{ $otherUser->name }}" 
                                         class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $otherUser->name }}
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
                                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-600 rounded-full">
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
                                    <p class="text-gray-600">Start a conversation by visiting someone's profile!</p>
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
</x-app-layout>