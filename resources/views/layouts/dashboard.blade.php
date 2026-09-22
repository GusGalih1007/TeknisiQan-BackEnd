<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klien - Teknisi Qan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#5003C0] text-white flex flex-col shadow-xl z-20">
        <!-- Logo Sidebar -->
        <div class="h-20 flex items-center justify-center border-b border-[#AB03A9]">
            <span class="text-2xl font-extrabold text-[#FFD51E] tracking-wider">TEKNISI QAN</span>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Menu Aktif -->
            <a href="#" class="flex items-center space-x-3 bg-[#FFD51E] text-[#5003C0] px-4 py-3 rounded-lg font-bold shadow-md transition">
                <span>🏠</span>
                <span>Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-purple-100 hover:bg-[#AB03A9] hover:text-white px-4 py-3 rounded-lg font-medium transition">
                <span>📝</span>
                <span>Buat Laporan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-purple-100 hover:bg-[#AB03A9] hover:text-white px-4 py-3 rounded-lg font-medium transition">
                <span>📋</span>
                <span>Riwayat Laporan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 text-purple-100 hover:bg-[#AB03A9] hover:text-white px-4 py-3 rounded-lg font-medium transition">
                <span>👤</span>
                <span>Profil Saya</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-[#AB03A9]">
            <a href="#" class="flex items-center space-x-3 text-red-300 hover:text-white px-4 py-2 rounded-lg transition">
                <span>🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Top Navbar -->
        <header class="bg-white h-20 shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-2xl font-bold text-[#5003C0]">Dashboard Klien</h1>

            <div class="flex items-center space-x-6">
                <!-- Notifikasi -->
                <div class="relative cursor-pointer">
                    <span class="text-2xl">🔔</span>
                    <span class="absolute top-0 right-0 bg-[#FFD51E] text-[#5003C0] text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">3</span>
                </div>
                <!-- Profil -->
                <div class="flex items-center space-x-3 border-l pl-6 border-gray-200">
                    <div class="w-10 h-10 bg-[#5003C0] rounded-full flex items-center justify-center text-white font-bold">A</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">PT. Maju Jaya</p>
                        <p class="text-xs text-gray-500">Klien Terverifikasi</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Laporan -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#5003C0]">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Laporan</p>
                    <p class="text-3xl font-extrabold text-[#5003C0]">12</p>
                </div>
                <!-- Dalam Proses -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#AB03A9]">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Dalam Proses</p>
                    <p class="text-3xl font-extrabold text-[#AB03A9]">3</p>
                </div>
                <!-- Selesai -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#FFD51E]">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Selesai</p>
                    <p class="text-3xl font-extrabold text-gray-800">9</p>
                </div>
            </div>

            <!-- Tabel Laporan Terbaru -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 flex justify-between items-center border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Laporan Terbaru</h2>
                    <button class="bg-[#FFD51E] text-[#5003C0] font-bold px-5 py-2.5 rounded-lg shadow hover:bg-yellow-400 transition flex items-center space-x-2">
                        <span>+</span>
                        <span>Buat Laporan Baru</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">ID Laporan</th>
                                <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- Baris 1 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-[#5003C0]">#LPR-001</td>
                                <td class="px-6 py-4 text-gray-800">AC Ruang Meeting</td>
                                <td class="px-6 py-4 text-gray-500">22 Sep 2026</td>
                                <td class="px-6 py-4">
                                    <span class="bg-[#FFD51E] bg-opacity-20 text-[#5003C0] px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-[#AB03A9] hover:text-[#5003C0] font-semibold text-sm">Detail</button>
                                </td>
                            </tr>
                            <!-- Baris 2 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-[#5003C0]">#LPR-002</td>
                                <td class="px-6 py-4 text-gray-800">Printer Lantai 2</td>
                                <td class="px-6 py-4 text-gray-500">20 Sep 2026</td>
                                <td class="px-6 py-4">
                                    <span class="bg-[#AB03A9] bg-opacity-10 text-[#AB03A9] px-3 py-1 rounded-full text-xs font-bold">Diproses</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-[#AB03A9] hover:text-[#5003C0] font-semibold text-sm">Detail</button>
                                </td>
                            </tr>
                            <!-- Baris 3 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-[#5003C0]">#LPR-003</td>
                                <td class="px-6 py-4 text-gray-800">Lampu Koridor</td>
                                <td class="px-6 py-4 text-gray-500">18 Sep 2026</td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-[#AB03A9] hover:text-[#5003C0] font-semibold text-sm">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
