@extends('layouts.public')

@section('title', 'Lacak Tiket Laporan - TeknisiQan')

@section('content')
<div class="min-h-screen bg-surface py-8 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-primary">Cari Status Laporan Anda</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-xl mx-auto">
                Masukkan nomor tiket laporan untuk melihat status dan riwayat penanganan.
            </p>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
            <form action="{{ route('tickets.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input
                    type="text"
                    name="ticket"
                    placeholder="Contoh: CMP-26-001"
                    value="{{ request('ticket') }}"
                    class="flex-1 px-4 sm:px-6 py-3 sm:py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                    required>
                <button
                    type="submit"
                    class="px-6 sm:px-8 py-3 sm:py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                    <i class="bi bi-search"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        <!-- Search Results / Instructions -->
        @if (isset($report))
            <!-- Report Found -->
            <div class="space-y-6">
                <!-- Report Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Header dengan Ticket Number & Status -->
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-tertiary/5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Tiket</p>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-primary">{{ $report->ticketNumber }}</h2>
                            </div>
                            <div class="text-right">
                                @php
                                    $hasResponse = $report->responses()->exists();
                                    $statusColor = $hasResponse
                                        ? ($report->responses()->latest()->first()?->status->value === 'completed'
                                            ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                            : 'bg-blue-100 text-blue-800 border-blue-200')
                                        : 'bg-amber-100 text-amber-800 border-amber-200';
                                    $statusLabel = $hasResponse
                                        ? ucfirst($report->responses()->latest()->first()?->status->value ?? 'Pending')
                                        : 'Sedang Diajukan';
                                @endphp
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</p>
                                <span class="inline-flex items-center {{ $statusColor }} border px-4 py-2 rounded-xl font-bold text-sm">
                                    @if (!$hasResponse)
                                        <i class="bi bi-clock-history mr-2"></i>
                                    @elseif ($report->responses()->latest()->first()?->status->value === 'completed')
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                    @else
                                        <i class="bi bi-arrow-repeat mr-2"></i>
                                    @endif
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Report Details -->
                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tanggal Laporan</p>
                                <p class="text-sm sm:text-base text-gray-800 font-medium">{{ $report->reportDate->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Perusahaan</p>
                                <p class="text-sm sm:text-base text-gray-800 font-medium">{{ $report->company->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Unit / Barang</p>
                                <p class="text-sm sm:text-base text-gray-800 font-medium">{{ $report->unit->unitName }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ruangan</p>
                                <p class="text-sm sm:text-base text-gray-800 font-medium">{{ $report->unit->room->roomName }}</p>
                            </div>
                        </div>

                        <!-- Problem Description -->
                        <div class="border-t border-gray-100 pt-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Deskripsi Kerusakan</p>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $report->problem }}</p>
                            </div>
                        </div>

                        <!-- Reporter Info -->
                        <div class="border-t border-gray-100 pt-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Pelapor</p>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 space-y-2">
                                    <span class="text-xs font-semibold text-gray-600">Nama: </span>
                                    <span class="text-sm text-gray-800">{{ $report->reportBy['name'] }}</span>
                            </div>
                        </div>

                        <!-- Photos -->
                        @if ($report->photo && count($report->photo) > 0)
                        <div class="border-t border-gray-100 pt-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Foto Kerusakan</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($report->photo as $photo)
                                    <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="aspect-square rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition group">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto Kerusakan" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Response Timeline -->
                @if ($report->responses()->exists())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            Riwayat Penanganan
                        </h3>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="space-y-6">
                            @foreach ($report->responses()->latest('responseDate')->get() as $index => $response)
                                <div class="relative pb-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <!-- Timeline dot -->
                                    <div class="absolute -left-4 top-0 w-8 h-8 rounded-full {{ $response->status->value === 'completed' ? 'bg-emerald-100 border-4 border-emerald-500' : 'bg-blue-100 border-4 border-blue-500' }} flex items-center justify-center -translate-x-4">
                                        <i class="bi {{ $response->status->value === 'completed' ? 'bi-check-lg' : 'bi-arrow-repeat' }} text-white text-xs font-bold"></i>
                                    </div>

                                    <div class="ml-8">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                            <h4 class="font-bold text-gray-800">{{ $response->technician->name ?? 'Teknisi' }}</h4>
                                            <p class="text-xs font-semibold text-gray-500">{{ $response->responseDate->format('d M Y H:i') }}</p>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</p>
                                                <span class="inline-flex items-center {{ $response->status->value === 'completed' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-blue-100 text-blue-800 border-blue-200' }} border px-3 py-1 rounded-full font-bold text-xs">
                                                    {{ ucfirst($response->status->value) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Solusi</p>
                                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                    <p class="text-sm text-gray-700">{{ $response->solution }}</p>
                                                </div>
                                            </div>
                                            @if ($response->photo && count($response->photo) > 0)
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Foto Hasil</p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                    @foreach ($response->photo as $photo)
                                                        <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="aspect-square rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition group">
                                                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto Hasil" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <!-- No Response Yet -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-600 mb-4">
                        <i class="bi bi-hourglass-split text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Sedang Diproses</h3>
                    <p class="text-sm text-gray-600">Laporan Anda baru diterima dan sedang menunggu penindaklanjutan dari tim teknisi. Kami akan segera memulai penanganan.</p>
                </div>
                @endif
            </div>
        @elseif (request('ticket'))
            <!-- Not Found -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                    <i class="bi bi-search text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Tiket Tidak Ditemukan</h3>
                <p class="text-sm text-gray-600 mb-6">Nomor tiket "<strong>{{ request('ticket') }}</strong>" tidak ditemukan di sistem kami. Silakan periksa kembali nomor tiket Anda.</p>
                <a href="{{ route('tickets.search') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white font-bold px-6 py-2.5 rounded-xl transition">
                    <i class="bi bi-arrow-left"></i>
                    <span>Coba Lagi</span>
                </a>
            </div>
        @else
            <!-- Instructions -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Info Cards -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-xl mb-4">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Nomor Tiket</h3>
                    <p class="text-xs text-gray-600">Cari menggunakan nomor tiket yang diberikan saat melakukan pelaporan. Format: ABC-YY-NNN</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Status Real-time</h3>
                    <p class="text-xs text-gray-600">Pantau progres penanganan laporan Anda secara real-time dari tim teknisi.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Riwayat Lengkap</h3>
                    <p class="text-xs text-gray-600">Lihat riwayat penanganan lengkap termasuk solusi dan bukti foto dari teknisi.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
