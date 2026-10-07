<!-- Sidebar Navigasi TeknisiQan -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white text-black flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:z-auto border-r-4 border-primary">
    <!-- Logo Header Sidebar -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-gray-200 bg-white">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 text-black">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-white/20">
                <img src="{{ asset('important/Logo.png') }}" alt="Logo TeknisiQan" class="w-full h-full object-contain">

            </div>
            <div>
                <span class="text-lg font-extrabold tracking-wider block leading-none">TEKNISIQAN</span>
                {{-- <span class="text-[10px] text-purple-200 tracking-normal font-light">Client Portal</span> --}}
            </div>
        </a>
        <!-- Close Button di Mobile -->
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-black p-1"
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
        <a href="{{ url('/temp-dashboard') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'dashboard' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-grid-1x2-fill text-lg {{ $activeMenu === 'dashboard' ? 'text-primary' : 'text-black' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- 2. Buat Laporan -->
        {{-- <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'create-report' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
            <i
                class="bi bi-plus-circle-fill text-lg {{ $activeMenu === 'create-report' ? 'text-primary' : 'text-secondary' }}"></i>
            <span>Buat Laporan</span>
        </a> --}}

        <!-- 3. Riwayat Laporan -->
        <a href="{{ Route::has('reports.index') ? route('reports.index') : url('/reports') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'history' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-clock-history text-lg {{ $activeMenu === 'history' ? 'text-primary' : 'text-black' }}"></i>
            <span>Riwayat Laporan</span>
        </a>

        <!-- 4. Daftar User -->
        <a href="{{ Route::has('users.index') ? route('users.index') : url('/users') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'users' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-people text-lg {{ $activeMenu === 'users' ? 'text-primary' : 'text-black' }}"></i>
            <span>Daftar User</span>
        </a>


        <a href="{{ Route::has('companies.index') ? route('companies.index') : url('/companies') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'companies' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-building text-lg {{ $activeMenu === 'companies' ? 'text-primary' : 'text-black' }}"></i>
            <span>Daftar Instansi</span>
        </a>

        <a href="{{ Route::has('rooms.index') ? route('rooms.index') : url('/rooms') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'rooms' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-door-closed text-lg {{ $activeMenu === 'rooms' ? 'text-primary' : 'text-black' }}"></i>
            <span>Daftar Ruangan</span>
        </a>

        <a href="{{ Route::has('units.index') ? route('units.index') : url('/units') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition {{ $activeMenu === 'units' ? 'bg-secondary text-primary font-bold shadow-md shadow-secondary/20' : 'text-gray-700 hover:bg-gray-100 hover:text-black' }}">
            <i
                class="bi bi-collection text-lg {{ $activeMenu === 'units' ? 'text-primary' : 'text-black' }}"></i>
            <span>Daftar Unit</span>
        </a>
    </nav>
</aside>
