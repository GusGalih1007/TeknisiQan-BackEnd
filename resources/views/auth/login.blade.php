@extends('layouts.auth')

@section('title', 'Login Klien - Teknisi Qan')

@section('content')
    <!-- Header Form -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-gray-500 text-sm mt-2">
            Silakan masuk menggunakan akun yang telah didaftarkan oleh Superadmin.
        </p>
    </div>

    <!-- Alert Notifikasi Session -->
    @if (session('no-account'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3">
            <i class="bi bi-exclamation-octagon-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold">Akun Tidak Ditemukan</p>
                <p class="text-xs mt-0.5">{{ session('no-account') }}</p>
            </div>
        </div>
    @endif

    @if (session('invalid-password'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3">
            <i class="bi bi-shield-lock-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold">Autentikasi Gagal</p>
                <p class="text-xs mt-0.5">{{ session('invalid-password') }}</p>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg flex-shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-envelope"></i>
                </div>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    placeholder="nama@perusahaan.com"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50/20 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                >
            </div>
            @error('email')
                <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                    <i class="bi bi-x-circle"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-key"></i>
                </div>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Masukkan password Anda"
                    class="w-full pl-10 pr-10 py-3 rounded-xl border @error('password') border-red-400 bg-red-50/20 @else border-gray-300 @enderror focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                >
                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                    <i class="bi bi-x-circle"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer select-none">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember" 
                    {{ old('remember') ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-primary focus:ring-primary border-gray-300 accent-primary"
                >
                <span>Ingat Saya</span>
            </label>

            <a href="#" class="text-sm font-semibold text-tertiary hover:text-tertiary-light transition">
                Lupa Password?
            </a>
        </div>

        <!-- Tombol Masuk -->
        <button 
            type="submit" 
            class="w-full bg-primary hover:bg-tertiary text-white font-bold py-3.5 px-6 rounded-xl transition duration-200 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 group cursor-pointer text-sm"
        >
            <span>Masuk ke Akun</span>
            <i class="bi bi-arrow-right group-hover:translate-x-1 transition duration-200"></i>
        </button>
    </form>

    <!-- Info Pendaftaran Superadmin (Tanpa tombol Daftar) -->
    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
        <div class="inline-flex items-center gap-2 p-3.5 rounded-xl bg-gray-50 border border-gray-200/80 text-xs text-gray-600 text-left w-full">
            <i class="bi bi-info-circle-fill text-tertiary text-base flex-shrink-0"></i>
            <div>
                <span class="font-bold text-gray-800">Belum punya akun klien?</span><br>
                <span>Pendaftaran hanya dilakukan oleh Superadmin. Silakan hubungi admin perusahaan Anda.</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush