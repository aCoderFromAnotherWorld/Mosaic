<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Your Notifications</h3>
                        @if($notifications->where('is_read', false)->count() > 0)
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-500">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="flex items-start space-x-3 p-4 rounded-lg {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50 border-l-4 border-blue-400' }}">
                                    <div class="flex-shrink-0">
                                        @if($notification->sender)
                                            <img class="h-10 w-10 rounded-full" src="{{ $notification->sender->profile_picture ? asset('storage/' . $notification->sender->profile_picture) : asset('images/default-avatar.png') }}" alt="{{ $notification->sender->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm text-gray-900">
                                                {!! $notification->message !!}
                                            </p>
                                            <div class="flex items-center space-x-2">
                                                @if(!$notification->is_read)
                                                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-500">
                                                            Mark read
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-500">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                        @if($notification->data && isset($notification->data['url']))
                                            <a href="{{ route('notifications.redirect', $notification) }}" class="text-xs text-blue-600 hover:text-blue-500 mt-1 inline-block">
                                                View details ->
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <!-- <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"></path>
                            </svg> -->

                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>

                            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">You're all caught up! Check back later for new notifications.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
