@extends('layouts.dashboard')

@section('title', 'Riwayat Laporan - TeknisiQan')
@section('page-title', 'Riwayat Laporan')
@section('sidebar-active', 'history')

@section('content')
    <div class="min-h-screen bg-surface py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Alerts -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm font-semibold text-red-800">Terjadi kesalahan:</p>
                    <ul class="mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-700">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-red-600 text-lg"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Search & Filter -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form action="{{ route('reports.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Cari
                                Tiket atau Unit</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nomor tiket atau nama unit..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Sedang
                                    Diajukan</option>
                                <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="delayed" {{ request('status') === 'delayed' ? 'selected' : '' }}>Tertunda
                                </option>
                                <option value="solved" {{ request('status') === 'solved' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 items-center">
                        <button type="submit"
                            class="px-6 py-2 bg-primary hover:bg-primary-dark text-white font-bold rounded-lg transition text-sm inline-flex items-center gap-2">
                            <i class="bi bi-search"></i>
                            <span>Cari</span>
                        </button>
                        @if (request('search') || request('status'))
                            <a href="{{ route('reports.index') }}"
                                class="px-6 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition text-sm inline-flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 ml-auto">
                            <a href="{{ route('non-user-reports.create') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition whitespace-nowrap">
                                <i class="bi bi-plus-lg"></i>
                                <span>Laporan Baru</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Reports List -->
            @if ($reports->count() > 0)
                <div class="space-y-4">
                    @foreach ($reports as $report)
                        @php
                            $latestResponse = $report->responses()->latest('responseDate')->first();
                            $status = $latestResponse ? $latestResponse->status->value : 'pending';
                            $statusLabel = match ($status) {
                                'pending' => 'Sedang Diajukan',
                                'processed' => 'Diproses',
                                'delayed' => 'Tertunda',
                                'solved' => 'Selesai',
                                'rejected' => 'Ditolak',
                                default => 'Unknown',
                            };
                            $statusColor = match ($status) {
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'processed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'delayed' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'solved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                            $statusIcon = match ($status) {
                                'pending' => 'bi-clock-history',
                                'processed' => 'bi-arrow-repeat',
                                'delayed' => 'bi-exclamation-triangle',
                                'solved' => 'bi-check-circle-fill',
                                'rejected' => 'bi-x-circle-fill',
                                default => 'bi-question-circle',
                            };
                        @endphp

                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                                    <!-- Ticket & Unit Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-bold text-primary">{{ $report->ticketNumber }}</h3>
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 {{ $statusColor }} border rounded-full font-bold text-xs">
                                                <i class="bi {{ $statusIcon }}"></i>
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $report->unit->unitName }} •
                                            {{ $report->company->name }}</p>
                                    </div>

                                    <!-- Date -->
                                    <div class="text-right sm:text-left">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                            Laporan</p>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $report->reportDate->format('d M Y H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="mb-3">
                                    <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $report->title ?? 'Tanpa Judul' }}</p>
                                </div>

                                <!-- Problem Preview -->
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Deskripsi
                                    </p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $report->problem }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-wrap gap-2 items-center justify-between">
                                    <div class="flex gap-2">
                                        <a href="{{ route('reports.show', $report->reportId) }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary/10 hover:bg-primary/20 text-primary font-bold rounded-lg transition text-sm">
                                            <i class="bi bi-eye"></i>
                                            <span>Lihat Detail</span>
                                        </a>

                                        @if (auth()->user()->role->value !== 'technician' && $status !== 'rejected' && $status !== 'solved')
                                            <button type="button" onclick="openRejectModal('{{ $report->reportId }}')"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-bold rounded-lg transition text-sm">
                                                <i class="bi bi-x-circle"></i>
                                                <span>Tolak</span>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($report->photo && count($report->photo) > 0)
                                        <span class="text-xs text-gray-500 font-semibold">
                                            <i class="bi bi-image"></i>
                                            {{ count($report->photo) }} foto
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $reports->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <i class="bi bi-inbox text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Laporan</h3>
                    <p class="text-sm text-gray-600 mb-6">Tidak ada laporan yang sesuai dengan filter Anda. Coba ubah
                        pencarian atau buat laporan baru.</p>
                    <div class="flex gap-3 justify-center">
                        @if (request('search') || request('status'))
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition text-sm">
                                <i class="bi bi-arrow-left"></i>
                                <span>Reset Filter</span>
                            </a>
                        @endif
                        <a href="{{ route('non-user-reports.create') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white font-bold rounded-lg transition text-sm">
                            <i class="bi bi-plus-lg"></i>
                            <span>Buat Laporan</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tolak Laporan -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle text-red-600"></i>
                    Tolak Laporan
                </h3>
            </div>

            <form id="rejectForm" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Alasan
                        Penolakan</label>
                    <textarea name="reason" required maxlength="500" placeholder="Jelaskan alasan penolakan laporan ini..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none"
                        rows="4"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition text-sm">
                        Tolak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(reportId) {
            const form = document.getElementById('rejectForm');
            form.action = `/reports/${reportId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
