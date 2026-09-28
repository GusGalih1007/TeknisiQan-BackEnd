@extends('layouts.dashboard')

@section('title', 'Edit Instansi - Teknisi Qan')
@section('page-title', 'Edit Instansi')
@section('sidebar-active', 'companies')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-tertiary/5">
                <h2 class="text-2xl font-bold text-gray-800">Edit Instansi</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah informasi instansi di bawah</p>
            </div>

            <!-- Form Body -->
            <form action="{{ route('companies.update', $data->compId) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Row 1: Nama Instansi -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Instansi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" placeholder="Masukan nama instansi/perusahaan"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('name') border-red-500 @enderror"
                        value="{{ old('name', $data->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 2: Alamat -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3" placeholder="Masukan alamat lengkap instansi"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('address') border-red-500 @enderror"
                        required>{{ old('address', $data->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 3: Pemimpin Instansi -->
                <div>
                    <label for="leaderId" class="block text-sm font-semibold text-gray-700 mb-2">
                        Pemimpin Instansi (Admin) <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <select id="leaderId" name="leaderId"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('leaderId') border-red-500 @enderror">
                        <option value="">-- Pilih Admin Sebagai Pemimpin --</option>
                        @foreach ($leaders as $leader)
                            <option value="{{ $leader->userId }}"
                                @if (old('leaderId', $data->leaderId) == $leader->userId) selected @endif>
                                {{ $leader->name }} ({{ $leader->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('leaderId')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 4: Logo Upload -->
                <div>
                    <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Logo Instansi <span class="text-gray-500">(Opsional)</span>
                    </label>
                    
                    <!-- Current Logo Display -->
                    @if ($data->logo && file_exists(public_path('storage/' . $data->logo)))
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 mb-2 font-medium">Logo Saat Ini:</p>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $data->logo) }}" alt="{{ $data->name }}" 
                                    class="w-32 h-32 object-cover rounded-xl border border-gray-200">
                                <button type="button" onclick="document.getElementById('logo').click()" 
                                    class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-lg hover:bg-primary-dark transition">
                                    <i class="bi bi-pencil text-sm"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- File Input -->
                    <div class="relative">
                        <input type="file" id="logo" name="logo" accept="image/*"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('logo') border-red-500 @enderror"
                            onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, JPEG, WebP | Max: 5MB</p>
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-xs text-gray-600 mb-2 font-medium">Preview Logo Baru:</p>
                        <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border border-gray-200">
                        <button type="button" onclick="removeImage()" class="mt-2 text-red-500 text-sm hover:text-red-700 font-medium">
                            Batal Ubah Logo
                        </button>
                    </div>
                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('companies.index') }}"
                        class="flex-1 px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark transition">
                        <i class="bi bi-check-lg mr-2"></i>Perbarui
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
                document.getElementById('logo').value = '';
                document.getElementById('imagePreview').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
