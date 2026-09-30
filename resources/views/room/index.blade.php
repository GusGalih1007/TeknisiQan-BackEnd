@extends('layouts.dashboard')

@section('title', 'Daftar Ruangan - Teknisi Qan')
@section('page-title', 'Daftar Ruangan')
@section('sidebar-active', 'rooms')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Manajemen Ruangan</h2>
                <p class="text-sm text-gray-500 mt-2">Kelola ruangan di instansi Anda</p>
            </div>
            
            @if (auth()->user()->role->value == 'admin' || auth()->user()->role->value == 'superadmin')
                <a href="{{ route('rooms.create') }}" 
                   class="bg-secondary text-primary font-bold px-6 py-3 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
                    <i class="bi bi-plus-lg text-base"></i>
                    <span>Tambah Ruangan Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('rooms.index') }}" class="flex gap-2">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" placeholder="Cari nama ruangan..." 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-dark transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Rooms Grid -->
    @if($data->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($data as $room)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden group">
                    <!-- Room Header -->
                    <div class="p-6 bg-gradient-to-r from-primary/10 to-tertiary/10 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $room->roomName }}</h3>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-building text-primary"></i>
                                    {{ $room->company?->name ?? '-' }}
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-lg bg-primary/20 flex items-center justify-center text-primary text-xl">
                                <i class="bi bi-door-closed"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Room Stats -->
                    <div class="p-6">
                        <!-- Units Count -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 mb-1">Total Perangkat</p>
                                    <p class="text-2xl font-bold text-primary">{{ $room->roomUnits->count() ?? 0 }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-lg">
                                    <i class="bi bi-boxes"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Room Info -->
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium">ID Ruangan</p>
                                    <p class="text-xs font-mono text-gray-800 mt-1">{{ substr($room->roomId, 0, 8) }}...</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium">Dibuat</p>
                                    <p class="text-xs text-gray-800 mt-1">{{ $room->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @if (auth()->user()->role->value == 'admin' || auth()->user()->role->value == 'superadmin')
                            <div class="flex gap-2">
                                <a href="{{ route('rooms.edit', $room->roomId) }}" 
                                   class="flex-1 text-center bg-primary/10 text-primary hover:bg-primary/20 font-semibold py-2.5 rounded-lg transition text-sm">
                                    <i class="bi bi-pencil mr-1"></i>Edit
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ $room->roomId }}', '{{ $room->roomName }}', 'room')"
                                    class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2.5 rounded-lg transition text-sm">
                                    <i class="bi bi-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        @else
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                <p class="text-xs text-blue-700 font-medium">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Hubungi admin untuk mengelola ruangan
                                </p>
                            </div>
                        @endif
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
                    <i class="bi bi-door-closed text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada ruangan ditemukan</p>
                @if (auth()->user()->role->value == 'admin')
                    <p class="text-gray-400 text-sm mt-2">Mulai dengan membuat ruangan baru</p>
                    <a href="{{ route('rooms.create') }}" class="inline-block mt-4 bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-dark transition">
                        <i class="bi bi-plus-lg mr-1"></i>Tambah Ruangan Baru
                    </a>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('scripts')
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
                        <h3 class="text-lg font-bold text-gray-800">Hapus Ruangan</h3>
                        <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-2">Anda akan menghapus:</p>
                <p class="text-gray-800 font-semibold text-base mb-4"><span id="deleteItemName"></span></p>
                <p class="text-gray-500 text-xs">Semua perangkat yang terkait dengan ruangan ini juga akan dihapus. Pastikan Anda benar-benar ingin menghapus.</p>
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
            deleteData = { id: null, type: null };
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
