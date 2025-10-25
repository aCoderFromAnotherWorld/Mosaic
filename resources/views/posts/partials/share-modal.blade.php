<div id="share-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" data-share-close></div>
    <div class="relative mx-auto mt-16 w-full max-w-2xl px-4 sm:px-6">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Share Post</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition" data-share-close aria-label="Close share modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-5 py-4 space-y-4">
                <p class="text-sm text-gray-600">
                    Select the friends or followers you want to share this post with.
                </p>

                <div>
                    <label for="share-search" class="sr-only">Search people</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="share-search"
                               type="text"
                               placeholder="Search friends and followers..."
                               class="w-full rounded-xl border border-gray-200 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 transition"
                               data-share-search>
                    </div>
                </div>

                @php
                    $listedUserIds = [];
                @endphp

                <div class="max-h-72 overflow-y-auto space-y-6 pr-1" data-share-list>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Friends</h4>
                        @if($shareFriends->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($shareFriends as $friend)
                                    @php $listedUserIds[] = $friend->id; @endphp
                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition share-person-item" data-share-person data-name="{{ strtolower($friend->name . ' ' . $friend->username) }}">
                                        <input type="checkbox" value="{{ $friend->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 share-person-checkbox">
                                        <img src="{{ $friend->profile_picture ? asset('storage/' . $friend->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($friend->name) }}"
                                             alt="{{ $friend->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $friend->name }}</p>
                                            <p class="text-xs text-gray-500">&#64;{{ $friend->username }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">You have no friends yet.</p>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Followers</h4>
                        @php
                            $uniqueFollowers = $shareFollowers->filter(fn($follower) => !in_array($follower->id, $listedUserIds));
                        @endphp
                        @if($uniqueFollowers->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($uniqueFollowers as $follower)
                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition share-person-item" data-share-person data-name="{{ strtolower($follower->name . ' ' . $follower->username) }}">
                                        <input type="checkbox" value="{{ $follower->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 share-person-checkbox">
                                        <img src="{{ $follower->profile_picture ? asset('storage/' . $follower->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->name) }}"
                                             alt="{{ $follower->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $follower->name }}</p>
                                            <p class="text-xs text-gray-500">&#64;{{ $follower->username }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No additional followers available.</p>
                        @endif
                    </div>
                </div>

                <div id="share-feedback" class="hidden text-sm"></div>
            </div>

            <div class="px-5 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                <button type="button" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition" data-share-close>
                    Cancel
                </button>
                <button type="button"
                        id="share-submit-button"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition disabled:opacity-70 disabled:cursor-not-allowed"
                        data-share-submit>
                    Share Post
                </button>
            </div>
        </div>
    </div>
</div>

