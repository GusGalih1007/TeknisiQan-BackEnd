<!-- Footer Landing Page Teknisi Qan -->
<footer class="bg-primary text-white py-12 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <!-- Brand Info -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                    <i class="bi bi-gear-wide-connected text-secondary text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-wider block leading-none">TEKNISI QAN</span>
                    <span class="text-[10px] text-purple-200 font-light">Sistem Pelaporan Kerusakan Barang</span>
                </div>
            </div>

            <!-- Footer Navigation -->
            <div class="flex items-center space-x-6 text-sm text-purple-200">
                <a href="{{ url('/') }}" class="hover:text-secondary transition">Beranda</a>
                <a href="{{ Route::has('reports.create') ? route('reports.create') : url('/lapor') }}" class="hover:text-secondary transition">Laporkan Kerusakan</a>
                <a href="{{ route('login') }}" class="hover:text-secondary transition">Portal Klien</a>
            </div>

            <!-- Copyright -->
            <div class="text-xs text-purple-300 text-center md:text-right">
                Copyright &copy; 2024 Teknisi Qan. All rights reserved.
            </div>
        </div>
    </div>
</footer>

