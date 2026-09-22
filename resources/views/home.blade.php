@extends('layouts.dashboard')

@section('page-title', 'Dashboard')
@section('sidebar-active', 'dashboard')

@section('content')
    {{-- Statistics Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Card 1 - Total Laporan --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Laporan</p>
                    <p class="text-3xl font-bold text-primary mt-1">24</p>
                    <p class="text-xs text-success mt-2 flex items-center gap-1">
                        <i class="bi bi-arrow-up"></i> +3 bulan ini
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-xl text-primary"></i>
                </div>
            </div>
        </div>

        {{-- Card 2 - Dalam Proses --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Dalam Proses</p>
                    <p class="text-3xl font-bold text-tertiary mt-1">8</p>
                    <p class="text-xs text-tertiary mt-2 flex items-center gap-1">
                        <i class="bi bi-clock"></i> 5 menunggu konfirmasi
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-tertiary/10 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-xl text-tertiary"></i>
                </div>
            </div>
        </div>

        {{-- Card 3 - Selesai --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai</p>
                    <p class="text-3xl font-bold text-success mt-1">16</p>
                    <p class="text-xs text-success mt-2 flex items-center gap-1">
                        <i class="bi bi-check-circle"></i> Semua selesai tepat waktu
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-success/10 flex items-center justify-center">
                    <i class="bi bi-check-circle text-xl text-success"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div>
        {{-- Header Row --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Riwayat Laporan Terbaru</h2>
            <a href="#" class="bg-secondary text-primary px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-secondary-dark transition shadow-sm inline-flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Buat Laporan Baru
            </a>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Laporan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    {{-- Row 1 --}}
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">LP-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">AC Central Lantai 3</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">15 Sep 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-secondary/20 text-secondary-dark px-3 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-primary font-medium hover:text-primary-light transition">Detail</a>
                        </td>
                    </tr>
                    {{-- Row 2 --}}
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">LP-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">Lift Gedung A</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">14 Sep 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-tertiary/10 text-tertiary px-3 py-1 rounded-full text-xs font-semibold">Diproses</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-primary font-medium hover:text-primary-light transition">Detail</a>
                        </td>
                    </tr>
                    {{-- Row 3 --}}
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">LP-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">Genset Backup</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">13 Sep 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-success/10 text-success px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-primary font-medium hover:text-primary-light transition">Detail</a>
                        </td>
                    </tr>
                    {{-- Row 4 --}}
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">LP-004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">CCTV Lobby</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">12 Sep 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-secondary/20 text-secondary-dark px-3 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-primary font-medium hover:text-primary-light transition">Detail</a>
                        </td>
                    </tr>
                    {{-- Row 5 --}}
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">LP-005</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">Pompa Air</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">10 Sep 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-success/10 text-success px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-primary font-medium hover:text-primary-light transition">Detail</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
