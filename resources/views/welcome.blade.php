<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Mosaic') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="w-full py-6 px-4 sm:px-6 lg:px-8">
                <nav class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center">
                        <x-mosaic-logo class="h-10 w-auto" />
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/feed') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Go to Feed
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>
            </header>

            <!-- Main Content -->
            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="mb-8">
                        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                            Welcome to <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Mosaic</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                            Share your moments, discover amazing content, and connect with people who share your interests. 
                            Your social media experience, beautifully crafted.
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid md:grid-cols-3 gap-8 mb-12">
                        <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Share Moments</h3>
                            <p class="text-gray-600">Upload photos and videos to share your life with friends and followers.</p>
                        </div>

                        <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Connect</h3>
                            <p class="text-gray-600">Follow friends, discover new people, and build meaningful connections.</p>
                        </div>

                        <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg">
                            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Engage</h3>
                            <p class="text-gray-600">Like, comment, and share content that inspires and entertains you.</p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-105">
                                Join Mosaic Today
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="border-2 border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-600 hover:text-white transition-all">
                                Sign In
                            </a>
                        @endif
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-8 px-4 sm:px-6 lg:px-8 border-t border-gray-200">
                <div class="max-w-7xl mx-auto text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} Mosaic. Made with ❤️ for sharing beautiful moments.</p>
                </div>
            </footer>
        </div>
    </body>
</html>