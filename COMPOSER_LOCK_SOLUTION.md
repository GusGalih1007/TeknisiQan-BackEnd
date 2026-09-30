# Solusi Composer Lock Issue

## Masalah
File terkunci oleh antivirus/Windows Search Indexer saat install `simplesoftwareio/simple-qrcode`

## Solusi - Pilih Salah Satu:

### **Solusi 1: Disable Antivirus Sementara (Paling Cepat)**
1. Disable Windows Defender/Antivirus sementara
2. Buka cmd/PowerShell sebagai Administrator
3. Jalankan ulang composer update
4. Enable kembali antivirus setelah selesai

### **Solusi 2: Exclude Folder dari Antivirus**
1. Buka Windows Defender
2. Go to: Settings > Virus & threat protection > Manage settings
3. Add `C:\laragon\www\LaporTeknisiBackend` ke Exclusions
4. Jalankan ulang composer

### **Solusi 3: Disable Windows Search Indexing**
1. Services.msc
2. Cari "Windows Search"
3. Disable service
4. Jalankan ulang composer
5. Enable kembali setelah selesai

### **Solusi 4: Menggunakan Alternative QR Code Library**
Jika masih bermasalah, gunakan package lain yang lebih ringan:

```bash
composer require endroid/qr-code
```

## Step-by-Step Implementasi

### Jika menggunakan `endroid/qr-code`:
Update file UnitQrCodeService.php dengan implementasi baru (lihat file service-update.md)

### Testing
```bash
php artisan tinker
>>> App\Services\UnitNumberGeneratorService::generateByRoom('room-id', 'Room Name')
>>> App\Services\UnitQrCodeService::generateQrCode('unit-id')
```

## Rekomendasi Terakhir
Coba Solusi 2 (Exclude Folder) atau Solusi 4 (Alternative Package) yang paling aman dan tidak perlu restart services.
