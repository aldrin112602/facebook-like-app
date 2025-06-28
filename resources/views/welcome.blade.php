<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SocialConnect App | Built with Laravel & Tailwind CSS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body
    class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 text-[#1b1b18] dark:text-[#f4f4f4] min-h-screen">
    <!-- Header -->
    <header
        class="w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between h-16">

                    <h1
                        class="text-sm font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent block md:hidden">
                        SocialConnect
                    </h1>

                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center hidden md:block">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hidden md:block">
                            SocialConnect
                        </h1>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/feed') }}"
                                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-full hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                </svg>
                                Feed
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-2 text-sm md:px-5 py-1 md:py-2 text-gray-700 dark:text-gray-300 font-medium rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-2 md:px-6 py-1 text-sm md:py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-full hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h1
                        class="text-5xl lg:text-7xl font-extrabold mb-6 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent leading-tight">
                        Connect. Share. Engage.
                    </h1>
                    <p
                        class="text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                        Join millions of people sharing their stories, connecting with friends, and discovering amazing
                        content every day.
                    </p>

                    @guest
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-lg font-semibold rounded-full hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Create Account
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-8 py-4 bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 text-lg font-semibold rounded-full hover:bg-white dark:hover:bg-gray-800 backdrop-blur-sm border border-gray-200 dark:border-gray-700 transition-all duration-200">
                                Sign In
                            </a>
                        </div>
                    @endguest
                </div>

                <!-- Features Preview -->
                <div class="grid lg:grid-cols-3 gap-8 mb-16">
                    <!-- Feature 1: Share Posts -->
                    <div
                        class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:transform hover:scale-105 transition-all duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Share Your Moments</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Post photos, videos, and thoughts. Share your life's best moments with friends and family in
                            real-time.
                        </p>
                    </div>

                    <!-- Feature 2: Connect -->
                    <div
                        class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:transform hover:scale-105 transition-all duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Connect with Friends</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Find and connect with friends, family, and like-minded people. Build meaningful
                            relationships online.
                        </p>
                    </div>

                    <!-- Feature 3: Engage -->
                    <div
                        class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-8 border border-gray-200/50 dark:border-gray-700/50 hover:transform hover:scale-105 transition-all duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Like & Comment</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Engage with content through likes, comments, and shares. Be part of conversations that
                            matter to you.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer
        class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm border-t border-gray-200/50 dark:border-gray-700/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-600 dark:text-gray-400">
                © {{ date('Y') }} SocialConnect. Built with Laravel & Tailwind CSS.
            </p>
        </div>
    </footer>
</body>

</html>
