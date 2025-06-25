<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Feed') }}
        </h2>
    </x-slot>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Create Post Form --}}
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/50 dark:border-gray-700/50 p-6 mb-8 shadow-xl">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf
                    <div class="flex items-start space-x-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-lg">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <textarea name="content" placeholder="What's on your mind, {{ auth()->user()->name }}?"
                                class="w-full border-none resize-none bg-transparent text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none text-lg min-h-[60px]"
                                rows="3" required></textarea>

                            {{-- Media Preview --}}
                            <div id="mediaPreview" class="mt-4 hidden">
                                <div class="relative">
                                    <img id="imagePreview" class="hidden max-w-full h-auto rounded-lg" />
                                    <video id="videoPreview" class="hidden max-w-full h-auto rounded-lg"
                                        controls></video>
                                    <button type="button" onclick="clearMedia()"
                                        class="absolute top-2 right-2 bg-gray-800 text-white rounded-full p-1 hover:bg-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Post Actions --}}
                            <div
                                class="flex items-center justify-between pt-4 border-t border-gray-200/50 dark:border-gray-700/50 mt-4 flex-wrap gap-4">
                                <div class="flex space-x-4">
                                    <label
                                        class="flex items-center space-x-2 cursor-pointer text-gray-600 dark:text-gray-400 hover:text-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Photo</span>
                                        <input type="file" name="image" accept="image/*"
                                            onchange="previewMedia(this)" class="hidden">
                                    </label>

                                    <label
                                        class="flex items-center space-x-2 cursor-pointer text-gray-600 dark:text-gray-400 hover:text-green-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Video</span>
                                        <input type="file" name="video" accept="video/*"
                                            onchange="previewMedia(this)" class="hidden">
                                    </label>
                                </div>

                                <div class="flex items-center gap-3">
                                    {{-- Privacy Dropdown --}}
                                    <select name="privacy" id="privacy"
                                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-700 dark:text-gray-300 pr-10">
                                        <option value="public">🌍 Public</option>
                                        <option value="friends">👥 Friends</option>
                                        <option value="private">🔒 Private</option>
                                    </select>

                                    {{-- Submit Button --}}
                                    <button type="submit"
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-full hover:from-blue-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        Post
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            {{-- Posts Feed --}}
            <div class="space-y-6">
                @foreach ($posts as $post)
                    <div
                        class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200/50 dark:border-gray-700/50 shadow-xl overflow-hidden">
                        {{-- Post Header --}}
                        <div class="p-6 pb-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-lg">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $post->created_at->diffForHumans() }}</p>
                                </div>
                                @if ($post->user_id == auth()->id())
                                    <div class="relative">
                                        <button onclick="toggleDropdown({{ $post->id }})"
                                            class="text-gray-400 hover:text-gray-600 p-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                </path>
                                            </svg>
                                        </button>
                                        <div id="dropdown-{{ $post->id }}"
                                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10">
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                                class="block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600"
                                                    onclick="return confirm('Are you sure?')">
                                                    Delete Post
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Post Content --}}
                        <div class="px-6 pb-4">
                            <p class="text-gray-800 dark:text-gray-200 text-lg leading-relaxed">{{ $post->content }}
                            </p>
                        </div>

                        {{-- Post Media --}}
                        @if ($post->media_path)
                            <div class="mb-4">
                                @if (str_contains($post->media_type, 'image'))
                                    <img src="{{ asset('storage/' . $post->media_path) }}" alt="Post image"
                                        class="w-full max-h-96 object-cover">
                                @elseif(str_contains($post->media_type, 'video'))
                                    <video controls class="w-full max-h-96">
                                        <source src="{{ asset('storage/' . $post->media_path) }}"
                                            type="{{ $post->media_type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endif

                        {{-- Post Stats --}}
                        <div class="px-6 py-2 border-t border-gray-200/50 dark:border-gray-700/50">
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $post->likes_count }} likes</span>
                                <span>{{ $post->comments_count }} comments</span>
                            </div>
                        </div>

                        {{-- Post Actions --}}
                        <div class="px-6 py-3 border-t border-gray-200/50 dark:border-gray-700/50">
                            <div class="flex items-center space-x-6">
                                <button onclick="toggleLike({{ $post->id }})"
                                    class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->user()) ? 'text-blue-500 fill-current' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <span>Like</span>
                                </button>

                                <button onclick="toggleComments({{ $post->id }})"
                                    class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-green-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <span>Comment</span>
                                </button>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        <div id="comments-{{ $post->id }}"
                            class="hidden border-t border-gray-200/50 dark:border-gray-700/50">
                            {{-- Add Comment Form --}}
                            <div class="p-4 border-b border-gray-200/50 dark:border-gray-700/50">
                                <form action="{{ route('comments.store', $post->id) }}" method="POST"
                                    class="flex items-start space-x-3">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="content" placeholder="Write a comment..."
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            rows="1" required></textarea>
                                        <button type="submit"
                                            class="mt-2 bg-blue-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                                            Post
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Comments List --}}
                            <div class="max-h-96 overflow-y-auto">
                                @foreach ($post->comments as $comment)
                                    <div class="p-4 border-b border-gray-100 dark:border-gray-700/50 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ substr($comment->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2">
                                                    <h4 class="font-semibold text-sm text-gray-900 dark:text-white">
                                                        {{ $comment->user->name }}</h4>
                                                    <p class="text-gray-800 dark:text-gray-200 text-sm">
                                                        {{ $comment->content }}</p>
                                                </div>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if ($comment->user_id == auth()->id())
                                                        <form action="{{ route('comments.destroy', $comment) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-xs text-red-500 hover:text-red-700"
                                                                onclick="return confirm('Delete this comment?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <script>
        // Media Preview Function
        function previewMedia(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                const mediaPreview = document.getElementById('mediaPreview');
                const imagePreview = document.getElementById('imagePreview');
                const videoPreview = document.getElementById('videoPreview');

                reader.onload = function(e) {
                    mediaPreview.classList.remove('hidden');

                    if (file.type.startsWith('image/')) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        videoPreview.classList.add('hidden');
                    } else if (file.type.startsWith('video/')) {
                        videoPreview.src = e.target.result;
                        videoPreview.classList.remove('hidden');
                        imagePreview.classList.add('hidden');
                    }
                };

                reader.readAsDataURL(file);
            }
        }

        // Clear Media Function
        function clearMedia() {
            document.getElementById('mediaPreview').classList.add('hidden');
            document.getElementById('imagePreview').src = '';
            document.getElementById('videoPreview').src = '';
            document.querySelector('input[type="file"]').value = '';
        }

        // Toggle Dropdown
        function toggleDropdown(postId) {
            const dropdown = document.getElementById(`dropdown-${postId}`);
            dropdown.classList.toggle('hidden');
        }

        // Toggle Like
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Simple reload for now
                    }
                });
        }

        // Toggle Comments
        function toggleComments(postId) {
            const comments = document.getElementById(`comments-${postId}`);
            comments.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>

</x-app-layout>
