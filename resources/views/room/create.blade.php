@extends('layouts.dashboard')

@section('title', (isset($data) ? 'Edit' : 'Tambah') . ' Ruangan - TeknisiQan')
@section('page-title', (isset($data) ? 'Edit' : 'Tambah') . ' Ruangan')
@section('sidebar-active', 'rooms')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Card Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-tertiary/5">
                <h2 class="text-2xl font-bold text-gray-800">{{ isset($data) ? 'Edit' : 'Tambah' }} Ruangan</h2>
                <p class="text-sm text-gray-500 mt-1">{{ isset($data) ? 'Ubah' : 'Isi' }} informasi ruangan di bawah</p>
            </div>

            <!-- Form Body -->
            <form action="{{ isset($data) ? route('rooms.update', $data->roomId) : route('rooms.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <!-- Nama Ruangan -->
                <div>
                    <label for="roomName" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="roomName" name="roomName" placeholder="Misal: Ruang Server, Meeting Room A, etc"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/30 transition @error('roomName') border-red-500 @enderror"
                        value="{{ old('roomName', $data->roomName ?? '') }}" maxlength="20" required>
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
                                        @if (old('compId') == $company->compId) selected @endif>
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
                                    <option value="{{ $company->compId }}" selected>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="compId" value="{{ $companies->first()->compId ?? '' }}">
                            <p class="text-xs text-gray-500 mt-2">Instansi otomatis berdasarkan akun Anda</p>
                        @endif
                        @error('compId')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-700">
                        <i class="bi bi-info-circle mr-2"></i>
                        <strong>Catatan:</strong> Ruangan ini akan menampung perangkat/aset yang ada di lokasi tersebut.
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
                        <i class="bi bi-check-lg mr-2"></i>{{ isset($data) ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
