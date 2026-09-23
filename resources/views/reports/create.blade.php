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

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-surface text-bodytext min-h-screen flex flex-col justify-between antialiased">

    <!-- Navbar Sederhana -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-primary">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('important/Logo.png') }}" alt="Logo Teknisi Qan"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-wider block leading-none">TEKNISI QAN</span>
                    <span class="text-[10px] text-gray-400 font-semibold tracking-widest uppercase">Pelaporan
                        Publik</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}"
                    class="text-xs sm:text-sm font-semibold text-gray-500 hover:text-primary transition flex items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i>
                    <span>Lacak Tiket</span>
                </a>
                <div class="btn btn-primary">
                    <a href="{{ route('login') }}"
                        class="bg-primary text-white font-bold px-5 py-2.5 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center space-x-2 text-sm">
                        {{-- <i class="bi bi-arrow-left"></i> --}}
                        <span>Login Admin/Teknisi</span>
                    </a>
                </div>
            </div>
    </header>

    <!-- Konten Utama: Form Scaffolding Pelaporan Kerusakan -->
    <main class="max-w-4xl mx-auto px-6 py-12 flex-grow w-full">

        <!-- Header Halaman -->
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary/25 border border-secondary/40 text-purple-950 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="bi bi-broadcast text-tertiary"></i>
                Layanan Pelaporan Tanpa Login
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-primary">Formulir Pelaporan Kerusakan</h1>
            <p class="text-sm text-gray-500 mt-2 max-w-xl mx-auto">
                Silakan lengkapi data barang dan detail kerusakan di bawah ini. Tim teknisi kami akan segera
                memverifikasi dan menindaklanjuti laporan Anda.
            </p>
        </div>

        <!-- Kartu Formulir Scaffolding -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sm:p-12 relative overflow-hidden">
            <!-- Ornamen Aksen Atas -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-tertiary to-secondary">
            </div>

            {{-- 
                ========================================================================
                TEMPLATE / SCAFFOLDING FORM PELAPORAN
                Silakan sesuaikan action form, name input, dan validasi sesuai kebutuhan Anda.
                ========================================================================
            --}}
            <form action="{{ Route::has('reports.store') ? route('reports.store') : '#' }}" method="POST"
                enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- SEKSI 1: DATA IDENTITAS PELAPOR -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                            1
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Identitas Pelapor</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="reporter_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="reporter_name" name="reporter_name"
                                placeholder="Contoh: Ahmad Rizki"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                required>
                        </div>

                        <!-- No. WhatsApp / Telepon -->
                        <div>
                            <label for="reporter_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                No. WhatsApp / Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="reporter_phone" name="reporter_phone"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                required>
                        </div>

                        <!-- Email (Opsional) -->
                        <div>
                            <label for="reporter_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Email (Opsional)
                            </label>
                            <input type="email" id="reporter_email" name="reporter_email"
                                placeholder="ahmad@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition">
                        </div>

                        <!-- Divisi / Perusahaan / Instansi -->
                        <div>
                            <label for="reporter_department" class="block text-sm font-semibold text-gray-700 mb-2">
                                Divisi / Departemen / Instansi
                            </label>
                            <input type="text" id="reporter_department" name="reporter_department"
                                placeholder="Contoh: Operasional / HRD / Umum"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition">
                        </div>
                    </div>
                </div>

                <!-- SEKSI 2: INFORMASI BARANG / FASILITAS -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-tertiary/10 text-tertiary flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Informasi Barang / Peralatan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Barang -->
                        <div>
                            <label for="item_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Barang / Peralatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="item_name" name="item_name"
                                placeholder="Contoh: AC Split 2 PK, Printer Epson, dsb."
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                required>
                        </div>

                        <!-- Nomor Seri / Kode Inventaris -->
                        <div>
                            <label for="item_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode / Nomor Seri Barang (Jika Ada)
                            </label>
                            <input type="text" id="item_code" name="item_code" placeholder="Contoh: INV-2024-098"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition">
                        </div>

                        <!-- Lokasi / Ruangan -->
                        <div>
                            <label for="item_location" class="block text-sm font-semibold text-gray-700 mb-2">
                                Lokasi / Ruangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="item_location" name="item_location"
                                placeholder="Contoh: Gedung A Lantai 2, Ruang Rapat"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                                required>
                        </div>

                        <!-- Tingkat Urgensi -->
                        <div>
                            <label for="urgency" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tingkat Urgensi <span class="text-red-500">*</span>
                            </label>
                            <select id="urgency" name="urgency"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition bg-white"
                                required>
                                <option value="rendah">Rendah (Peralatan masih bisa berfungsi terbatas)</option>
                                <option value="sedang" selected>Sedang (Menghambat aktivitas tapi tidak darurat)
                                </option>
                                <option value="tinggi">Tinggi (Berhenti total & menghambat pekerjaan)</option>
                                <option value="darurat">Kritis / Darurat (Bahaya keselamatan atau kerusakan parah)
                                </option>
                            </select>
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
                        <label for="damage_description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="damage_description" name="damage_description" rows="4"
                            placeholder="Jelaskan secara detail kendala yang dialami, suara aneh, lampu indikator, atau pesan error yang muncul..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition"
                            required></textarea>
                    </div>
                </div>

                <!-- SEKSI 4: BUKTI FOTO (UPLOAD BOX) -->
                <div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            4
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Lampiran Foto Kerusakan (Opsional)</h2>
                    </div>

                    <div
                        class="border-2 border-dashed border-gray-300 hover:border-primary rounded-2xl p-8 text-center transition bg-gray-50/50 cursor-pointer">
                        <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-3 block"></i>
                        <p class="text-sm font-medium text-gray-700 mb-1">
                            Pilih file foto atau seret ke sini
                        </p>
                        <p class="text-xs text-gray-400">
                            Format yang didukung: JPG, PNG, WEBP (Maksimal 5MB)
                        </p>
                        <input type="file" name="photos[]" multiple accept="image/*"
                            class="mt-4 text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-light cursor-pointer">
                    </div>
                </div>

                <!-- TOMBOL SUBMIT & INFO -->
                <div
                    class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600 text-base"></i>
                        <span>Laporan akan langsung diteruskan ke tim teknisi siaga.</span>
                    </p>

                    <button type="submit"
                        class="w-full sm:w-auto bg-primary hover:bg-tertiary text-white font-bold px-8 py-4 rounded-xl transition duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 text-sm cursor-pointer">
                        {{-- <i class="bi bi-send-fill text-secondary"></i> --}}
                        <span>Kirim Laporan Kerusakan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 border-t border-white/10 mt-12">
        <div
            class="max-w-5xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-purple-200">
            <div class="flex items-center space-x-2">
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
