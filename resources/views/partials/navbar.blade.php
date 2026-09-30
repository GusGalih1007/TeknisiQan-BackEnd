<!-- Top Navbar Header -->
<nav class="bg-white border-b border-gray-200 px-6 lg:px-8 py-4 flex items-center justify-between shadow-sm">
    <!-- Left: Hamburger Menu Toggle (Mobile Only) -->
    <button onclick="toggleSidebar()" class="lg:hidden text-primary hover:bg-gray-100 p-2 rounded-lg transition"
        aria-label="Toggle Sidebar">
        <i class="bi bi-list text-xl"></i>
    </button>

    <!-- Center: Empty Space for Logo/Title (if needed) -->
    <div class="flex-1 hidden lg:block"></div>

    <!-- Right: Notification & Profile Section -->
    <div class="flex items-center space-x-6">
        <!-- Notification Bell -->
        <div class="relative group">
            <button class="relative text-gray-600 hover:text-primary transition p-2 rounded-lg hover:bg-gray-100">
                <i class="bi bi-bell text-xl"></i>
                <!-- Notification Badge -->
                <span
                    class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
            </button>

            <!-- Notification Dropdown -->
            <div
                class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800">Notifikasi</h3>
                </div>
                <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                    <!-- Notification Item 1 -->
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                        <p class="font-medium text-sm text-gray-800">Laporan baru telah diproses</p>
                        <p class="text-xs text-gray-500 mt-1">5 menit yang lalu</p>
                    </a>

                    <!-- Notification Item 2 -->
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                        <p class="font-medium text-sm text-gray-800">Respon dari teknisi</p>
                        <p class="text-xs text-gray-500 mt-1">1 jam yang lalu</p>
                    </a>

                    <!-- Notification Item 3 -->
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition">
                        <p class="font-medium text-sm text-gray-800">Status laporan diperbarui</p>
                        <p class="text-xs text-gray-500 mt-1">3 jam yang lalu</p>
                    </a>
                </div>
                <div class="p-3 border-t border-gray-200 text-center">
                    <a href="#" class="text-sm text-primary font-medium hover:text-primary-dark transition">Lihat
                        Semua</a>
                </div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="relative group flex items-center space-x-3 cursor-pointer">
            <!-- Profile Image -->
            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center text-white font-bold hover:shadow-lg transition overflow-hidden">
                @if (Auth::check() == true)
                    @if (Auth::user()->photo && file_exists(public_path('storage/' . Auth::user()->photo)))
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                            class="w-full h-full object-cover rounded-full">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=5003C0&color=fff&bold=true" 
                            alt="{{ Auth::user()->name }}" class="w-full h-full object-cover rounded-full">
                    @endif
                @else
                    <img src="https://ui-avatars.com/api/?name=User&background=5003C0&color=fff&bold=true" 
                        alt="User" class="w-full h-full object-cover rounded-full">
                @endif
            </a>

            <!-- Profile Dropdown -->
            <div
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 top-full">
                <div class="p-4 border-b border-gray-200">
                    <p class="font-bold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                </div>

                <div class="py-2 divide-y divide-gray-100">
                    <!-- Profile Link -->
                    <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                        <i class="bi bi-person text-lg text-primary"></i>
                        <span>Profil Saya</span>
                    </a>

                    <!-- Settings Link (Optional) -->
                    <a href="#"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                        <i class="bi bi-gear text-lg text-gray-500"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>

                <div class="p-3 border-t border-gray-200">
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50/50 rounded-lg transition font-medium">
                            <i class="bi bi-box-arrow-left text-lg"></i>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
