<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    @if ($friend->avatar)
                        <img src="{{ $friend->avatar }}" alt="{{ $friend->name }}"
                            class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                        </div>
                    @endif
                    @if ($friend->is_online)
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white">
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $friend->name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        @if ($friend->is_online)
                            Online
                        @else
                            Last seen {{ $friend->last_seen_at ? $friend->last_seen_at->diffForHumans() : 'recently' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-[545px]">
                {{-- Messages Area --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                    @if ($messages->count() > 0)
                        @foreach ($messages as $message)
                            <div
                                class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    @if ($message->sender_id != auth()->id())
                                        <div class="flex items-end space-x-2">
                                            @if ($friend->avatar)
                                                <img src="{{ $friend->avatar }}" alt="{{ $friend->name }}"
                                                    class="w-6 h-6 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-gray-600 text-xs font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg px-4 py-2">
                                                <p class="text-gray-900 dark:text-gray-100">{{ $message->content }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-blue-500 text-white rounded-lg px-4 py-2">
                                            <p>{{ $message->content }}</p>
                                        </div>
                                    @endif
                                    <div
                                        class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} mt-1">
                                        <span class="text-xs text-gray-500">
                                            {{ $message->created_at->diffForHumans() }}
                                            @if ($message->sender_id == auth()->id() && $message->is_read)
                                                <span class="text-blue-500">✓✓</span>
                                            @elseif($message->sender_id == auth()->id())
                                                <span class="text-gray-400">✓</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No messages yet</h3>
                            <p class="text-gray-500">Start the conversation by sending a message below.</p>
                        </div>
                    @endif
                </div>

                {{-- Message Input --}}
                <div class=" dark:border-gray-700 p-4 fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow">
                    <form action="{{ route('messages.send') }}" method="POST" class="flex space-x-4 w-full md:w-1/2 mx-auto">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $friend->id }}">
                        <div class="flex flex-1 w-full justify-between items-center ">
                            <textarea name="content"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 max-h-12"
                                placeholder="Type your message..." required></textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="bg-blue-500 my-auto text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom of messages
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        // Auto-resize textarea
        document.querySelector('textarea[name="content"]').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Handle Enter key to send message (Shift+Enter for new line)
        document.querySelector('textarea[name="content"]').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    </script>
</x-app-layout>