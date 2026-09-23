<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Teknisi Qan')</title>

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
<body class="bg-surface text-bodytext font-sans antialiased h-screen flex overflow-hidden">

    <!-- Backdrop Overlay untuk Mobile Sidebar -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-opacity duration-300"></div>

    <!-- Partial: Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content Area Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Partial: Top Navbar -->
        @include('partials.navbar')

        <!-- Main Scrollable Body Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-surface flex flex-col justify-between">
            <div class="flex-grow">
                @yield('content')
            </div>

            <!-- Partial: Footer -->
            @include('partials.footer')
        </main>
    </div>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
