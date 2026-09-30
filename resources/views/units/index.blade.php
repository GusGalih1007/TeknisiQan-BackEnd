@extends('layouts.dashboard')

@section('title', 'Daftar Unit - Teknisi Qan')
@section('page-title', 'Daftar Unit')
@section('sidebar-active', 'units')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Manajemen Unit</h2>
                <p class="text-sm text-gray-500 mt-2">Kelola data unit penyimpanan dalam sistem</p>
            </div>
            
            @if (auth()->user()->role->value === 'superadmin' || auth()->user()->role->value === 'admin')
                <a href="{{ route('units.create') }}" 
                   class="bg-secondary text-primary font-bold px-6 py-3 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
                    <i class="bi bi-plus-lg text-base"></i>
                    <span>Tambah Unit Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('units.index') }}" class="flex gap-2">
            <div class="flex-1 relative">
                <input type="text" name="search" placeholder="Cari nama unit..." 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-dark transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Units Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Unit</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $index => $unit)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-semibold text-gray-600">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 cursor-pointer hover:text-primary transition" 
                                   onclick="openUnitDetailModal('{{ addslashes($unit->unitName) }}', '{{ addslashes($unit->company->name ?? '-') }}', '{{ addslashes($unit->room->roomName ?? '-') }}', '{{ $unit->unitId }}')">
                                    {{ $unit->unitName }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $unit->company->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $unit->room->roomName ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" 
                                            class="text-primary hover:text-primary-dark font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition"
                                            title="Lihat Detail"
                                            onclick="openUnitDetailModal('{{ addslashes($unit->unitName) }}', '{{ addslashes($unit->company->name ?? '-') }}', '{{ addslashes($unit->room->roomName ?? '-') }}', '{{ $unit->unitId }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if(auth()->user()->role->value === 'superadmin' || auth()->user()->role->value === 'admin')
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
                            <td colspan="5" class="px-6 py-8 text-center">
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
                Menampilkan <span class="font-semibold text-gray-700">{{ $data->count() }}</span> dari <span class="font-semibold text-gray-700">{{ $data->total() }}</span> total unit
            </div>
            <div class="flex gap-2">
                {{ $data->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Unit Detail Modal -->
    <div id="unitDetailModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full animate-scale-in">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Detail Unit</h3>
                    <button type="button" onclick="closeUnitDetailModal()"
                        class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                <!-- Unit Name -->
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Unit</p>
                    <p class="text-sm text-gray-800 font-semibold" id="unitDetailName"></p>
                </div>

                <!-- Company -->
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Instansi</p>
                    <p class="text-sm text-gray-700" id="unitDetailCompany"></p>
                </div>

                <!-- Room -->
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ruangan</p>
                    <p class="text-sm text-gray-700" id="unitDetailRoom"></p>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200"></div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <a id="editUnitBtn" href="#" 
                       class="flex-1 text-center bg-blue-100 text-blue-800 hover:bg-blue-200 font-semibold py-2.5 rounded-lg transition">
                        <i class="bi bi-pencil mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="closeUnitDetailModal()"
                        class="flex-1 bg-gray-100 text-gray-800 hover:bg-gray-200 font-semibold py-2.5 rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
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
                <p class="text-gray-500 text-xs">Semua data yang terkait dengan item ini juga akan dihapus. Pastikan Anda benar-benar ingin menghapus.</p>
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

        function openUnitDetailModal(name, company, room, unitId) {
            document.getElementById('unitDetailName').textContent = name;
            document.getElementById('unitDetailCompany').textContent = company;
            document.getElementById('unitDetailRoom').textContent = room;
            document.getElementById('editUnitBtn').href = `/units/${unitId}/edit`;
            
            document.getElementById('unitDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUnitDetailModal() {
            document.getElementById('unitDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

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
            deleteData = { id: null, type: null };
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

        // Close modals when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeUnitDetailModal();
                closeDeleteModal();
            }
        });

        // Close modals when clicking outside
        document.getElementById('unitDetailModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeUnitDetailModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush
