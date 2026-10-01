@if (session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="flex-shrink-0 text-emerald-600 text-lg">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-semibold text-emerald-900 text-sm">Sukses!</h3>
            <p class="text-emerald-700 text-sm mt-1">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-emerald-400 hover:text-emerald-600 transition">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="flex-shrink-0 text-red-600 text-lg">
            <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-semibold text-red-900 text-sm">Terjadi Kesalahan</h3>
            <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-red-400 hover:text-red-600 transition">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

@if (session('warning'))
    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="flex-shrink-0 text-amber-600 text-lg">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-semibold text-amber-900 text-sm">Perhatian</h3>
            <p class="text-amber-700 text-sm mt-1">{{ session('warning') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-amber-400 hover:text-amber-600 transition">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

@if (session('info'))
    <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-start gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="flex-shrink-0 text-blue-600 text-lg">
            <i class="bi bi-info-circle-fill"></i>
        </div>
        <div class="flex-grow">
            <h3 class="font-semibold text-blue-900 text-sm">Informasi</h3>
            <p class="text-blue-700 text-sm mt-1">{{ session('info') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-blue-400 hover:text-blue-600 transition">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif
