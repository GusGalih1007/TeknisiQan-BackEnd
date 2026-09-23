<!-- Navbar Landing Page Teknisi Qan -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        <!-- Kiri: Logo TEKNISI QAN -->
        <a href="{{ url('/') }}" class="flex items-center space-x-3 text-primary group">
            <div class="w-11 h-11 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:rotate-45 transition duration-500">
                <i class="bi bi-gear-wide-connected text-primary text-2xl"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold tracking-wider block leading-none">TEKNISI QAN</span>
                <span class="text-[10px] text-gray-400 font-semibold tracking-widest uppercase">Damage Reporting System</span>
            </div>
        </a>

        <!-- Nav Links & CTA -->
        <div class="flex items-center space-x-4 sm:space-x-6">
            <!-- Link Laporkan Publik -->
            <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}" 
               class="hidden md:inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-tertiary transition">
                <i class="bi bi-megaphone text-tertiary"></i>
                <span>Laporkan Kerusakan</span>
            </a>

            @auth
                <a href="{{ Route::has('home') ? route('home') : url('/home') }}" 
                   class="bg-secondary text-primary font-bold px-6 py-2.5 rounded-xl hover:bg-secondary-dark transition duration-300 shadow-md shadow-secondary/30 flex items-center gap-2 text-sm">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard Klien</span>
                </a>
            @else
                <!-- Kanan: Tombol Login Klien (#5003C0, teks putih) -->
                <a href="{{ route('login') }}" 
                   class="bg-primary text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-primary-light transition duration-300 shadow-md shadow-primary/20 flex items-center gap-2 text-sm">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login Klien</span>
                </a>
            @endauth
        </div>
    </div>
</header>

