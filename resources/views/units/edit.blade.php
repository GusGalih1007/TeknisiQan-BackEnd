@extends('layouts.dashboard')

@section('title', 'Edit Unit - Teknisi Qan')
@section('page-title', 'Edit Unit')
@section('sidebar-active', 'units')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Edit Unit</h2>
            <p class="text-sm text-gray-500 mt-2">Perbarui informasi unit di bawah ini</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('units.update', $data->unitId) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Unit Name -->
                <div>
                    <label for="unitName" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unitName" id="unitName" placeholder="Masukkan nama unit"
                           value="{{ old('unitName', $data->unitName) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition">
                    @error('unitName')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room -->
                <div>
                    <label for="roomId" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="roomId" id="roomId" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition"
                            onchange="updateCompanyInfo()">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->roomId }}" data-comp-id="{{ $room->compId }}" data-comp-name="{{ $room->company->name ?? '-' }}" {{ old('roomId', $data->roomId) == $room->roomId ? 'selected' : '' }}>
                                {{ $room->roomName }} - {{ $room->company->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('roomId')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Info Display -->
                <div id="companyInfoBox" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">Instansi Terpilih</p>
                    <p class="text-sm font-semibold text-blue-800" id="companyName">-</p>
                </div>

                <!-- Unit Photo -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Unit <span class="font-normal text-gray-400">(Opsional)</span>
                    </label>
                    @if ($data->photo)
                        <img id="currentPhoto" src="{{ asset('storage/' . $data->photo) }}" alt="{{ $data->unitName }}"
                            class="mb-4 h-44 w-full rounded-xl object-cover border border-gray-200">
                        <label class="mb-4 flex items-center gap-2 text-sm text-red-600">
                            <input type="checkbox" name="removePhoto" value="1" {{ old('removePhoto') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            Hapus foto saat ini
                        </label>
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                        class="block w-full rounded-xl text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary/10 file:px-4 file:py-2.5 file:font-semibold file:text-primary hover:file:bg-primary/20">
                    <p class="mt-2 text-xs text-gray-500">Pilih foto baru untuk mengganti foto lama. Maksimal 5MB.</p>
                    @error('photo')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <img id="photoPreview" class="hidden mt-4 h-44 w-full rounded-xl object-cover border border-gray-200" alt="Pratinjau foto baru">
                </div>

                <!-- Info Box -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-xs text-green-600"><i class="bi bi-info-circle mr-2"></i>Dibuat pada {{ $data->created_at->format('d M Y H:i') }}</p>
                    <p class="text-xs text-green-600">Terakhir diubah {{ $data->updated_at->diffForHumans() }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('units.index') }}" 
                       class="flex-1 text-center px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition">
                        <i class="bi bi-check-lg mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateCompanyInfo() {
            const roomSelect = document.getElementById('roomId');
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const compId = selectedOption.getAttribute('data-comp-id');
            const compName = selectedOption.getAttribute('data-comp-name');
            const companyNameDisplay = document.getElementById('companyName');

            if (compId) {
                companyNameDisplay.textContent = compName;
            } else {
                companyNameDisplay.textContent = '-';
            }
        }

        // Update on page load
        window.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('roomId').value) {
                updateCompanyInfo();
            }

            document.getElementById('photo').addEventListener('change', function() {
                const preview = document.getElementById('photoPreview');
                const file = this.files[0];

                if (!file) {
                    preview.classList.add('hidden');
                    preview.removeAttribute('src');
                    return;
                }

                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            });
        });
    </script>
@endpush
