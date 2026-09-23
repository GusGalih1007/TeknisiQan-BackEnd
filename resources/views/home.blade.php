@extends('layouts.dashboard')

@section('title', 'Dashboard Klien - Teknisi Qan')
@section('page-title', 'Dashboard Klien')
@section('sidebar-active', 'dashboard')

@section('content')
    <!-- Baris 1: Kartu Statistik (3 Kartu) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Kartu 1: Total Laporan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Laporan</p>
                    <p class="text-4xl font-extrabold text-primary">24</p>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 font-medium">
                        <i class="bi bi-arrow-up-right text-emerald-500"></i>
                        <span class="text-emerald-600 font-semibold">+3 laporan</span> bulan ini
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Dalam Proses -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Dalam Proses</p>
                    <p class="text-4xl font-extrabold text-tertiary">8</p>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 font-medium">
                        <i class="bi bi-hourglass-split text-tertiary"></i>
                        <span class="text-tertiary font-semibold">5 menunggu</span> verifikasi teknisi
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-tertiary/10 flex items-center justify-center text-tertiary text-2xl">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Selesai -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Selesai Diperbaiki</p>
                    <p class="text-4xl font-extrabold text-emerald-600">16</p>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 font-medium">
                        <i class="bi bi-check-circle-fill text-emerald-500"></i>
                        <span class="text-emerald-600 font-semibold">100%</span> tuntas bulan ini
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl">
                    <i class="bi bi-check-all"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Tombol Aksi & Tabel Riwayat Laporan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Tabel Header & CTA Button -->
        <div class="p-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Riwayat Laporan Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">Daftar kerusakan barang yang telah dilaporkan ke tim teknisi</p>
            </div>
            
            <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}" 
               class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm">
                <i class="bi bi-plus-lg text-base"></i>
                <span>Buat Laporan Baru</span>
            </a>
        </div>

        <!-- Tabel Riwayat -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4">ID Laporan</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Tanggal Lapor</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Baris 1: Menunggu -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-semibold text-primary">#LPR-2026-001</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">AC Central Ruang Meeting Utama</td>
                        <td class="px-6 py-4 text-gray-500">22 Sep 2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-secondary/30 text-amber-900 border border-secondary/50 px-3 py-1 rounded-full text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                Menunggu
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="text-primary hover:text-tertiary font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 2: Diproses -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-semibold text-primary">#LPR-2026-002</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">Printer LaserJet Lantai 2</td>
                        <td class="px-6 py-4 text-gray-500">20 Sep 2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-tertiary text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm shadow-tertiary/20">
                                <i class="bi bi-gear-wide-connected text-[10px] animate-spin"></i>
                                Diproses
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="text-primary hover:text-tertiary font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 3: Diproses -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-semibold text-primary">#LPR-2026-003</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">Lift Barang Gedung B</td>
                        <td class="px-6 py-4 text-gray-500">19 Sep 2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-tertiary text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm shadow-tertiary/20">
                                <i class="bi bi-gear-wide-connected text-[10px] animate-spin"></i>
                                Diproses
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="text-primary hover:text-tertiary font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 4: Selesai -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-semibold text-primary">#LPR-2026-004</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">Genset Cadangan 150 kVA</td>
                        <td class="px-6 py-4 text-gray-500">15 Sep 2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm shadow-emerald-600/20">
                                <i class="bi bi-check text-xs"></i>
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="text-primary hover:text-tertiary font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 5: Selesai -->
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 font-semibold text-primary">#LPR-2026-005</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">Kamera CCTV Lobby Depan</td>
                        <td class="px-6 py-4 text-gray-500">12 Sep 2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm shadow-emerald-600/20">
                                <i class="bi bi-check text-xs"></i>
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="text-primary hover:text-tertiary font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition">
                                Detail
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
