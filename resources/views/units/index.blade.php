@extends('layouts.dashboard')

@section('title', 'Daftar Unit - TeknisiQan')
@section('page-title', 'Daftar Unit / Barang')
@section('sidebar-active', 'units')

@section('content')

    <!-- Header & Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
            <div class="flex-1">
                <!-- Search Bar -->
                <form method="GET" action="{{ route('units.index') }}" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" placeholder="Cari nomor unit, nama, atau ruangan..."
                            value="{{ request('search') }}"
                            class="w-full pl-4 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
                    </div>
                    <button type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-dark transition">
                        Cari
                    </button>
                </form>
            </div>

            @if (in_array(auth()->user()->role->value, ['superadmin', 'admin'], true))
                <a href="{{ route('units.create') }}"
                    class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto whitespace-nowrap">
                    <i class="bi bi-plus-lg text-base"></i>
                    <span>Tambah Unit Baru</span>
                </a>
            @endif
        </div>

        <form id="bulkPrintForm" action="{{ route('units.bulk-print-preview') }}" method="POST" target="_blank"
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-4 border-t border-gray-100">
            @csrf
            <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 cursor-pointer">
                <input type="checkbox" id="selectAllUnits"
                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                Pilih semua di halaman ini
            </label>
            <div class="flex items-center gap-3">
                <span id="selectedUnitCount" class="text-xs font-semibold text-gray-500">0 unit dipilih</span>
                <button type="submit" id="bulkPrintButton" disabled
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                    <i class="bi bi-printer"></i>
                    Print QR A4
                </button>
            </div>
        </form>
    </div>

    <!-- Units Grid (Card Layout) -->
    @if ($data->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data as $unit)
                <div class="unit-card relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition group">
                    <label class="absolute top-3 left-3 z-20 flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg bg-white/95 shadow-md"
                        title="Pilih {{ $unit->unitName }}">
                        <input type="checkbox" name="unitIds[]" value="{{ $unit->unitId }}" form="bulkPrintForm"
                            class="unit-checkbox h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary">
                    </label>
                    <!-- Image Section -->
                    <div class="relative h-40 bg-gray-100 overflow-hidden">
                        @if ($unit->photo && file_exists(public_path('storage/' . $unit->photo)))
                            <img src="{{ asset('storage/' . $unit->photo) }}" alt="{{ $unit->unitName }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full bg-linear-to-br from-primary/10 to-tertiary/10 flex items-center justify-center">
                                <i class="bi bi-image text-3xl text-gray-300"></i>
                            </div>
                        @endif
                        <!-- Badge -->
                        <div class="absolute top-3 right-3 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">
                            {{ $unit->unitNumber }}
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-4 sm:p-6 space-y-3">
                        <!-- Unit Name -->
                        <a href="{{ route('units.show', $unit->unitId) }}"
                            class="block font-bold text-gray-800 text-sm sm:text-base line-clamp-2 group-hover:text-primary transition">
                            {{ $unit->unitName }}
                        </a>

                        <!-- Info Grid -->
                        <div class="space-y-2 text-xs">
                            <div class="flex items-start gap-2">
                                <i class="bi bi-building text-primary shrink-0 mt-0.5"></i>
                                <span class="text-gray-600">{{ $unit->company?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="bi bi-door-closed text-tertiary shrink-0 mt-0.5"></i>
                                <span class="text-gray-600">{{ $unit->room?->roomName ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-3 border-t border-gray-100 flex gap-2">
                            <a href="{{ route('units.show', $unit->unitId) }}"
                                class="flex-1 text-center bg-purple-100 text-primary hover:bg-purple-200 font-semibold py-2 rounded-lg transition text-xs"
                                title="Detail Unit">
                                Detail
                            </a>
                            <a href="{{ route('units.print-preview', $unit->unitId) }}"
                                class="flex-1 text-center bg-emerald-100 text-emerald-800 hover:bg-emerald-200 font-semibold py-2 rounded-lg transition text-xs"
                                title="Print QR Code">
                                Print
                            </a>
                            @if (in_array(auth()->user()->role->value, ['superadmin', 'admin'], true))
                                <a href="{{ route('units.edit', $unit->unitId) }}"
                                    class="flex-1 text-center bg-blue-100 text-blue-800 hover:bg-blue-200 font-semibold py-2 rounded-lg transition text-xs"
                                    title="Edit Unit">
                                    Edit
                                </a>
                                <button type="button"
                                    class="flex-1 text-center bg-red-100 text-red-800 hover:bg-red-200 font-semibold py-2 rounded-lg transition text-xs"
                                    title="Hapus Unit"
                                    data-delete-url="{{ route('units.destroy', $unit->unitId) }}"
                                    onclick="openDeleteModal(this.dataset.deleteUrl, @js($unit->unitName))">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold text-gray-700">{{ $data->count() }}</span> dari <span
                    class="font-semibold text-gray-700">{{ $data->total() }}</span> total units
            </div>
            <div class="flex gap-2">
                {{ $data->links('pagination::simple-tailwind') }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <i class="bi bi-inbox text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Tidak Ada Data Unit</h3>
            <p class="text-sm text-gray-600 mb-6">
                {{ request('search') ? 'Tidak ada unit yang cocok dengan pencarian.' : 'Belum ada unit/barang yang terdaftar.' }}
            </p>
            @if (request('search'))
                <a href="{{ route('units.index') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-bold px-6 py-2.5 rounded-xl">
                    Reset Pencarian
                </a>
            @elseif (in_array(auth()->user()->role->value, ['superadmin', 'admin'], true))
                <a href="{{ route('units.create') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white font-bold px-6 py-2.5 rounded-xl transition">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Unit</span>
                </a>
            @endif
        </div>
    @endif

@endsection

@push('scripts')
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full animate-scale-in">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Hapus Data</h3>
                        <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-2">Anda akan menghapus:</p>
                <p class="text-gray-800 font-semibold text-base mb-4"><span id="deleteItemName"></span></p>
                <p class="text-gray-500 text-xs">Unit yang sudah memiliki laporan kerusakan tidak dapat dihapus.</p>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i class="bi bi-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-scale-in {
            animation: scaleIn 0.2s ease-out;
        }
    </style>

    <script>
        let deleteData = {
            url: null,
        };

        function openDeleteModal(url, name) {
            deleteData.url = url;

            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
            deleteData = {
                url: null,
            };
        }

        function confirmDelete() {
            if (deleteData.url) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteData.url;

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    input.value = csrfToken.getAttribute('content');
                    form.appendChild(input);
                }

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
            closeDeleteModal();
        }

        // Close modals when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Close modals when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        const unitCheckboxes = Array.from(document.querySelectorAll('.unit-checkbox'));
        const selectAllUnits = document.getElementById('selectAllUnits');
        const selectedUnitCount = document.getElementById('selectedUnitCount');
        const bulkPrintButton = document.getElementById('bulkPrintButton');

        function updateBulkSelection() {
            const selectedCount = unitCheckboxes.filter((checkbox) => checkbox.checked).length;

            selectedUnitCount.textContent = `${selectedCount} unit dipilih`;
            bulkPrintButton.disabled = selectedCount === 0;
            selectAllUnits.checked = unitCheckboxes.length > 0 && selectedCount === unitCheckboxes.length;
            selectAllUnits.indeterminate = selectedCount > 0 && selectedCount < unitCheckboxes.length;

            unitCheckboxes.forEach((checkbox) => {
                checkbox.closest('.unit-card')?.classList.toggle('ring-2', checkbox.checked);
                checkbox.closest('.unit-card')?.classList.toggle('ring-primary', checkbox.checked);
            });
        }

        selectAllUnits?.addEventListener('change', function() {
            unitCheckboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
            updateBulkSelection();
        });

        unitCheckboxes.forEach((checkbox) => checkbox.addEventListener('change', updateBulkSelection));

        document.getElementById('bulkPrintForm')?.addEventListener('submit', function(event) {
            if (!unitCheckboxes.some((checkbox) => checkbox.checked)) {
                event.preventDefault();
                alert('Pilih minimal satu unit untuk dicetak.');
            }
        });
    </script>
@endpush
