@extends('layouts.dashboard')

@section('title', 'Daftar Users - Teknisi Qan')
@section('page-title', 'Daftar Users')
@section('sidebar-active', 'users')

@section('content')
    <!-- Baris 1: Widget Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Widget 1: Total Users Terdaftar -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Users Terdaftar</p>
                    <p class="text-4xl font-extrabold text-primary">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 font-medium">
                        <i class="bi bi-people-fill text-primary"></i>
                        <span class="text-gray-600">Pengguna aktif di sistem</span>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>

        <!-- Widget 2: Users Bulan Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Users Bulan Ini</p>
                    <p class="text-4xl font-extrabold text-secondary">{{ $usersThisMonth }}</p>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1 font-medium">
                        <i class="bi bi-calendar-plus text-secondary"></i>
                        <span class="text-gray-600">Registrasi September 2026</span>
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary text-2xl">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Datatable Users -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header & Search Section -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Semua Users</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Data pengguna terdaftar dalam sistem Teknisi Qan</p>
                </div>
                
                <a href="{{ route('users.create') }}" 
                   class="bg-secondary text-primary font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-secondary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm w-full sm:w-auto">
                    <i class="bi bi-plus-lg text-base"></i>
                    <span>Tambah User Baru</span>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" placeholder="Cari nama atau email user..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
                    </div>
                    <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl font-medium hover:bg-primary-dark transition">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Datatable -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama User</th>
                        <th class="px-6 py-4">Perusahaan</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Tanggal Registrasi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $index => $user)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-semibold text-gray-600">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition" onclick="openUserDetailModal('{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone ?? '-' }}', '{{ $user->userId }}', '{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=5003C0&color=fff&bold=true' }}')">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center text-white text-xs font-bold overflow-hidden flex-shrink-0">
                                        @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
                                            <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=5003C0&color=fff&bold=true" 
                                                alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 hover:underline">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->company?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = [
                                        'admin' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
                                        'technician' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
                                        'client' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-200'],
                                    ];
                                    $roleConfig = $roleColors[$user->role->value] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'];
                                @endphp
                                <span class="inline-flex items-center {{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} border {{ $roleConfig['border'] }} px-3 py-1 rounded-full text-xs font-bold">
                                    {{ ucfirst($user->role->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" 
                                            class="text-primary hover:text-primary-dark font-bold text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition"
                                            title="Lihat Detail"
                                            onclick="openUserDetailModal('{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone ?? '-' }}', '{{ $user->userId }}', '{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=5003C0&color=fff&bold=true' }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('users.edit', $user->userId) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition"
                                       title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(Auth::id() !== $user->userId)
                                        <button type="button"
                                                class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition"
                                                title="Hapus User"
                                                onclick="openDeleteModal('{{ $user->userId }}', '{{ addslashes($user->name) }}', 'user')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs p-1.5 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i class="bi bi-inbox text-3xl text-gray-300"></i>
                                    <p class="text-gray-500 font-medium">Tidak ada data user ditemukan</p>
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
                Menampilkan <span class="font-semibold text-gray-700">{{ $data->count() }}</span> dari <span class="font-semibold text-gray-700">{{ $data->total() }}</span> total users
            </div>
            <div class="flex gap-2">
                {{ $data->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- User Detail Modal -->
    <div id="userDetailModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full animate-scale-in max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Detail User</h3>
                    <button type="button" onclick="closeUserDetailModal()"
                        class="text-gray-400 hover:text-gray-600 text-xl transition">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Photo Section -->
                <div class="text-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-tertiary flex items-center justify-center text-white text-4xl font-bold overflow-hidden mx-auto mb-4">
                        <img id="userPhoto" src="" alt="User Photo" class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-gray-800" id="userDetailName"></h4>
                </div>

                <!-- Info Sections -->
                <div class="space-y-4">
                    <!-- Email -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</p>
                        <p class="text-sm text-gray-700 break-all" id="userDetailEmail"></p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Telepon</p>
                        <p class="text-sm text-gray-700" id="userDetailPhone"></p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200"></div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <a id="editUserBtn" href="#" 
                           class="flex-1 text-center bg-blue-100 text-blue-800 hover:bg-blue-200 font-semibold py-2.5 rounded-lg transition">
                            <i class="bi bi-pencil mr-2"></i>Edit
                        </a>
                        <button type="button" onclick="closeUserDetailModal()"
                            class="flex-1 bg-gray-100 text-gray-800 hover:bg-gray-200 font-semibold py-2.5 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
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
            type: null,
            form: null
        };

        function openUserDetailModal(name, email, phone, userId, photoUrl) {
            document.getElementById('userDetailName').textContent = name;
            document.getElementById('userDetailEmail').textContent = email;
            document.getElementById('userDetailPhone').textContent = phone;
            document.getElementById('editUserBtn').href = `/users/${userId}/edit`;
            
            // Set photo dengan URL yang dikirim dari server
            const photoImg = document.getElementById('userPhoto');
            photoImg.src = photoUrl;
            photoImg.onerror = function() {
                // Fallback jika foto gagal
                this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=5003C0&color=fff&bold=true`;
            };
            
            document.getElementById('userDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUserDetailModal() {
            document.getElementById('userDetailModal').classList.add('hidden');
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
            deleteData = { id: null, type: null, form: null };
        }

        function confirmDelete() {
            if (deleteData.type === 'user') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/users/${deleteData.id}`;
                
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
                closeUserDetailModal();
                closeDeleteModal();
            }
        });

        // Close modals when clicking outside
        document.getElementById('userDetailModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeUserDetailModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endpush