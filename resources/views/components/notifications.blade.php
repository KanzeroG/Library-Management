@props(['notifications'])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($notifications->where('is_read', false)->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $notifications->where('is_read', false)->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-bg-secondary rounded-lg shadow-lg overflow-hidden z-50">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notifications</h3>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->link }}" 
                   class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-dark-bg-secondary' }}"
                   onclick="markAsRead({{ $notification->id }})">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @switch($notification->type)
                                @case('due_date')
                                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('overdue')
                                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    @break
                                @case('system')
                                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @default
                                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                            @endswitch
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $notification->message }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    No notifications
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });
}
</script>
@endpush 
 