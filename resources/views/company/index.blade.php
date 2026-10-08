@extends('layouts.dashboard')

@section('title', 'Daftar Instansi - TeknisiQan')
@section('page-title', 'Daftar Instansi')
@section('sidebar-active', 'companies')

@section('content')
    <!-- Header & Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header & Search Section -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <form method="GET" action="{{ route('companies.index') }}" class="flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" name="search" placeholder="Cari nama atau alamat instansi..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-4 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
                            </div>
                            <button type="submit"
                                class="bg-primary text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-dark transition">
                                Cari
                            </button>
                        </form>
                    </div>

                @if (auth()->user()->role->value == 'superadmin')
                    <a href="{{ route('companies.create') }}"
                        class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
                        <i class="bi bi-plus-lg text-base"></i>
                        <span>Tambah Instansi Baru</span>
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
                            <th class="px-6 py-4">Nama Instansi</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Pemimpin</th>
                            <th class="px-6 py-4">Total Users</th>
                            <th class="px-6 py-4">Ruangan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $index => $company)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 font-semibold text-gray-600">
                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $company->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    <button type="button" 
                                        onclick="openDetailModal('Alamat Lengkap', '{{ addslashes($company->address) }}')"
                                        class="text-primary hover:underline">
                                        {{ Str::limit($company->address, 40) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($company->leader)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs flex-shrink-0 overflow-hidden">
                                                @if ($company->leader->photo && file_exists(public_path('storage/' . $company->leader->photo)))
                                                    <img src="{{ asset('storage/' . $company->leader->photo) }}" alt="{{ $company->leader->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($company->leader->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $company->leader->name }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $company->companyUsers->count() ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $company->companyRooms->count() ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if (auth()->user()->role->value == 'superadmin')
                                            <a href="{{ route('companies.edit', $company->compId) }}"
                                                class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition"
                                                title="Edit Instansi">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                                                title="Hapus Instansi"
                                                onclick="openDeleteModal('{{ $company->compId }}', '{{ addslashes($company->name) }}', 'company')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('companies.show', $company->compId) }}"
                                                class="text-primary hover:text-primary-dark font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition"
                                                title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data instansi ditemukan</p>
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
                        class="font-semibold text-gray-700">{{ $data->total() }}</span> total instansi
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
                        <i class="bi bi-building text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada instansi ditemukan</p>
                    @if (auth()->user()->role->value == 'superadmin')
                        <p class="text-gray-400 text-sm mt-2">Mulai dengan membuat instansi baru</p>
                        <a href="{{ route('companies.create') }}"
                            class="inline-block mt-4 bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-dark transition">
                            <i class="bi bi-plus-lg mr-1"></i>Tambah Instansi Baru
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" data-modal="detail">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full animate-scale-in">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800" id="detailTitle">Detail</h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-2">Konten Lengkap:</p>
                <p class="text-gray-800 font-semibold text-base break-words whitespace-pre-wrap" id="detailContent"></p>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-200 flex gap-3 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeDetailModal()"
                    class="flex-1 px-4 py-2.5 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition">
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
                        <h3 class="text-lg font-bold text-gray-800">Hapus Data</h3>
                        <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-2">Anda akan menghapus:</p>
                <p class="text-gray-800 font-semibold text-base mb-4"><span id="deleteItemName"></span></p>
                <p class="text-gray-500 text-xs">Semua data yang terkait dengan item ini juga akan dihapus. Pastikan Anda
                    benar-benar ingin menghapus.</p>
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
            type: null,
            form: null
        };

        function openDetailModal(title, content) {
            document.getElementById('detailTitle').textContent = title;
            document.getElementById('detailContent').textContent = content;
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
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
            deleteData = {
                id: null,
                type: null,
                form: null
            };
        }

        function confirmDelete() {
            if (deleteData.type === 'company') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/companies/${deleteData.id}`;

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
                closeDetailModal();
                closeDeleteModal();
            }
        });

        // Close modals when clicking outside (backdrop)
        document.getElementById('detailModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDetailModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush
