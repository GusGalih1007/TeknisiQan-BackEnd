<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pelaporan Kerusakan - TeknisiQan</title>

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
                    <img src="{{ asset('important/Logo.png') }}" alt="Logo TeknisiQan"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-lg sm:text-xl font-extrabold tracking-wider block leading-none">TEKNISIQAN</span>
                    <span class="text-[8px] sm:text-[10px] text-gray-400 font-semibold tracking-widest uppercase">Pelaporan
                        Publik</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('tickets.search') }}"
                    class="text-xs sm:text-sm font-semibold text-gray-500 hover:text-primary transition flex items-center gap-1.5">
                    <span>Lacak Tiket</span>
                </a>
                <div class="btn btn-primary">
                    @auth
                    <a href="{{ route('temp.dashboard') }}"
                        class="bg-primary text-white font-bold px-3 sm:px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                        <span>Dashboard</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="bg-primary text-white font-bold px-3 sm:px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                        <span>Login Admin</span>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama: Form Pelaporan Kerusakan -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12 flex-grow w-full">

        <!-- Session Alerts -->
        @include('components.alert')

        <!-- Kartu Formulir -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 md:p-12 relative overflow-visible">
            <!-- Ornamen Aksen Atas -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-tertiary to-secondary pointer-events-none">
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-primary mb-2">Formulir Pelaporan Kerusakan</h1>
            <!-- Header Halaman -->
        <div class="text-left mb-4 sm:mb-4">
            <p class="text-xs sm:text-sm text-gray-500">
                Silakan lengkapi data barang dan detail kerusakan di bawah ini.
            </p>
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
                        <h2 class="text-lg font-bold text-gray-800">Cari Unit Barang</h2>
                    </div>

                    <!-- Toggle Switch: Scan QR vs Manual Input -->
                    <div class="mb-6">
                        <div class="relative grid grid-cols-2 w-full max-w-xs h-10 bg-primary rounded-full p-0.5 shadow-md shadow-primary/15">
                            <div id="toggle-bg"
                                class="absolute left-0.5 top-0.5 h-9 rounded-full bg-white shadow-sm transition-transform duration-300 ease-out"
                                style="width: calc(50% - 0.125rem); transform: translateX(100%);"></div>

                            <button type="button" id="mode-scanner"
                                class="relative z-10 flex items-center justify-center rounded-full px-3 text-xs font-bold text-white transition-colors duration-300">
                                Scan QR
                            </button>
                            <button type="button" id="mode-manual"
                                class="relative z-10 flex items-center justify-center rounded-full px-3 text-xs font-bold text-primary transition-colors duration-300">
                                Input Manual
                            </button>
                        </div>
                    </div>

                    <!-- SCANNER VIEW -->
                    <div id="scanner-view" class="hidden">
                        <div id="qr-reader" class="w-full rounded-xl overflow-hidden bg-black" style="aspect-ratio: 1; max-width: 100%; max-height: 500px; margin: 0 auto;"></div>
                        <div id="scanner-status" class="mt-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs font-medium text-blue-700">
                            Arahkan QR Code unit ke dalam kotak pemindai.
                        </div>
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
                        <h2 class="text-lg font-bold text-gray-800">Identitas Pelapor</h2>
                    </div>

                    <div>
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
                    </div>
                </div>

                <!-- SEKSI 3: DETAIL KERUSAKAN & GEJALA -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Detail & Gejala Kerusakan</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Judul Kerusakan -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                placeholder="Contoh: Layar Tidak Menyala"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Kerusakan -->
                        <div>
                            <label for="problem" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi & Gejala <span class="text-red-500">*</span>
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
                </div>

                <!-- SEKSI 4: BUKTI FOTO (5 INPUT FILE) -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Lampiran Foto Kerusakan (Opsional)</h2>
                    </div>

                    <p class="text-xs text-gray-600 mb-4">Maksimal 5 foto (masing-masing maksimal 5MB)</p>

                    <div class="flex gap-2 overflow-x-auto pb-2" id="photo-inputs-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="relative photo-input-wrapper flex-shrink-0" data-photo-index="{{ $i }}">
                                <div class="relative border-2 border-dashed border-gray-300 hover:border-primary rounded-lg p-2 text-center transition bg-gray-50/50 cursor-pointer group photo-drop-zone w-24 h-24 sm:w-28 sm:h-28 flex flex-col items-center justify-center"
                                    onclick="document.getElementById('photo-{{ $i }}').click()">
                                    <input type="file" id="photo-{{ $i }}" name="photos[]" accept="image/png,image/jpg,image/jpeg,image/webp"
                                        class="hidden photo-input" data-index="{{ $i }}">

                                    <div class="photo-display-{{ $i }} photo-display w-full h-full flex flex-col items-center justify-center">
                                        <i class="bi bi-cloud-arrow-up text-lg text-gray-400 mb-0.5"></i>
                                        <p class="text-[10px] font-medium text-gray-700">Foto {{ $i }}</p>
                                    </div>

                                    <div id="file-info-{{ $i }}" class="photo-info hidden w-full h-full">
                                        <img id="photo-preview-{{ $i }}" class="w-full h-full object-cover rounded" alt="Preview">
                                    </div>
                                </div>
                                <button type="button" id="remove-photo-{{ $i }}"
                                    class="remove-photo hidden absolute -right-2 -top-2 z-20 h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white shadow-md transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300"
                                    data-index="{{ $i }}" aria-label="Hapus foto {{ $i }}" title="Hapus foto">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </button>
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
                <span class="font-bold text-white">TEKNISIQAN</span>
                <span>&bull; Layanan Pelaporan Kerusakan Barang</span>
            </div>
            <div>
                &copy; {{ date('Y') }} TeknisiQan. All rights reserved.
            </div>
        </div>
    </footer>



</body>

</html>
