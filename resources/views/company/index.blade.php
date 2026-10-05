@extends('layouts.dashboard')

@section('title', 'Daftar Instansi - Teknisi Qan')
@section('page-title', 'Daftar Instansi')
@section('sidebar-active', 'companies')

@section('content')
    <!-- Header & Search Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
            <div class="flex-1">
                <!-- Search Bar -->
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
                    class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto whitespace-nowrap">
                    <i class="bi bi-plus-lg text-base"></i>
                    <span>Tambah Instansi Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Companies Grid -->
    @if ($data->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data as $company)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden group">
                    <!-- Logo Section -->
                    <div
                        class="relative h-32 bg-gradient-to-br from-primary/10 to-tertiary/10 flex items-center justify-center overflow-hidden">
                        @if ($company->logo && file_exists(public_path('storage/' . $company->logo)))
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-4xl font-bold text-primary/20">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Content Section -->
                    <div class="p-5 flex flex-col h-full">
                        <!-- Company Name -->
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $company->name }}</h3>

                        <!-- Address -->
                        <button type="button" onclick="openDetailModal('{{ $company->name }}', '{{ $company->address }}')"
                            class="text-xs text-gray-500 mb-3 flex items-center gap-2 hover:text-primary transition cursor-pointer group w-full">
                            <i class="bi bi-geo-alt-fill text-primary flex-shrink-0"></i>
                            <span class="truncate group-hover:underline">{{ $company->address }}</span>
                        </button>

                        <!-- Leader Info -->
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Pemimpin Instansi:</p>
                            @if ($company->leader)
                                <button type="button"
                                    onclick="openDetailModal('{{ addslashes($company->leader->name) }}', '{{ addslashes($company->leader->email) }}')"
                                    class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded transition cursor-pointer w-full">
                                    @if ($company->leader->photo)
                                        <div
                                            class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-gray-400 text-xs flex-shrink-0">
                                            <img src="{{ asset('storage/' . $company->leader->photo) }}"
                                                alt="{{ $company->leader->name }}"
                                                class="w-full h-full object-cover rounded-full">
                                        </div>
                                    @else
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs flex-shrink-0">
                                            {{ strtoupper(substr($company->leader->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 text-left">
                                        <p class="text-xs font-semibold text-gray-800 truncate hover:underline">
                                            {{ $company->leader->name }}</p>
                                        <p class="text-xs text-gray-500 truncate hover:underline">
                                            {{ $company->leader->email }}</p>
                                    </div>
                                </button>
                            @else
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs flex-shrink-0">
                                        <i class="bi bi-person-slash text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500">Belum ditentukan</p>
                                        <p class="text-xs text-gray-400">—</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-blue-50 rounded-lg p-2">
                                <p class="text-xs text-blue-600 font-semibold">Total Users</p>
                                <p class="text-lg font-bold text-blue-800">{{ $company->companyUsers->count() ?? 0 }}</p>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-2">
                                <p class="text-xs text-emerald-600 font-semibold">Ruangan</p>
                                <p class="text-lg font-bold text-emerald-800">{{ $company->companyRooms->count() ?? 0 }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            @if (auth()->user()->role->value == 'superadmin')
                                <a href="{{ route('companies.edit', $company->compId) }}"
                                    class="flex-1 text-center bg-primary/10 text-primary hover:bg-primary/20 font-semibold py-2 rounded-lg transition text-sm">
                                    <i class="bi bi-pencil mr-1"></i>Edit
                                </a>
                                <button type="button"
                                    onclick="openDeleteModal('{{ $company->compId }}', '{{ $company->name }}', 'company')"
                                    class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2 rounded-lg transition text-sm">
                                    <i class="bi bi-trash mr-1"></i>Hapus
                                </button>
                            @else
                                <a href="{{ route('companies.show', $company->compId) }}"
                                    class="flex-1 text-center bg-primary text-white hover:bg-primary-dark font-semibold py-2 rounded-lg transition text-sm">
                                    <i class="bi bi-eye mr-1"></i>Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $data->links('pagination::simple-tailwind') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
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
@endsection

@push('scripts')
    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full animate-scale-in">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800" id="detailTitle">Detail</h3>
                    <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
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

        // Close modals when clicking outside
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
