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
                                                {{-- Video Call Button --}}
                                                <button
                                                    onclick="initiateVideoCall({{ $friend->id }}, '{{ $friend->name }}')"
                                                    class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Call</span>
                                                </button>
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
                                        There are no friends to suggest at the moment nor pending or sent. Please try
                                        again later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Call Modal --}}
    <div id="callModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="w-20 h-20 bg-gray-300 rounded-full mx-auto mb-4" id="callerAvatar"></div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="callerName"></h3>
                        <p class="text-gray-500">Incoming video call...</p>
                    </div>
                    <div class="flex space-x-4 justify-center">
                        <button onclick="answerCall(true)"
                            class="bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </button>
                        <button onclick="answerCall(false)"
                            class="bg-red-500 text-white px-6 py-3 rounded-full hover:bg-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 3l1.5 1.5m0 0L16 16l2.5 2.5M4.5 4.5L16 16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    // Enhanced script with debugging for call popup
    <script>
        let currentCallId = null;
        let currentCallerId = null;

        // Debug function to check connection status
        function debugEchoConnection() {
            console.log('=== Echo Debug Info ===');
            console.log('Window Echo exists:', !!window.Echo);
            if (window.Echo) {
                console.log('Echo connector:', window.Echo.connector);
                console.log('Echo options:', window.Echo.options);
            }
            console.log('User ID:', {{ auth()->id() }});
            console.log('======================');
        }

        // Initialize Pusher for receiving calls
        window.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Setting up Echo listeners');
            debugEchoConnection();

            if (window.Echo) {
                try {
                    const channel = window.Echo.private('user.{{ auth()->id() }}');
                    console.log('Private channel created:', channel);

                    // Listen for video call offers
                    channel.listen('.video.call.offer', (e) => {
                        console.log('=== Received video call offer ===');
                        console.log('Event data:', e);
                        console.log('Call ID:', e.call_id);
                        console.log('Caller:', e.caller);
                        console.log('================================');
                        showIncomingCall(e);
                    });

                    // Listen for call end events
                    channel.listen('.video.call.end', (e) => {
                        console.log('Call ended:', e);
                        hideCallModal();
                    });

                    // Test if channel is working
                    channel.listen('.test', (e) => {
                        console.log('Test event received:', e);
                    });

                    console.log('Echo listeners set up successfully');
                } catch (error) {
                    console.error('Error setting up Echo listeners:', error);
                }
            } else {
                console.error('Echo is not available! Make sure Laravel Echo is properly initialized.');
            }
        });

        // Enhanced initiate call function with debugging
        function initiateVideoCall(friendId, friendName) {
            console.log(`Initiating call to ${friendName} (ID: ${friendId})`);

            fetch('{{ route('video.call.initiate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        friend_id: friendId
                    })
                })
                .then(response => {
                    console.log('Call initiate response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Call initiate response data:', data);
                    if (data.success) {
                        console.log('Redirecting to call page:', `/video-call/${data.call_id}`);
                        window.location.href = `/video-call/${data.call_id}`;
                    } else {
                        console.error('Call initiation failed:', data.message || 'Unknown error');
                        alert('Failed to initiate call: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error initiating call:', error);
                    alert('Failed to initiate call: Network error');
                });
        }

        // Enhanced show incoming call with debugging
        function showIncomingCall(callData) {
            console.log('=== Showing incoming call ===');
            console.log('Call data:', callData);

            currentCallId = callData.call_id;
            currentCallerId = callData.caller.id;

            console.log('Set currentCallId:', currentCallId);
            console.log('Set currentCallerId:', currentCallerId);

            // Update caller name
            const callerNameElement = document.getElementById('callerName');
            if (callerNameElement) {
                callerNameElement.textContent = callData.caller.name;
                console.log('Updated caller name:', callData.caller.name);
            } else {
                console.error('callerName element not found');
            }

            // Update caller avatar
            const callerAvatarElement = document.getElementById('callerAvatar');
            if (callerAvatarElement) {
                if (callData.caller.avatar) {
                    callerAvatarElement.innerHTML =
                        `<img src="${callData.caller.avatar}" alt="${callData.caller.name}" class="w-20 h-20 rounded-full object-cover">`;
                } else {
                    callerAvatarElement.innerHTML =
                        `<div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center"><span class="text-gray-600 font-semibold text-2xl">${callData.caller.name.charAt(0)}</span></div>`;
                }
                console.log('Updated caller avatar');
            } else {
                console.error('callerAvatar element not found');
            }

            // Show modal
            const callModal = document.getElementById('callModal');
            if (callModal) {
                callModal.classList.remove('hidden');
                console.log('Call modal shown');
            } else {
                console.error('callModal element not found');
            }

            console.log('=== Incoming call setup complete ===');
        }

        // Enhanced answer call function
        function answerCall(accepted) {
            console.log(`Answering call: ${accepted ? 'ACCEPTED' : 'DECLINED'}`);
            console.log('Current call ID:', currentCallId);
            console.log('Current caller ID:', currentCallerId);

            if (!currentCallId || !currentCallerId) {
                console.error('Missing call ID or caller ID');
                hideCallModal();
                return;
            }

            fetch('{{ route('video.call.answer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        call_id: currentCallId,
                        caller_id: currentCallerId,
                        accepted: accepted
                    })
                })
                .then(response => {
                    console.log('Answer call response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Answer call response data:', data);
                    hideCallModal();

                    if (data.success && accepted && data.redirect) {
                        console.log('Redirecting to call page:', data.redirect);
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => {
                    console.error('Error answering call:', error);
                    hideCallModal();
                    alert('Error answering call');
                });
        }

        // Helper function to hide call modal
        function hideCallModal() {
            const callModal = document.getElementById('callModal');
            if (callModal) {
                callModal.classList.add('hidden');
                console.log('Call modal hidden');
            }

            // Reset state
            currentCallId = null;
            currentCallerId = null;
        }

        // Test function to simulate incoming call (for debugging)
        function testIncomingCall() {
            console.log('Testing incoming call...');
            const testData = {
                call_id: 'test-123',
                caller: {
                    id: 999,
                    name: 'Test User',
                    avatar: null
                }
            };
            showIncomingCall(testData);
        }

        // Add test button (remove in production)
        window.addEventListener('DOMContentLoaded', function() {
            // Add a test button to debug
            const testButton = document.createElement('button');
            testButton.textContent = 'Test Incoming Call';
            testButton.className = 'fixed bottom-4 right-4 bg-purple-500 text-white px-4 py-2 rounded z-50';
            testButton.onclick = testIncomingCall;
            document.body.appendChild(testButton);

            // Debug Echo connection after a short delay
            setTimeout(() => {
                debugEchoConnection();
            }, 2000);
        });

        // Global error handler for debugging
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
        });

        // Check if all required elements exist
        function checkRequiredElements() {
            const requiredElements = ['callModal', 'callerName', 'callerAvatar'];
            const missing = [];

            requiredElements.forEach(id => {
                if (!document.getElementById(id)) {
                    missing.push(id);
                }
            });

            if (missing.length > 0) {
                console.error('Missing required elements:', missing);
                return false;
            }

            console.log('All required elements found');
            return true;
        }

        // Run element check after DOM loads
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkRequiredElements, 1000);
        });
    </script>
</x-app-layout>
