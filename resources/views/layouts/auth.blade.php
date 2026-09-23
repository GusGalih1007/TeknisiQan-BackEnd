<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login - Teknisi Qan')</title>

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
<body class="bg-gray-50 text-bodytext min-h-screen flex antialiased">

    <!-- Sisi Kiri: Branding (Hidden on mobile, split 50% on lg) -->
    <div class="hidden lg:flex lg:w-1/2 bg-primary relative flex-col justify-between p-12 overflow-hidden text-white select-none">
        
        <!-- Ornamen Latar Belakang Gear & Blur -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-tertiary rounded-full blur-3xl opacity-30 pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary rounded-full blur-3xl opacity-20 pointer-events-none"></div>
        
        <!-- Subtle Decorative Gear SVG Pattern -->
        <div class="absolute inset-0 opacity-5 pointer-events-none flex items-center justify-center">
            <svg class="w-[600px] h-[600px] animate-[spin_60s_linear_infinite]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <!-- Top Header: Logo kecil & Kembali ke Web -->
        <div class="z-10 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-white hover:text-secondary transition">
                <div class="w-10 h-10 rounded-xl bg-white backdrop-blur-md flex items-center justify-center border border-white/20">
                    <img src="{{ asset('important/Logo.png') }}" alt="Logo Teknisi Qan" class="w-full h-full object-contain">
                </div>
                <span class="text-xl font-extrabold tracking-wider">TEKNISI QAN</span>
            </a>
            <a href="{{ url('/') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-purple-100 transition flex items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Beranda
            </a>
        </div>

        <!-- Center: Branding Hero -->
        <div class="z-10 my-auto py-10 max-w-lg">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-secondary/20 text-secondary text-xs font-bold uppercase tracking-wider mb-6 border border-secondary/30">
                <i class="bi bi-shield-check"></i> Portal Klien Resmi
            </div>
            
            <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-4">
                Pelaporan Cepat, <br>
                <span class="text-secondary">Perbaikan Tepat.</span>
            </h1>
            
            <p class="text-purple-200 text-base leading-relaxed mb-8">
                Pantau progres penanganan kerusakan peralatan Anda secara transparan dan terukur bersama tim teknisi profesional.
            </p>

            <!-- Fitur Mini -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-tertiary/40 flex items-center justify-center text-white text-sm">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <span class="text-xs text-purple-100 font-medium">Real-time Tracking</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-secondary/30 flex items-center justify-center text-secondary text-sm">
                        <i class="bi bi-tools"></i>
                    </div>
                    <span class="text-xs text-purple-100 font-medium">Teknisi Tersertifikasi</span>
                </div>
            </div>
        </div>

        <!-- Bottom: Footer Brand -->
        <div class="z-10 text-xs text-purple-300">
            &copy; {{ date('Y') }} Teknisi Qan. Hak cipta dilindungi undang-undang.
        </div>
    </div>

    <!-- Sisi Kanan: Konten Form (Full width on mobile, 50% on lg) -->
    <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-12 lg:p-16 bg-white overflow-y-auto">
        <!-- Mobile Top Navbar -->
        <div class="lg:hidden flex items-center justify-between pb-6 mb-6 border-b border-gray-100">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 text-primary font-bold text-lg">
                <i class="bi bi-gear-wide-connected text-primary text-xl"></i>
                <span>TEKNISI QAN</span>
            </a>
            <a href="{{ url('/') }}" class="text-xs text-gray-500 hover:text-primary font-medium flex items-center gap-1">
                <i class="bi bi-house"></i> Beranda
            </a>
        </div>

        <!-- Wrapper Konten Form yield content) -->
        <div class="w-full max-w-md mx-auto my-auto py-6">
            @yield('content')
        </div>

        <!-- Footer kecil untuk mobile / copyright -->
        <div class="text-center pt-8 text-xs text-gray-400">
            Aplikasi Pelaporan Kerusakan Barang &bull; Teknisi Qan
        </div>
    </div>

    @stack('scripts')
</body>
</html>

