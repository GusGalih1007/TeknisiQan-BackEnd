<!-- Sidebar Navigasi Teknisi Qan -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-primary text-white flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:z-auto">
    <!-- Logo Header Sidebar -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-primary-dark/30">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 text-white">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-white/20">
                <img src="{{ asset('important/Logo.png') }}" alt="Logo Teknisi Qan" class="w-full h-full object-contain">

            </div>
            <div>
                <span class="text-lg font-extrabold tracking-wider block leading-none">TEKNISI QAN</span>
                <span class="text-[10px] text-purple-200 tracking-normal font-light">Client Portal</span>
            </div>
        </a>
        <!-- Close Button di Mobile -->
        <button onclick="toggleSidebar()" class="lg:hidden text-white/70 hover:text-white p-1"
            aria-label="Tutup Sidebar">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    <!-- Menu Navigasi Utama -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        @php
            $activeMenu = View::getSection('sidebar-active', 'dashboard');
        @endphp

        <!-- 1. Dashboard -->
        <a href="{{ Route::has('home') ? route('home') : url('/home') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'dashboard' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
            <i
                class="bi bi-grid-1x2-fill text-lg {{ $activeMenu === 'dashboard' ? 'text-primary' : 'text-secondary' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- 2. Buat Laporan -->
        <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'create-report' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
            <i
                class="bi bi-plus-circle-fill text-lg {{ $activeMenu === 'create-report' ? 'text-primary' : 'text-secondary' }}"></i>
            <span>Buat Laporan</span>
        </a>

        <!-- 3. Riwayat Laporan -->
        <a href="#"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'history' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
            <i
                class="bi bi-clock-history text-lg {{ $activeMenu === 'history' ? 'text-primary' : 'text-purple-200' }}"></i>
            <span>Riwayat Laporan</span>
        </a>

        <!-- 4. Profil Saya -->
        <a href="#"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'profile' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
            <i
                class="bi bi-person-badge text-lg {{ $activeMenu === 'profile' ? 'text-primary' : 'text-purple-200' }}"></i>
            <span>Profil Klien</span>
        </a>
    </nav>

    <!-- Sidebar Footer / Logout -->
    <div class="p-4 border-t border-white/10 bg-primary-dark/20">
        <a href="{{ route('logout') }}"
            class="flex items-center space-x-3 text-purple-200 hover:text-white hover:bg-red-500/20 px-4 py-3 rounded-xl transition font-medium">
            <i class="bi bi-box-arrow-left text-lg text-red-400"></i>
            <span>Keluar (Logout)</span>
        </a>
    </div>
</aside>
