<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teknisi Qan')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN Fallback + Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#5003C0',
                        'primary-light': '#6B2BD6',
                        'primary-dark': '#3D0299',
                        secondary: '#FFD51E',
                        'secondary-dark': '#E6BF00',
                        tertiary: '#AB03A9',
                        'tertiary-light': '#C94DC8',
                        surface: '#F5F5F5',
                        bodytext: '#333333',
                    }
                }
            }
        }
    </script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-surface text-bodytext min-h-screen flex flex-col antialiased">

    <!-- Navbar Sederhana -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-primary">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('important/Logo.png') }}" alt="Logo Teknisi Qan"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-wider block leading-none">TEKNISIQAN</span>
                    <span class="text-[8px] sm:text-[10px] text-gray-400 font-semibold tracking-widest uppercase">Lacak Status</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ url('/lapor') }}"
                    class="text-xs sm:text-sm font-semibold text-gray-500 hover:text-primary transition flex items-center gap-1.5">
                    <span class="">Buat Laporan</span>
                </a>
                @auth
                    <a href="{{ route('temp.dashboard') }}"
                        class="bg-primary text-white font-bold px-3 sm:px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                        <span class="">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-primary text-white font-bold px-3 sm:px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                        <span>Login Admin</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-6 sm:py-8 border-t border-white/10 mt-8 sm:mt-12">
        <div
            class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-purple-200">
            <div class="flex items-center space-x-2 flex-wrap justify-center">
                <i class="bi bi-gear-wide-connected text-secondary"></i>
                <span class="font-bold text-white">TEKNISIQAN</span>
                <span>&bull; Layanan Pelaporan Kerusakan Barang</span>
            </div>
            <div>
                &copy; {{ date('Y') }} TeknisiQan. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
