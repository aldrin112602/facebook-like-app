<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Friends') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- My Friends --}}
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">My Friends ({{ $friends->count() }})</h3>

                            @if ($friends->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($friends as $friend)
                                        <div class="border rounded-lg p-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    @if ($friend->avatar)
                                                        <img src="{{ $friend->avatar }}" alt="{{ $friend->name }}"
                                                            class="w-10 h-10 rounded-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span
                                                                class="text-gray-600 font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($friend->is_online)
                                                        <div
                                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold">{{ $friend->name }}</h4>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $friend->is_online ? 'Online' : 'Offline' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('messages.show', $friend->id) }}"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                                    Message
                                                </a>
                                                <form action="{{ route('friends.remove', $friend->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                                        onclick="return confirm('Are you sure you want to remove this friend?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No friends yet
                                    </h3>
                                    <p class="text-gray-500">Send friend requests to connect with people!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Friend Requests & Suggestions --}}
                <div>
                    {{-- Pending Requests --}}
                    @if ($pendingRequests->count() > 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-semibold mb-4">Friend Requests
                                    ({{ $pendingRequests->count() }})</h3>

                                <div class="space-y-4">
                                    @foreach ($pendingRequests as $request)
                                        <div class="border rounded-lg p-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                @if ($request->user->avatar)
                                                    <img src="{{ $request->user->avatar }}"
                                                        alt="{{ $request->user->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span
                                                            class="text-gray-600 font-semibold">{{ substr($request->user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold">{{ $request->user->name }}</h4>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $request->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <form action="{{ route('friends.accept', $request->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                                        Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('friends.decline', $request->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600">
                                                        Decline
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Sent Requests --}}
                    @if ($sentRequests->count() > 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-semibold mb-4">Sent Requests ({{ $sentRequests->count() }})
                                </h3>

                                <div class="space-y-4">
                                    @foreach ($sentRequests as $request)
                                        <div class="border rounded-lg p-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                @if ($request->friend->avatar)
                                                    <img src="{{ $request->friend->avatar }}"
                                                        alt="{{ $request->friend->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span
                                                            class="text-gray-600 font-semibold">{{ substr($request->friend->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold">{{ $request->friend->name }}</h4>
                                                    <p class="text-sm text-gray-500">Sent
                                                        {{ $request->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <span class="text-sm text-yellow-600">Pending</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Suggested Friends --}}
                    @if ($suggestedFriends->count() > 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="text-lg font-semibold mb-4">People You May Know</h3>

                                <div class="space-y-4">
                                    @foreach ($suggestedFriends as $user)
                                        <div class="border rounded-lg p-4 flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                @if ($user->avatar)
                                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span
                                                            class="text-gray-600 font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold">{{ $user->name }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <form action="{{ route('friends.request') }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                                <button type="submit"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                                    Add Friend
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- If no pending, sent, and suggested friends, show a message --}}
                    @if ($suggestedFriends->count() === 0 && $pendingRequests->count() === 0 && $sentRequests->count() === 0)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-8 text-center">
                                <!-- Animated Icon Stack -->
                                <div class="mb-6 relative">
                                    <div class="mx-auto w-32 h-32 relative">
                                        <!-- Background Circle with Animation -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-full animate-pulse">
                                        </div>

                                        <!-- Main Icon -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>

                                        <!-- Floating Elements -->
                                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce"
                                            style="animation-delay: 0.5s;">
                                            <svg class="w-3 h-3 text-yellow-800" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>

                                        <div class="absolute -bottom-1 -left-2 w-5 h-5 bg-green-400 rounded-full animate-ping"
                                            style="animation-delay: 1s;"></div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="space-y-4">
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                        No Suggested Friends Available!
                                    </h3>

                                    <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto text-lg">
                                       There are no friends to suggest at the moment nor pending or sent. Please try again later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
