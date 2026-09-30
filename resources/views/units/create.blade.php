@extends('layouts.dashboard')

@section('title', 'Tambah Unit - Teknisi Qan')
@section('page-title', 'Tambah Unit Baru')
@section('sidebar-active', 'units')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Tambah Unit Baru</h2>
            <p class="text-sm text-gray-500 mt-2">Isi formulir di bawah untuk menambahkan unit baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('units.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Unit Name -->
                <div>
                    <label for="unitName" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unitName" id="unitName" placeholder="Masukkan nama unit"
                           value="{{ old('unitName') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('unitName') border-red-500 @enderror">
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
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('roomId') border-red-500 @enderror"
                            onchange="updateCompanyInfo()">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->roomId }}" data-comp-id="{{ $room->compId }}" data-comp-name="{{ $room->company->name ?? '-' }}" {{ old('roomId') == $room->roomId ? 'selected' : '' }}>
                                {{ $room->roomName }} - {{ $room->company->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('roomId')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden compId field -->
                <input type="hidden" name="compId" id="compId" value="{{ old('compId') }}">

                <!-- Company Info Display -->
                <div id="companyInfoBox" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">Instansi Terpilih</p>
                    <p class="text-sm font-semibold text-blue-800" id="companyName">-</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('units.index') }}" 
                       class="flex-1 text-center px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition">
                        <i class="bi bi-check-lg mr-2"></i>Simpan Unit
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
            const companyInfoBox = document.getElementById('companyInfoBox');
            const companyNameDisplay = document.getElementById('companyName');
            const compIdField = document.getElementById('compId');

            if (compId) {
                compIdField.value = compId;
                companyNameDisplay.textContent = compName;
                companyInfoBox.classList.remove('hidden');
            } else {
                compIdField.value = '';
                companyInfoBox.classList.add('hidden');
            }
        }

        // Update on page load if room is already selected
        window.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('roomId').value) {
                updateCompanyInfo();
            }
        });
    </script>
@endpush
