@extends('layouts.dashboard')

@section('title', 'Detail Laporan - ' . $report->ticketNumber)

@section('content')
<div class="min-h-screen bg-surface py-6 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Back Button -->
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-bold text-sm mb-6 transition">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>

        <!-- Header dengan Ticket Number & Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 sm:p-8 border-b border-gray-100 bg-linear-to-r from-primary/5 to-tertiary/5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Tiket</p>
                        <h1 class="text-3xl font-extrabold text-primary">{{ $report->ticketNumber }}</h1>
                    </div>
                    <div class="text-right">
                        @php
                            $latestResponse = $report->responses()->latest('responseDate')->first();
                            $status = $latestResponse ? $latestResponse->status->value : 'pending';
                            $statusLabel = match($status) {
                                'pending' => 'Sedang Diajukan',
                                'processed' => 'Diproses',
                                'delayed' => 'Tertunda',
                                'solved' => 'Selesai',
                                'rejected' => 'Ditolak',
                                default => 'Unknown'
                            };
                            $statusColor = match($status) {
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'processed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'delayed' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'solved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                            $statusIcon = match($status) {
                                'pending' => 'bi-clock-history',
                                'processed' => 'bi-arrow-repeat',
                                'delayed' => 'bi-exclamation-triangle',
                                'solved' => 'bi-check-circle-fill',
                                'rejected' => 'bi-x-circle-fill',
                                default => 'bi-question-circle'
                            };
                        @endphp
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</p>
                        <span class="inline-flex items-center gap-1.5 {{ $statusColor }} border px-4 py-2 rounded-xl font-bold text-sm">
                            <i class="bi {{ $statusIcon }}"></i>
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
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Judul Laporan</p>
                    <p class="text-base font-semibold text-gray-800 mb-4">{{ $report->title }}</p>

                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Deskripsi Kerusakan</p>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $report->problem }}</p>
                    </div>
                </div>

                <!-- Reporter Info -->
                <div class="border-t border-gray-100 pt-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Pelapor</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-sm text-gray-800"><span class="font-semibold">Nama:</span> {{ $report->reportBy['name'] ?? 'N/A' }}</p>
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
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    Riwayat Penanganan
                </h2>
            </div>

            <div class="p-6 sm:p-8">
                <div class="space-y-6">
                    @foreach ($report->responses()->latest('responseDate')->get() as $index => $response)
                        @php
                            $respStatusColor = match($response->status->value) {
                                'processed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'delayed' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'solved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                            $respStatusIcon = match($response->status->value) {
                                'processed' => 'bi-arrow-repeat',
                                'delayed' => 'bi-exclamation-triangle',
                                'solved' => 'bi-check-lg',
                                'rejected' => 'bi-x-lg',
                                default => 'bi-question-circle'
                            };
                            $respStatusLabel = match($response->status->value) {
                                'processed' => 'Diproses',
                                'delayed' => 'Tertunda',
                                'solved' => 'Selesai',
                                'rejected' => 'Ditolak',
                                default => 'Tidak Diketahui'
                            };
                            $respTimelineColor = match($response->status->value) {
                                'processed' => 'bg-blue-500 ring-blue-100',
                                'delayed' => 'bg-orange-500 ring-orange-100',
                                'solved' => 'bg-emerald-500 ring-emerald-100',
                                'rejected' => 'bg-red-500 ring-red-100',
                                default => 'bg-gray-500 ring-gray-100'
                            };
                        @endphp

                        <div class="flex gap-4 sm:gap-5">
                            <!-- Timeline marker dan garis -->
                            <div class="relative flex w-10 shrink-0 justify-center">
                                @if (!$loop->last)
                                    <div class="absolute bottom-0 top-10 w-px bg-gray-200"></div>
                                @endif
                                <div class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full text-white shadow-sm ring-4 {{ $respTimelineColor }}">
                                    <i class="bi {{ $respStatusIcon }} text-sm font-bold"></i>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1 pb-8 {{ !$loop->last ? 'border-b border-gray-100' : 'pb-0' }}">
                                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $response->technician->name ?? 'Sistem' }}</h3>
                                        <p class="mt-0.5 text-xs text-gray-500">Petugas penanganan</p>
                                    </div>
                                    <p class="inline-flex items-center gap-1.5 whitespace-nowrap text-xs font-semibold text-gray-500">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $response->responseDate->format('d M Y H:i') }}
                                    </p>
                                </div>

                                <div class="space-y-4">
                                    <!-- Status Badge -->
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</p>
                                        <span class="inline-flex items-center gap-1.5 {{ $respStatusColor }} border px-3 py-1.5 rounded-full font-bold text-xs">
                                            <i class="bi {{ $respStatusIcon }}"></i>
                                            {{ $respStatusLabel }}
                                        </span>
                                    </div>

                                    <!-- Solution / Reason -->
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            {{ $response->status->value === 'rejected' ? 'Alasan Penolakan' : 'Solusi' }}
                                        </p>
                                        <div class="rounded-xl border {{ $response->status->value === 'rejected' ? 'border-red-100 bg-red-50/60' : 'border-gray-200 bg-gray-50' }} p-4">
                                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $response->solution }}</p>
                                        </div>
                                    </div>

                                    <!-- Photos -->
                                    @if ($response->photo && count($response->photo) > 0)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            {{ $response->status->value === 'rejected' ? 'Foto Dokumentasi' : 'Foto Hasil' }}
                                        </p>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                            @foreach ($response->photo as $photo)
                                                <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="aspect-square rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition group">
                                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-600 mb-4">
                <i class="bi bi-hourglass-split text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Sedang Diproses</h3>
            <p class="text-sm text-gray-600">Laporan ini baru diterima dan sedang menunggu penindaklanjutan dari tim teknisi</p>
        </div>
        @endif

        <!-- Admin Actions -->
        @if (auth()->user()->role->value !== 'technician' && $status !== 'rejected' && $status !== 'solved')
        <div class="mt-6 flex gap-3 justify-center">
            <button type="button" onclick="openRejectModal()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 font-bold rounded-lg transition text-sm border border-red-200">
                <i class="bi bi-x-circle"></i>
                <span>Tolak Laporan</span>
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tolak Laporan -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-exclamation-circle text-red-600"></i>
                Tolak Laporan
            </h3>
        </div>

        <form id="rejectForm" action="{{ route('reports.reject', $report->reportId) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Alasan Penolakan</label>
                <textarea 
                    name="reason" 
                    required 
                    maxlength="500"
                    placeholder="Jelaskan alasan penolakan laporan ini..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none"
                    rows="4"></textarea>
                <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition text-sm">
                    Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});
</script>
@endsection
