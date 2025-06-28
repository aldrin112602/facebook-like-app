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
                                                @if ($message->isImage())
                                                    <div class="mb-2">
                                                        <img src="{{ $message->file_url }}" alt="Image"
                                                            class="max-w-full h-auto rounded-lg cursor-pointer"
                                                            onclick="openImageModal('{{ $message->file_url }}')">
                                                    </div>
                                                    @if ($message->content && $message->content !== $message->file_name)
                                                        <p class="text-gray-900 dark:text-gray-100 text-sm">
                                                            {{ $message->content }}</p>
                                                    @endif
                                                @elseif ($message->isVideo())
                                                    <div class="mb-2">
                                                        <video controls class="max-w-full h-auto rounded-lg">
                                                            <source src="{{ $message->file_url }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                    @if ($message->content && $message->content !== $message->file_name)
                                                        <p class="text-gray-900 dark:text-gray-100 text-sm">
                                                            {{ $message->content }}</p>
                                                    @endif
                                                @elseif ($message->isFile())
                                                    <div
                                                        class="flex items-center space-x-3 p-3 bg-gray-100 dark:bg-gray-600 rounded-lg">
                                                        <svg class="w-8 h-8 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        <div class="flex-1 min-w-0">
                                                            <a href="{{ route('messages.download', $message->id) }}"
                                                                class="text-blue-600 hover:text-blue-800 font-medium text-sm truncate block">
                                                                {{ $message->file_name }}
                                                            </a>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $message->formatted_file_size }}</p>
                                                        </div>
                                                        <a href="{{ route('messages.download', $message->id) }}"
                                                            class="text-blue-600 hover:text-blue-800">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    @if ($message->content && $message->content !== $message->file_name)
                                                        <p class="text-gray-900 dark:text-gray-100 mt-2 text-sm">
                                                            {{ $message->content }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-gray-900 dark:text-gray-100">{{ $message->content }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-blue-500 text-white rounded-lg px-4 py-2">
                                            @if ($message->isImage())
                                                <div class="mb-2">
                                                    <img src="{{ $message->file_url }}" alt="Image"
                                                        class="max-w-full h-auto rounded-lg cursor-pointer"
                                                        onclick="openImageModal('{{ $message->file_url }}')">
                                                </div>
                                                @if ($message->content && $message->content !== $message->file_name)
                                                    <p class="text-sm">{{ $message->content }}</p>
                                                @endif
                                            @elseif ($message->isVideo())
                                                <div class="mb-2">
                                                    <video controls class="max-w-full h-auto rounded-lg">
                                                        <source src="{{ $message->file_url }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                                @if ($message->content && $message->content !== $message->file_name)
                                                    <p class="text-sm">{{ $message->content }}</p>
                                                @endif
                                            @elseif ($message->isFile())
                                                <div class="flex items-center space-x-3 p-3 bg-blue-600 rounded-lg">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <div class="flex-1 min-w-0">
                                                        <a href="{{ route('messages.download', $message->id) }}"
                                                            class="text-blue-100 hover:text-white font-medium text-sm truncate block">
                                                            {{ $message->file_name }}
                                                        </a>
                                                        <p class="text-xs text-blue-200">
                                                            {{ $message->formatted_file_size }}</p>
                                                    </div>
                                                    <a href="{{ route('messages.download', $message->id) }}"
                                                        class="text-blue-100 hover:text-white">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                @if ($message->content && $message->content !== $message->file_name)
                                                    <p class="mt-2 text-sm">{{ $message->content }}</p>
                                                @endif
                                            @else
                                                <p>{{ $message->content }}</p>
                                            @endif
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
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
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
                <div
                    class="border-t dark:border-gray-700 p-4 fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow">
                    <form action="{{ route('messages.send') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col space-y-4 w-full md:w-1/2 mx-auto">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $friend->id }}">

                        {{-- File Preview --}}
                        <div id="file-preview" class="hidden bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div id="preview-content"></div>
                                    <div>
                                        <p id="file-name"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                                        <p id="file-size" class="text-xs text-gray-500"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearFileSelection()"
                                    class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex flex-1 items-end space-x-2">
                                <textarea name="content"
                                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 max-h-12"
                                    placeholder="Type your message..."></textarea>

                                {{-- File Upload Button --}}
                                <label for="file-input"
                                    class="cursor-pointer p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                </label>
                                <input type="file" id="file-input" name="file" class="hidden"
                                    onchange="handleFileSelection(this)">
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Send
                                </button>
                            </div>
                        </div>

                        @error('content')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        @error('file')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center"
        onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full p-4">
            <img id="modal-image" src="" alt="Full size image" class="max-w-full max-h-full object-contain">
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

        // File selection handling
        function handleFileSelection(input) {
            const file = input.files[0];
            if (!file) return;

            const preview = document.getElementById('file-preview');
            const previewContent = document.getElementById('preview-content');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Show preview based on file type
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-12 h-12 object-cover rounded';
                previewContent.innerHTML = '';
                previewContent.appendChild(img);
            }

            preview.classList.remove('hidden');
        }

        function clearFileSelection() {
            document.getElementById('file-input').value = '';
            document.getElementById('file-preview').classList.add('hidden');
        }

        function formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unitIndex = 0;

            while (size > 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex++;
            }

            return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
        }

        // Image modal functions
        function openImageModal(imageSrc) {
            document.getElementById('modal-image').src = imageSrc;
            document.getElementById('image-modal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>
