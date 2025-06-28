<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Conversations List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="conversations-list space-y-4">
                        @forelse ($conversations as $conversation)
                            <div class="conversation-item flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-700 rounded-md shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-300">
                                <a href="{{ route('messages.show', ['id' => $conversation['friend']->id]) }}" class="flex items-center justify-between w-full">
                                    @if ($conversation['friend']->avatar)
                                        <img src="{{ $conversation['friend']->avatar }}" alt="{{ $conversation['friend']->name }}" class="w-14 h-14 rounded-full object-cover">
                                    @else
                                        <div class="w-14 h-14 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                            <span class="text-gray-600 dark:text-gray-300 font-semibold">{{ substr($conversation['friend']->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <!-- Friend Name and Latest Message -->
                                    <div class="flex-1 mx-3">
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $conversation['friend']->name }}</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400" title="{{ $conversation['latest_message']->content }}">
                                            {{ $conversation['latest_message']->sender_id == auth()->id() ? 'You: ' : '' }}
                                            {{ Str::limit($conversation['latest_message']->content, 50) }}
                                            @if ($conversation['latest_message']->sender_id == auth()->id() && $conversation['latest_message']->is_read)
                                                <span class="text-blue-500 dark:text-blue-400">✓✓</span>
                                            @elseif($conversation['latest_message']->sender_id == auth()->id())
                                                <span class="text-gray-400 dark:text-gray-500">✓</span>
                                            @endif
                                        </p>
                                    </div>
                                    <p class="text-xs mx-1 text-gray-500 dark:text-gray-400">
                                        {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                    </p>
                                </a>
                                <!-- Unread Count -->
                                @if ($conversation['unread_count'] > 0)
                                    <div class="unread-count bg-red-500 dark:bg-red-600 text-white rounded-full px-3 py-1 text-xs">
                                        {{ $conversation['unread_count'] }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="no-messages text-center py-6 text-lg text-gray-500 dark:text-gray-400">
                                <p>No conversations found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>