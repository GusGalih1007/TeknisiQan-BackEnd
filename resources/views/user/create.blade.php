@extends('layouts.dashboard')

@section('title', $pageTitle . ' - Teknisi Qan')
@section('page-title', $pageTitle)
@section('sidebar-active', 'users')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-tertiary/5">
                <h2 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h2>
                <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah untuk menambah user baru</p>
            </div>

            <!-- Form Body -->
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Row 1: Nama & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama User -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama User <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" placeholder="Masukan nama user"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" placeholder="Masukan email"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Telepon & Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" placeholder="Masukan nomor telepon"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('phone') border-red-500 @enderror"
                            value="{{ old('phone') }}" required>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('password') border-red-500 @enderror"
                            required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('password_confirmation') border-red-500 @enderror"
                        required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 4: Company & Role -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company/Instansi -->
                    @if ($companies->count() > 0)
                        <div>
                            <label for="compId" class="block text-sm font-semibold text-gray-700 mb-2">
                                Perusahaan/Instansi
                                @if (auth()->user()->role->value == 'superadmin')
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <select id="compId" name="compId"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('compId') border-red-500 @enderror"
                                @if (auth()->user()->role->value == 'admin') disabled @endif
                                @if (auth()->user()->role->value == 'superadmin') required @endif>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->compId }}"
                                        @if (auth()->user()->role->value == 'admin' || old('compId') == $company->compId) selected @endif>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if (auth()->user()->role->value == 'admin')
                                <input type="hidden" name="compId" value="{{ auth()->user()->compId }}">
                            @endif
                            @error('compId')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Role -->
                    @if (auth()->user()->role->value == 'superadmin' && $roles)
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('role') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}"
                                        @if (old('role') == $role->value) selected @endif>
                                        {{ ucfirst($role->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif (auth()->user()->role->value == 'admin')
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition bg-gray-50"
                                disabled>
                                <option value="technician" selected>Technician</option>
                            </select>
                            <input type="hidden" name="role" value="technician">
                            <p class="text-xs text-gray-500 mt-2">Role otomatis diatur sebagai Technician</p>
                        </div>
                    @endif
                </div>

                <!-- Row 5: Photo Upload -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Profil (Opsional)
                    </label>
                    <div class="relative">
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('photo') border-red-500 @enderror"
                            onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG, WebP | Max: 5MB</p>
                    </div>
                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border border-gray-200">
                        <button type="button" onclick="removeImage()" class="mt-2 text-red-500 text-sm hover:text-red-700 font-medium">
                            Hapus Foto
                        </button>
                    </div>
                    @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('users.index') }}"
                        class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark transition">
                        <i class="bi bi-check-lg mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('previewImg').src = e.target.result;
                        document.getElementById('imagePreview').classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }

            function removeImage() {
                document.getElementById('photo').value = '';
                document.getElementById('imagePreview').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
