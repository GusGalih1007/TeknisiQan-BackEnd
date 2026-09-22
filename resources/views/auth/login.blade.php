<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Teknisi Qan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans h-screen flex">

    <!-- Sisi Kiri: Branding -->
    <div class="hidden md:flex md:w-1/2 bg-[#5003C0] flex-col justify-center items-center p-10 relative overflow-hidden">
        <!-- Ornamen Background -->
        <div class="absolute w-96 h-96 bg-[#AB03A9] rounded-full blur-3xl opacity-30 -top-20 -left-20"></div>
        <div class="absolute w-96 h-96 bg-[#FFD51E] rounded-full blur-3xl opacity-20 -bottom-20 -right-20"></div>
        
        <div class="z-10 text-center">
            <h1 class="text-5xl font-extrabold text-white mb-4 tracking-wider">TEKNISI QAN</h1>
            <p class="text-purple-200 text-lg">Aplikasi Pelaporan Kerusakan Barang</p>
        </div>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="w-full md:w-1/2 flex justify-center items-center p-8">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang Kembali</h2>
            <p class="text-gray-500 mb-8">Silakan masuk menggunakan akun yang diberikan Superadmin.</p>

            <form action="#" method="POST">
                @csrf
                <!-- Input Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@perusahaan.com" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#5003C0] focus:border-transparent transition">
                </div>

                <!-- Input Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#5003C0] focus:border-transparent transition">
                </div>

                <!-- Tombol Masuk -->
                <button type="submit" 
                        class="w-full bg-[#5003C0] text-white font-bold py-3 rounded-lg hover:bg-[#AB03A9] transition duration-300 shadow-md">
                    Masuk
                </button>
            </form>

            <!-- Info Pendaftaran -->
            <div class="mt-8 text-center bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-600">
                    Belum punya akun? <br> 
                    <span class="font-semibold text-[#AB03A9]">Hubungi Superadmin untuk pendaftaran akun baru.</span>
                </p>
            </div>
        </div>
    </div>

</body>
</html>