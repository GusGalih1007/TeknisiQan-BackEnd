@extends('layouts.dashboard')

@section('title', 'Daftar Ruangan - TeknisiQan')
@section('page-title', 'Daftar Ruangan')
@section('sidebar-active', 'rooms')

@section('content')
    <!-- Header & Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header & Search Section -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <form method="GET" action="{{ route('rooms.index') }}" class="flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" name="search" placeholder="Cari nama ruangan..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-4 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
                            </div>
                            <button type="submit"
                                class="bg-primary text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-dark transition">
                                Cari
                            </button>
                        </form>
                    </div>

                @if (auth()->user()->role->value == 'admin' || auth()->user()->role->value == 'superadmin')
                    <a href="{{ route('rooms.create') }}"
                        class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
                        <i class="bi bi-plus-lg text-base"></i>
                        <span>Tambah Ruangan Baru</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Datatable -->
        @if ($data->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Ruangan</th>
                            <th class="px-6 py-4">Total Perangkat</th>
                            <th class="px-6 py-4">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $index => $room)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 font-semibold text-gray-600">
                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $room->roomName }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button" 
                                        class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold cursor-pointer hover:bg-blue-200 transition"
                                        onclick="openUnitModal('{{ $room->roomId }}', '{{ addslashes($room->roomName) }}')">
                                        {{ $room->roomUnits->count() ?? 0 }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $room->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if (auth()->user()->role->value == 'admin' || auth()->user()->role->value == 'superadmin')
                                            <a href="{{ route('rooms.edit', $room->roomId) }}"
                                                class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition"
                                                title="Edit Ruangan">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                                                title="Hapus Ruangan"
                                                onclick="openDeleteModal('{{ $room->roomId }}', '{{ addslashes($room->roomName) }}', 'room')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data ruangan ditemukan</p>
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
                        class="font-semibold text-gray-700">{{ $data->total() }}</span> total ruangan
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
                        <i class="bi bi-door-closed text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada ruangan ditemukan</p>
                    @if (auth()->user()->role->value == 'admin' || auth()->user()->role->value == 'superadmin')
                        <p class="text-gray-400 text-sm mt-2">Mulai dengan membuat ruangan baru</p>
                        <a href="{{ route('rooms.create') }}"
                            class="inline-block mt-4 bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-dark transition">
                            <i class="bi bi-plus-lg mr-1"></i>Tambah Ruangan Baru
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <!-- Units Modal -->
    <div id="unitsModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto" data-modal="units">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-6xl my-8 animate-scale-in">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100 sticky top-0 bg-white rounded-t-2xl">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Unit - <span id="roomNameDisplay"></span></h3>
                        <p class="text-xs text-gray-500">Kelola unit yang ada di ruangan ini</p>
                </div>
                </div>
            </div>

            <!-- Modal Body with Table -->
            <div class="p-6">
                <div id="unitsTableContainer" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                                <th class="px-6 py-4 w-12">
                                    <input type="checkbox" id="selectAllUnitsInModal"
                                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                </th>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nomor Unit</th>
                                <th class="px-6 py-4">Nama Unit</th>
                                <th class="px-6 py-4">Tanggal Dibuat</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="unitsTableBody" class="divide-y divide-gray-100">
                            <!-- Units will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div id="noUnitsMessage" class="text-center py-8 hidden">
                    <i class="bi bi-inbox text-3xl text-gray-300"></i>
                    <p class="text-gray-500 font-medium mt-2">Tidak ada unit di ruangan ini</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span id="selectedUnitCountModal" class="text-xs font-semibold text-gray-500">0 unit dipilih</span>
                    <button type="button" id="bulkPrintButtonModal" disabled
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                        onclick="bulkPrintUnits()">
                        <i class="bi bi-printer"></i>
                        Print QR A4
                    </button>
                </div>
                <button type="button" onclick="closeUnitModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

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
                        <h3 class="text-lg font-bold text-gray-800">Hapus Ruangan</h3>
                        <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-2">Anda akan menghapus:</p>
                <p class="text-gray-800 font-semibold text-base mb-4"><span id="deleteItemName"></span></p>
                <p class="text-gray-500 text-xs">Semua perangkat yang terkait dengan ruangan ini juga akan dihapus. Pastikan
                    Anda benar-benar ingin menghapus.</p>
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

        let currentRoomId = null;
        let currentRoomName = null;
        let selectedUnitIds = new Set();

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
            if (deleteData.type === 'room') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/rooms/${deleteData.id}`;

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

        // Units Modal Functions
        async function openUnitModal(roomId, roomName) {
            currentRoomId = roomId;
            currentRoomName = roomName;
            selectedUnitIds.clear();

            document.getElementById('roomNameDisplay').textContent = roomName;
            document.getElementById('unitsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            await loadUnitsForRoom(roomId);
        }

        function closeUnitModal() {
            document.getElementById('unitsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            selectedUnitIds.clear();
            currentRoomId = null;
            currentRoomName = null;
        }

        async function loadUnitsForRoom(roomId) {
            try {
                const response = await fetch(`/api/rooms/${roomId}/units`);
                const data = await response.json();

                const tableBody = document.getElementById('unitsTableBody');
                const noMessage = document.getElementById('noUnitsMessage');
                const tableContainer = document.getElementById('unitsTableContainer');

                tableBody.innerHTML = '';

                if (data.units && data.units.length > 0) {
                    tableContainer.classList.remove('hidden');
                    noMessage.classList.add('hidden');

                    data.units.forEach((unit, index) => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50/60 transition';
                        row.innerHTML = `
                            <td class="px-6 py-4">
                                <input type="checkbox" value="${unit.unitId}" class="unit-checkbox-modal h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    onchange="updateUnitSelection()">
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-600">${index + 1}</td>
                            <td class="px-6 py-4">${unit.unitNumber}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">${unit.unitName}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">${new Date(unit.created_at).toLocaleDateString('id-ID', {year: 'numeric', month: 'short', day: 'numeric'})}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/units/${unit.unitId}" target="_blank"
                                        class="text-primary hover:text-primary-dark font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition"
                                        title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/units/${unit.unitId}/print-preview" target="_blank"
                                        class="text-emerald-600 hover:text-emerald-800 font-bold text-xs bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition"
                                        title="Print QR Code">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    ${auth_user_role === 'admin' || auth_user_role === 'superadmin' ? `
                                        <a href="/units/${unit.unitId}/edit"
                                            class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition"
                                            title="Edit Unit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                            class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                                            title="Hapus Unit"
                                            onclick="openDeleteModal('${unit.unitId}', '${unit.unitName.replace(/'/g, "\\'")}', 'unit')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    ` : ''}
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableContainer.classList.add('hidden');
                    noMessage.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading units:', error);
                alert('Gagal memuat data unit. Silakan coba lagi.');
            }
        }

        function updateUnitSelection() {
            selectedUnitIds.clear();
            const checkboxes = document.querySelectorAll('.unit-checkbox-modal:checked');
            checkboxes.forEach(checkbox => {
                selectedUnitIds.add(checkbox.value);
            });

            const count = selectedUnitIds.size;
            document.getElementById('selectedUnitCountModal').textContent = `${count} unit dipilih`;
            document.getElementById('bulkPrintButtonModal').disabled = count === 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('selectAllUnitsInModal')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.unit-checkbox-modal');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateUnitSelection();
            });
        });

        function bulkPrintUnits() {
            if (selectedUnitIds.size === 0) {
                alert('Pilih minimal satu unit untuk dicetak');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/units/bulk-print-preview';
            form.target = '_blank';

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken.getAttribute('content');
                form.appendChild(input);
            }

            selectedUnitIds.forEach(unitId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'unitIds[]';
                input.value = unitId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function deleteUnit(unitId, unitName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/units/${unitId}`;

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
            document.body.removeChild(form);
        }

        // Expose auth role to JS
        const auth_user_role = "{{ auth()->user()->role->value }}";

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (!document.getElementById('unitsModal')?.classList.contains('hidden')) {
                    closeUnitModal();
                } else {
                    closeDeleteModal();
                }
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        document.getElementById('unitsModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeUnitModal();
            }
        });
    </script>
@endpush
