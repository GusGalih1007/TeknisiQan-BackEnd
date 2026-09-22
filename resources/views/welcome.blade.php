<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknisi Qan - Pelaporan Kerusakan Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
        <div class="text-[#5003C0] font-extrabold text-2xl tracking-wider">TEKNISI QAN</div>
        <a href="/login" class="bg-[#5003C0] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#AB03A9] transition duration-300">
            Login Klien
        </a>
    </nav>

    <!-- Hero Section -->
    <header class="max-w-6xl mx-auto mt-16 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-[#5003C0] leading-tight mb-6">
            Solusi Cepat Pelaporan <br> Kerusakan Barang Anda
        </h1>
        <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto">
            Laporkan kerusakan barang Anda secara real-time dan pantau proses perbaikannya dengan mudah melalui platform kami.
        </p>
        <a href="/login" class="bg-[#FFD51E] text-[#5003C0] text-xl font-bold px-10 py-4 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition duration-300 inline-block">
            Masuk ke Dashboard
        </a>
    </header>

    <!-- Cara Kerja Section -->
    <section class="max-w-6xl mx-auto mt-24 px-6 grid md:grid-cols-3 gap-8 mb-20">
        <!-- Card 1 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <div class="w-16 h-16 bg-[#AB03A9] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl">🔍</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">1. Identifikasi</h3>
            <p class="text-gray-500">Temukan barang yang mengalami kerusakan di lokasi Anda.</p>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <div class="w-16 h-16 bg-[#AB03A9] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl">📝</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">2. Buat Laporan</h3>
            <p class="text-gray-500">Isi formulir laporan kerusakan melalui aplikasi ini.</p>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
            <div class="w-16 h-16 bg-[#AB03A9] bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl">🛠️</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">3. Proses Perbaikan</h3>
            <p class="text-gray-500">Teknisi kami akan segera menuju lokasi untuk memperbaiki.</p>
        </div>
    </section>

</body>
</html>