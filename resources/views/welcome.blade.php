<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknisi Qan - Solusi Cepat Pelaporan Kerusakan Barang</title>

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
</head>
<body class="bg-white text-bodytext font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar Partial -->
    @include('partials.landing-navbar')

    <!-- Hero Section (Tengah) -->
    <main class="flex-grow">
        <section class="relative overflow-hidden bg-gradient-to-b from-primary/[0.04] via-transparent to-surface py-16 md:py-24">
            <!-- Background Ornamen -->
            <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-tertiary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-secondary/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Sisi Kiri Hero: Teks & CTA -->
                    <div class="lg:col-span-7 text-center lg:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary/25 border border-secondary/50 text-purple-950 text-xs font-bold uppercase tracking-wider mb-6">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Pelaporan Cepat &bull; Penanganan Tepat
                        </div>

                        <!-- Judul Besar (Warna #5003C0) -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-primary leading-tight mb-6">
                            Solusi Cepat Pelaporan <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-tertiary">Kerusakan Barang</span> Anda
                        </h1>

                        <!-- Sub-judul -->
                        <p class="text-base sm:text-lg text-gray-600 leading-relaxed mb-8 max-w-2xl mx-auto lg:mx-0">
                            Laporkan kerusakan barang Anda secara real-time dan pantau proses perbaikannya dengan mudah melalui sistem kami. Cepat, transparan, dan terstruktur.
                        </p>

                        <!-- Tombol CTA -->
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            <!-- Tombol CTA: Masuk ke Dashboard (Warna #FFD51E, teks #5003C0, agak besar dan bold) -->
                            <a href="{{ route('login') }}" 
                               class="w-full sm:w-auto bg-secondary text-primary text-base sm:text-lg font-extrabold px-8 py-4 rounded-xl shadow-lg shadow-secondary/30 hover:shadow-xl hover:bg-secondary-dark hover:-translate-y-0.5 transition duration-300 flex items-center justify-center gap-3">
                                <span>Masuk ke Dashboard</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

                            <!-- Tombol Lapor Publik (Bagi yang tidak login) -->
                            <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}" 
                               class="w-full sm:w-auto bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold px-8 py-4 rounded-xl transition duration-300 flex items-center justify-center gap-2 text-base">
                                <i class="bi bi-plus-circle"></i>
                                <span>Laporkan Sekarang (Tanpa Login)</span>
                            </a>
                        </div>

                        <!-- Micro stats info -->
                        <div class="mt-10 pt-8 border-t border-gray-200/60 flex items-center justify-center lg:justify-start gap-8 text-xs text-gray-500 font-medium">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-shield-check text-emerald-600 text-base"></i>
                                <span>Klien Terverifikasi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-clock-history text-tertiary text-base"></i>
                                <span>Respon Cepat 24/7</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan Hero: Ilustrasi Gear & Teknisi Estetik -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative w-80 h-80 sm:w-96 sm:h-96">
                            <!-- Background Circles -->
                            <div class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-primary to-tertiary rotate-6 opacity-20 filter blur-xl"></div>
                            
                            <div class="relative w-full h-full rounded-3xl bg-gradient-to-br from-primary via-primary-dark to-tertiary shadow-2xl p-8 flex flex-col justify-between text-white overflow-hidden border border-white/20">
                                
                                <!-- Floating Gear SVG (Gerak/Estetik) -->
                                <div class="absolute -right-12 -top-12 opacity-15 pointer-events-none">
                                    <svg class="w-64 h-64 animate-[spin_40s_linear_infinite]" viewBox="0 0 24 24" fill="none" stroke="#FFD51E" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>

                                <!-- Card Top -->
                                <div class="flex items-center justify-between z-10">
                                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                                        <i class="bi bi-gear-fill text-secondary text-2xl animate-spin" style="animation-duration: 10s;"></i>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-secondary text-primary font-bold text-xs tracking-wide shadow-sm">
                                        ACTIVE DISPATCH
                                    </span>
                                </div>

                                <!-- Card Center Content -->
                                <div class="z-10 my-auto py-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
                                        <p class="text-xs uppercase tracking-wider text-purple-200 font-semibold">Status Monitoring</p>
                                    </div>
                                    <h3 class="text-2xl font-extrabold text-white mb-1">Tiket #LPR-2026</h3>
                                    <p class="text-sm text-purple-200">Teknisi ditugaskan &bull; Siaga di Lapangan</p>
                                </div>

                                <!-- Card Bottom Stats -->
                                <div class="z-10 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-purple-200 uppercase font-bold block">Waktu Tanggap</span>
                                        <span class="text-lg font-extrabold text-secondary">&lt; 15 Menit</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-purple-200 uppercase font-bold block">Tingkat Kepuasan</span>
                                        <span class="text-lg font-extrabold text-white">99.8%</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Section "Cara Kerja" (3 Kolom) -->
        <section class="py-20 bg-surface border-y border-gray-200/60">
            <div class="max-w-7xl mx-auto px-6">
                
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-bold text-tertiary uppercase tracking-widest block mb-2">Langkah Mudah</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-primary">Cara Kerja Pelaporan</h2>
                    <p class="text-sm text-gray-500 mt-3">Alur pelaporan yang simpel, cepat, dan transparan dari awal hingga barang selesai diperbaiki.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    
                    <!-- Card 1: Identifikasi Kerusakan -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 relative group flex flex-col justify-between">
                        <span class="absolute top-6 right-6 text-4xl font-extrabold text-gray-100 group-hover:text-primary/10 transition">01</span>
                        <div>
                            <!-- Ikon Warna #AB03A9 -->
                            <div class="w-16 h-16 rounded-2xl bg-tertiary/10 flex items-center justify-center mb-6 group-hover:bg-tertiary group-hover:text-white transition duration-300">
                                <i class="bi bi-exclamation-diamond text-3xl text-tertiary group-hover:text-white transition"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Identifikasi Kerusakan</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Cek dan catat detail barang atau fasilitas yang mengalami masalah di lokasi kerja Anda.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 text-xs font-semibold text-tertiary flex items-center gap-1">
                            <span>Langkah Awal</span>
                            <i class="bi bi-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Card 2: Buat Laporan via App -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 relative group flex flex-col justify-between">
                        <span class="absolute top-6 right-6 text-4xl font-extrabold text-gray-100 group-hover:text-primary/10 transition">02</span>
                        <div>
                            <!-- Ikon Warna #AB03A9 -->
                            <div class="w-16 h-16 rounded-2xl bg-tertiary/10 flex items-center justify-center mb-6 group-hover:bg-tertiary group-hover:text-white transition duration-300">
                                <i class="bi bi-file-earmark-text text-3xl text-tertiary group-hover:text-white transition"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Buat Laporan via App</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Isi data kerusakan melalui form pelaporan online atau dashboard klien terdaftar.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 text-xs font-semibold text-tertiary flex items-center gap-1">
                            <span>Kirim Laporan</span>
                            <i class="bi bi-chevron-right text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Card 3: Teknisi Menuju Lokasi -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 relative group flex flex-col justify-between">
                        <span class="absolute top-6 right-6 text-4xl font-extrabold text-gray-100 group-hover:text-primary/10 transition">03</span>
                        <div>
                            <!-- Ikon Warna #AB03A9 -->
                            <div class="w-16 h-16 rounded-2xl bg-tertiary/10 flex items-center justify-center mb-6 group-hover:bg-tertiary group-hover:text-white transition duration-300">
                                <i class="bi bi-tools text-3xl text-tertiary group-hover:text-white transition"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Teknisi Menuju Lokasi</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Tim teknisi segera menerima tiket dan meluncur ke lokasi untuk penanganan tuntas.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 text-xs font-semibold text-tertiary flex items-center gap-1">
                            <span>Perbaikan Selesai</span>
                            <i class="bi bi-check-circle text-xs"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Quick Call to Action Banner -->
        <section class="py-16 bg-white">
            <div class="max-w-5xl mx-auto px-6">
                <div class="rounded-3xl bg-primary p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-tertiary rounded-full blur-2xl opacity-40"></div>
                    <div class="absolute -left-16 -top-16 w-64 h-64 bg-secondary rounded-full blur-2xl opacity-30"></div>
                    
                    <div class="relative z-10 max-w-2xl mx-auto">
                        <span class="text-xs font-bold text-secondary uppercase tracking-widest block mb-2">Layanan Publik</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold mb-4">Ada Kerusakan Barang yang Perlu Segera Ditangani?</h2>
                        <p class="text-purple-200 text-sm sm:text-base mb-8">
                            Anda tidak perlu akun untuk mengirim laporan darurat. Cukup isi formulir pelaporan kami dan tim kami akan segera memverifikasinya.
                        </p>
                        <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}" 
                           class="inline-flex items-center gap-2 bg-secondary text-primary font-extrabold px-8 py-3.5 rounded-xl shadow-lg hover:bg-secondary-dark transition">
                            <i class="bi bi-pencil-square text-lg"></i>
                            <span>Buka Formulir Pelaporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Partial -->
    @include('partials.landing-footer')

</body>
</html>