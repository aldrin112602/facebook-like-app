<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            /* Custom gradient background */
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
            }
            
            .gradient-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: 
                    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
                animation: float 6s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            /* Glassmorphism effect */
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            
            .dark .glass-card {
                background: rgba(17, 24, 39, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            /* Animated navbar */
            .navbar-blur {
                backdrop-filter: blur(20px);
                background: rgba(17, 24, 39, 0.9);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            /* Floating elements */
            .floating-element {
                position: absolute;
                pointer-events: none;
                opacity: 0.6;
            }
            
            .floating-1 {
                top: 10%;
                left: 10%;
                animation: float 8s ease-in-out infinite;
            }
            
            .floating-2 {
                top: 60%;
                right: 15%;
                animation: float 10s ease-in-out infinite reverse;
            }
            
            .floating-3 {
                bottom: 20%;
                left: 20%;
                animation: float 7s ease-in-out infinite;
                animation-delay: -2s;
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 text-[#1b1b18] dark:text-[#f4f4f4] min-h-screen">
    <!-- Header -->
    <header class="w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            SocialConnect
                        </h1>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/feed') }}" 
                               class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-full hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Feed
                            </a>
                        @else
                            <a href="{{ route('homepage') }}" 
                                   class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-full hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    Back to Home Page
                                </a>
                            
                        @endauth
                    </div>
                </nav>
            @endif
        </div>
    </header>
        <!-- Floating Background Elements -->
        <div class="floating-element floating-1">
            <div class="w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
        </div>
        <div class="floating-element floating-2">
            <div class="w-24 h-24 bg-purple-400/20 rounded-full blur-lg"></div>
        </div>
        <div class="floating-element floating-3">
            <div class="w-40 h-40 bg-blue-400/10 rounded-full blur-2xl"></div>
        </div>

        

        <!-- Main Content -->
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 relative z-10">
            <!-- Enhanced Card Container -->
            <div class="w-full max-w-md relative">
                <!-- Decorative elements -->
                <div class="absolute -top-4 -left-4 w-72 h-72 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
                <div class="absolute -bottom-4 -right-4 w-72 h-72 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
                
                <!-- Main Card -->
                <div class="glass-card rounded-2xl shadow-2xl overflow-hidden relative z-10 border border-white/20">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 p-6 text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">
                             @isset($header)
                             {{ $header }}
                             @endisset
                        </h2>
                        <p class="text-blue-100 text-sm">Connect with friends and build your network</p>
                    </div>

                    <!-- Card Body -->
                    <div class="p-8">
                        {{ $slot }}
                    </div>

                    <!-- Card Footer -->
                    <div class="px-8 pb-6">
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Secure
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                </svg>
                                Fast
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Reliable
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <footer class="relative z-10 mt-auto">
            <div class="navbar-blur border-t border-white/10">
                <div class="max-w-7xl mx-auto px-4 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="flex items-center space-x-4 mb-4 md:mb-0">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">SocialConnect</p>
                                <p class="text-white/60 text-xs">Building connections, one friend at a time</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            
                            <div class="text-white/60 text-sm">
                                © {{ date('Y') }} SocialConnect. Made with ❤️
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>