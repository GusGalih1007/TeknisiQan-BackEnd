@extends('layouts.dashboard')

@section('title', 'Detail Unit - Teknisi Qan')
@section('page-title', 'Detail Unit')
@section('sidebar-active', 'units')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">{{ $data->unitName }}</h2>
            <p class="text-sm text-gray-500 mt-2">Informasi lengkap unit penyimpanan</p>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
            @if ($data->photo)
                <img src="{{ asset('storage/' . $data->photo) }}" alt="{{ $data->unitName }}"
                    class="h-64 w-full rounded-xl object-cover border border-gray-100">
            @endif

            <!-- Unit Name -->
            <div class="pb-6 border-b border-gray-100">
                <p class="text-xs font-bold text-primary mb-2">{{ $data->unitNumber }}</p>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Unit</p>
                <p class="text-2xl font-bold text-gray-800">{{ $data->unitName }}</p>
            </div>

            <!-- Company -->
            <div class="pb-6 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Instansi</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $data->company?->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $data->company?->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Room -->
            <div class="pb-6 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ruangan</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                        <i class="bi bi-door-closed"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $data->room?->roomName ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Ruangan Penyimpanan</p>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dibuat Pada</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $data->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $data->created_at->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Terakhir Diubah</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $data->updated_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $data->updated_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('units.index') }}" 
                   class="flex-1 text-center px-6 py-3 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition">
                    <i class="bi bi-arrow-left mr-2"></i>Kembali
                </a>
                @if(auth()->user()->role->value === 'superadmin' || auth()->user()->role->value === 'admin')
                    <a href="{{ route('units.edit', $data->unitId) }}" 
                       class="flex-1 text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-pencil mr-2"></i>Edit
                    </a>
                    <button type="button" onclick="confirmDelete()"
                            class="flex-1 px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        <i class="bi bi-trash mr-2"></i>Hapus
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden delete form -->
    <form id="deleteForm" action="{{ route('units.destroy', $data->unitId) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus unit ini? Tindakan ini tidak dapat dibatalkan.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endpush
