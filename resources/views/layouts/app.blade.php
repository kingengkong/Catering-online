<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Catering Online') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-orange-500">
                                🍽️ CateringKu
                            </a>
                        </div>

                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('home') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                                Beranda
                            </a>
                            <a href="{{ route('customer.foods.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('customer.foods.*') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                                Menu
                            </a>
                            @auth
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.cart.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('customer.cart.*') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                                        Keranjang
                                        @php
                                            $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
                                            $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                                        @endphp
                                        @if($cartCount > 0)
                                            <span class="ml-1 bg-orange-500 text-white text-xs rounded-full px-2 py-0.5">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('customer.orders.*') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                                        Pesanan
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.*') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500 hover:text-gray-700' }}">
                                        Dashboard
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="flex items-center">
                        @auth
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-700 hover:text-orange-500">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-orange-500 mr-4">Login</a>
                            <a href="{{ route('register') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-600 transition">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white mt-12">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} CateringKu. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
