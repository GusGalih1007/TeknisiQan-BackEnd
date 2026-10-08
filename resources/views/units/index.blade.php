@extends('layouts.dashboard')

@section('title', 'Daftar Unit - TeknisiQan')
@section('page-title', 'Daftar Unit / Barang')
@section('sidebar-active', 'units')

@section('content')

    <!-- Header & Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
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
                        class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
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

        <!-- Datatable -->
        @if ($data->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="px-6 py-4 w-12">
                                <input type="checkbox" id="selectAllCheckbox"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                            </th>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nomor Unit</th>
                            <th class="px-6 py-4">Nama Unit</th>
                            <th class="px-6 py-4">Ruangan</th>
                            <th class="px-6 py-4">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $index => $unit)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="unitIds[]" value="{{ $unit->unitId }}" form="bulkPrintForm"
                                        class="unit-checkbox h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-600">
                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                        {{ $unit->unitNumber }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $unit->unitName }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    {{ $unit->room?->roomName ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $unit->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('units.show', $unit->unitId) }}"
                                            class="text-primary hover:text-primary-dark font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('units.print-preview', $unit->unitId) }}"
                                            class="text-emerald-600 hover:text-emerald-800 font-bold text-xs bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition"
                                            title="Print QR Code">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        @if (in_array(auth()->user()->role->value, ['superadmin', 'admin'], true))
                                            <a href="{{ route('units.edit', $unit->unitId) }}"
                                                class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition"
                                                title="Edit Unit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                                                title="Hapus Unit"
                                                onclick="openDeleteModal('{{ $unit->unitId }}', '{{ addslashes($unit->unitName) }}', 'unit')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data unit ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $data->count() }}</span> dari <span
                        class="font-semibold text-gray-700">{{ $data->total() }}</span> total unit
                </div>
                <div class="flex gap-2">
                    {{ $data->links('pagination::simple-tailwind') }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="inline-block">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-inbox text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada unit ditemukan</p>
                    @if (request('search'))
                        <p class="text-gray-400 text-sm mt-2">Coba ubah pencarian Anda</p>
                        <a href="{{ route('units.index') }}"
                            class="inline-block mt-4 bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition">
                            Reset Pencarian
                        </a>
                    @elseif (in_array(auth()->user()->role->value, ['superadmin', 'admin'], true))
                        <p class="text-gray-400 text-sm mt-2">Mulai dengan membuat unit baru</p>
                        <a href="{{ route('units.create') }}"
                            class="inline-block mt-4 bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-dark transition">
                            <i class="bi bi-plus-lg mr-1"></i>Tambah Unit Baru
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" data-modal="delete">
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
            id: null,
            type: null
        };

        function openDeleteModal(id, name, type) {
            deleteData.id = id;
            deleteData.type = type;

            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteData = {
                id: null,
                type: null
            };
        }

        function confirmDelete() {
            if (deleteData.type === 'unit') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/units/${deleteData.id}`;

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

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const selectAllUnits = document.getElementById('selectAllUnits');
        const unitCheckboxes = document.querySelectorAll('.unit-checkbox');
        const selectedUnitCount = document.getElementById('selectedUnitCount');
        const bulkPrintButton = document.getElementById('bulkPrintButton');

        function updateCheckboxStates() {
            const checkedCount = document.querySelectorAll('.unit-checkbox:checked').length;
            selectedUnitCount.textContent = `${checkedCount} unit dipilih`;
            bulkPrintButton.disabled = checkedCount === 0;

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === unitCheckboxes.length && unitCheckboxes.length > 0;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                unitCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCheckboxStates();
            });
        }

        if (selectAllUnits) {
            selectAllUnits.addEventListener('change', function() {
                unitCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCheckboxStates();
            });
        }

        unitCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCheckboxStates);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush
