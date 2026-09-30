<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pelaporan Kerusakan - Teknisi Qan</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS CDN Fallback + Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#5003C0',
                        'primary-light': '#6B2BD6',
                        'primary-dark': '#3D0299',
                        secondary: '#FFD51E',
                        'secondary-dark': '#E6BF00',
                        tertiary: '#AB03A9',
                        'tertiary-light': '#C94DC8',
                        surface: '#F5F5F5',
                        bodytext: '#333333',
                    }
                }
            }
        }
    </script>

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/report-form.js'])
    @endif

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .tab-btn.active {
            background-color: #5003C0;
            color: white;
        }

        .tab-content.hidden {
            display: none;
        }

        /* Fix untuk mobile button clicks */
        button, a[role="button"] {
            position: relative;
            z-index: 10;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        /* Ensure input file tidak mengblokir */
        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            z-index: -1;
        }

        .photo-drop-zone {
            z-index: 1;
        }

        /* Form inputs should not prevent button clicks */
        form, .form-group {
            position: relative;
            z-index: 1;
        }

        /* Prevent text selection on buttons during touch */
        button {
            touch-action: manipulation;
        }

        /* Fix html5-qrcode overlay blocking touch events */
        #qr-reader {
            pointer-events: none !important;
        }

        #qr-reader * {
            pointer-events: none !important;
        }

        /* Ensure buttons are always clickable on mobile */
        button, a[role="button"], input[type="button"], input[type="submit"] {
            pointer-events: auto !important;
        }
    </style>
</head>

<body class="bg-surface text-bodytext min-h-screen flex flex-col justify-between antialiased">

    <!-- Navbar Sederhana -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-primary">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('important/Logo.png') }}" alt="Logo Teknisi Qan"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-wider block leading-none">TEKNISIQAN</span>
                    <span class="text-[8px] sm:text-[10px] text-gray-400 font-semibold tracking-widest uppercase">Pelaporan
                        Publik</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ url('/') }}"
                    class="text-xs sm:text-sm font-semibold text-gray-500 hover:text-primary transition flex items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i>
                    <span class="hidden sm:inline">Lacak Tiket</span>
                </a>
                <div class="btn btn-primary">
                    <a href="{{ route('login') }}"
                        class="bg-primary text-white font-bold px-3 sm:px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama: Form Pelaporan Kerusakan -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12 flex-grow w-full">

        <!-- Header Halaman -->
        <div class="text-center mb-8 sm:mb-10">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary/25 border border-secondary/40 text-purple-950 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="bi bi-broadcast text-tertiary"></i>
                Layanan Pelaporan Tanpa Login
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-primary">Formulir Pelaporan Kerusakan</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-xl mx-auto">
                Silakan lengkapi data barang dan detail kerusakan di bawah ini. Tim teknisi kami akan segera
                memverifikasi dan menindaklanjuti laporan Anda.
            </p>
        </div>

        <!-- Kartu Formulir -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 md:p-12 relative overflow-visible">
            <!-- Ornamen Aksen Atas -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-tertiary to-secondary pointer-events-none">
            </div>

            <form action="{{ route('non-user-reports.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-8" id="reportForm">
                @csrf
                <input type="hidden" id="unitId" name="unitId" value="">
                <input type="hidden" id="compId" name="compId" value="">
                <input type="hidden" id="reportDate" name="reportDate" value="">

                <!-- SEKSI 1: PILIHAN PENCARIAN UNIT -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                            1
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Cari Unit Barang</h2>
                    </div>

                    <!-- Toggle Buttons: Scan QR vs Manual Input -->
                    <div class="flex gap-3 mb-6">
                        <button type="button" id="mode-scanner"
                            class="px-5 py-2 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm transition flex items-center gap-2 bg-gray-200 text-gray-700 hover:bg-gray-300"
                            style="cursor: pointer; pointer-events: auto;">
                            <i class="bi bi-qr-code"></i>
                            <span>Scan QR</span>
                        </button>
                        <button type="button" id="mode-manual"
                            class="px-5 py-2 sm:py-3 rounded-lg font-semibold text-xs sm:text-sm transition flex items-center gap-2 bg-primary text-white"
                            style="cursor: pointer; pointer-events: auto;">
                            <i class="bi bi-keyboard"></i>
                            <span>Input Manual</span>
                        </button>
                    </div>

                    <!-- SCANNER VIEW -->
                    <div id="scanner-view" class="hidden">
                        <div id="qr-reader" class="w-full rounded-xl overflow-hidden bg-black" style="aspect-ratio: 1; max-width: 100%; max-height: 500px; margin: 0 auto;"></div>
                    </div>

                    <!-- MANUAL INPUT VIEW -->
                    <div id="manual-view" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Perusahaan -->
                        <div>
                            <label for="manual_compId" class="block text-sm font-semibold text-gray-700 mb-2">
                                Perusahaan / Instansi <span class="text-red-500">*</span>
                            </label>
                            <select id="manual_compId" name="manual_compId"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition bg-white"
                                onchange="loadUnits(this.value)">
                                <option value="" disabled selected hidden>-- Pilih Perusahaan --</option>
                                @foreach ($companies ?? [] as $company)
                                    <option value="{{ $company->compId }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('compId')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit / Barang -->
                        <div>
                            <label for="manual_unitId" class="block text-sm font-semibold text-gray-700 mb-2">
                                Unit / Barang <span class="text-red-500">*</span>
                            </label>
                            <select id="manual_unitId" name="manual_unitId"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition bg-white"
                                onchange="selectUnit(this.value)">
                                <option value="" disabled selected hidden>-- Pilih Unit --</option>
                            </select>
                            @error('unitId')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Unit Terpilih -->
                    <div id="unit-info" class="mt-6 p-4 sm:p-6 bg-primary/5 border border-primary/20 rounded-xl hidden">
                        <h3 class="text-xs sm:text-sm font-bold text-primary mb-3">
                            <i class="bi bi-check-circle"></i> Unit Berhasil Dipilih
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                            <div>
                                <p class="text-gray-600">Nomor Unit:</p>
                                <p id="info-unit-number" class="font-semibold text-gray-800"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nama Unit:</p>
                                <p id="info-unit-name" class="font-semibold text-gray-800"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Ruangan:</p>
                                <p id="info-room-name" class="font-semibold text-gray-800"></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Perusahaan:</p>
                                <p id="info-company-name" class="font-semibold text-gray-800"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: DATA IDENTITAS PELAPOR -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Identitas Pelapor</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="reportByName" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="reportByName" name="reportByName"
                                placeholder="Contoh: Ahmad Rizki"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                value="{{ old('reportByName') }}" required>
                            @error('reportByName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. WhatsApp / Telepon -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                No. WhatsApp / Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_phone" name="contact_phone"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                value="{{ old('contact_phone') }}" required>
                            @error('contact_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Opsional) -->
                        <div>
                            <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Email (Opsional)
                            </label>
                            <input type="email" id="contact_email" name="contact_email"
                                placeholder="ahmad@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                value="{{ old('contact_email') }}">
                            @error('contact_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Divisi / Departemen -->
                        <div>
                            <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">
                                Divisi / Departemen
                            </label>
                            <input type="text" id="department" name="department"
                                placeholder="Contoh: Operasional / HRD / Umum"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                value="{{ old('department') }}">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 3: DETAIL KERUSAKAN -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-secondary/30 text-amber-900 flex items-center justify-center font-bold text-sm">
                            3
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Detail & Gejala Kerusakan</h2>
                    </div>

                    <div>
                        <label for="problem" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="problem" name="problem" rows="4"
                            placeholder="Jelaskan secara detail kendala yang dialami, suara aneh, lampu indikator, atau pesan error yang muncul..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                            required>{{ old('problem') }}</textarea>
                        @error('problem')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEKSI 4: BUKTI FOTO (5 INPUT FILE) -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            4
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Lampiran Foto Kerusakan (Opsional)</h2>
                    </div>

                    <p class="text-xs text-gray-600 mb-4">Maksimal 5 foto (masing-masing maksimal 5MB)</p>

                    <div class="space-y-3" id="photo-inputs-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative photo-input-wrapper" data-photo-index="{{ $i }}">
                                <label for="photo-{{ $i }}" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                    Foto {{ $i }} @if($i === 1) <span class="text-gray-400">(Utama)</span> @endif
                                </label>
                                <div class="relative border-2 border-dashed border-gray-300 hover:border-primary rounded-xl p-4 sm:p-6 text-center transition bg-gray-50/50 cursor-pointer group photo-drop-zone"
                                    onclick="document.getElementById('photo-{{ $i }}').click()">
                                    <input type="file" id="photo-{{ $i }}" name="photos[]" accept="image/png,image/jpg,image/jpeg,image/webp"
                                        class="hidden photo-input" data-index="{{ $i }}">
                                    
                                    <div class="photo-display-{{ $i }} photo-display">
                                        <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 mb-2 block"></i>
                                        <p class="text-xs font-medium text-gray-700">Klik untuk pilih file</p>
                                        <p class="text-[10px] text-gray-400">JPG, PNG, WEBP (Max 5MB)</p>
                                    </div>

                                    <div id="file-info-{{ $i }}" class="photo-info hidden">
                                        <img id="photo-preview-{{ $i }}" class="w-full h-48 object-cover rounded-lg mb-2" alt="Preview">
                                        <p class="text-xs text-gray-600" id="file-name-{{ $i }}"></p>
                                    </div>
                                </div>
                                @error("photos." . ($i - 1))
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endfor
                    </div>

                    @error('photos')
                        <p class="text-red-500 text-xs mt-3">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TOMBOL SUBMIT & INFO -->
                <div
                    class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600 text-base"></i>
                        <span>Laporan akan langsung diteruskan ke tim teknisi siaga.</span>
                    </p>

                    <button type="submit"
                        class="w-full sm:w-auto bg-primary hover:bg-tertiary text-white font-bold px-6 sm:px-8 py-3 sm:py-4 rounded-xl transition duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 text-xs sm:text-sm cursor-pointer min-h-12 active:scale-95"
                        style="position: relative; z-index: 20; pointer-events: auto;">
                        <i class="bi bi-send-fill text-secondary"></i>
                        <span>Kirim Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-6 sm:py-8 border-t border-white/10 mt-8 sm:mt-12">
        <div
            class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-purple-200">
            <div class="flex items-center space-x-2 flex-wrap justify-center">
                <i class="bi bi-gear-wide-connected text-secondary"></i>
                <span class="font-bold text-white">TEKNISI QAN</span>
                <span>&bull; Layanan Pelaporan Kerusakan Barang</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Teknisi Qan. All rights reserved.
            </div>
        </div>
    </footer>



</body>

</html>
