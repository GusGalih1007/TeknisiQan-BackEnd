@extends('layouts.dashboard')

@section('title', 'Edit Ruangan - TeknisiQan')
@section('page-title', 'Edit Ruangan')
@section('sidebar-active', 'rooms')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-tertiary/5">
                <h2 class="text-2xl font-bold text-gray-800">Edit Ruangan</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah informasi ruangan di bawah</p>
            </div>

            <!-- Form Body -->
            <form action="{{ route('rooms.update', $data->roomId) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Ruangan -->
                <div>
                    <label for="roomName" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="roomName" name="roomName" placeholder="Misal: Ruang Server, Meeting Room A, etc"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('roomName') border-red-500 @enderror"
                        value="{{ old('roomName', $data->roomName) }}" maxlength="20" required>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 20 karakter</p>
                    @error('roomName')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company/Instansi -->
                @if($companies->count() > 0)
                    <div>
                        <label for="compId" class="block text-sm font-semibold text-gray-700 mb-2">
                            Instansi <span class="text-red-500">*</span>
                        </label>
                        @if (auth()->user()->role->value == 'superadmin')
                            <!-- Superadmin: Dropdown enabled -->
                            <select id="compId" name="compId"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('compId') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Instansi --</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->compId }}" 
                                        @if(old('compId', $data->compId) == $company->compId) selected @endif>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Pilih instansi untuk ruangan ini</p>
                        @else
                            <!-- Admin: Disabled -->
                            <select id="compId" name="compId"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition bg-gray-50"
                                disabled>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->compId }}" 
                                        @if(old('compId', $data->compId) == $company->compId) selected @endif>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="compId" value="{{ old('compId', $data->compId) }}">
                            <p class="text-xs text-gray-500 mt-2">Instansi otomatis berdasarkan akun Anda</p>
                        @endif
                        @error('compId')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Room Info -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">ID Ruangan</p>
                            <p class="text-sm font-mono text-gray-800">{{ $data->roomId }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Total Perangkat</p>
                            <p class="text-sm font-mono text-gray-800">{{ $data->roomUnits_count ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Dibuat Pada</p>
                            <p class="text-sm font-mono text-gray-800">{{ $data->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Diubah Pada</p>
                            <p class="text-sm font-mono text-gray-800">{{ $data->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-700">
                        <i class="bi bi-info-circle mr-2"></i>
                        <strong>Catatan:</strong> Ruangan ini menampung {{ $data->roomUnits_count ?? 0 }} perangkat/aset.
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('rooms.index') }}"
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
@endsection
