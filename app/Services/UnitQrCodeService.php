<?php

namespace App\Services;

use App\Models\Unit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class UnitQrCodeService
{
    /**
     * Generate QR Code untuk unit
     * Data QR: unitId
     * 
     * @param string $unitId
     * @param int $size (dalam pixels)
     * @return string Base64 encoded image
     */
    public static function generateQrCode(string $unitId, int $size = 300): string
    {
        try {
            $qrCode = QrCode::size($size)
                ->format('png')
                ->errorCorrection('H')
                ->encoding('UTF-8')
                ->generate($unitId);
            
            return base64_encode($qrCode);
        } catch (\Exception $e) {
            \Log::error('QR Code Generation Error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Generate QR Code SVG untuk ditampilkan di browser tanpa memerlukan Imagick.
     */
    public static function generateQrCodeSvg(string $unitId, int $size = 300): string
    {
        try {
            return QrCode::size($size)
                ->format('svg')
                ->errorCorrection('H')
                ->encoding('UTF-8')
                ->generate($unitId);
        } catch (Throwable $exception) {
            report($exception);

            return '';
        }
    }

    /**
     * Generate QR Code dan simpan ke storage
     * 
     * @param string $unitId
     * @param int $size
     * @return string File path
     */
    public static function generateAndSaveQrCode(string $unitId, int $size = 300): string
    {
        try {
            $qrCode = QrCode::size($size)
                ->format('png')
                ->errorCorrection('H')
                ->generate($unitId);
            
            $fileName = "qr-codes/{$unitId}.png";
            Storage::disk('public')->put($fileName, $qrCode);
            
            return $fileName;
        } catch (\Exception $e) {
            \Log::error('QR Code Save Error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Generate label dengan QR Code (untuk print)
     * 
     * @param string $unitId
     * @return string HTML/SVG untuk label
     */
    public static function generateLabel(string $unitId): string
    {
        $unit = Unit::with('room', 'company')->find($unitId);
        
        if (!$unit) {
            return '';
        }
        
        $qrBase64 = self::generateQrCode($unitId, 200);
        
        return view('units.components.label', [
            'unit' => $unit,
            'qrCode' => $qrBase64
        ])->render();
    }

    /**
     * Generate PDF dengan QR Code
     * 
     * @param string $unitId
     * @param string $format 'label' | 'certificate' | 'tag'
     * @return \Barryvdh\DomPDF\PDF
     */
    public static function generatePdf(string $unitId, string $format = 'label')
    {
        $unit = Unit::with('room', 'company')->find($unitId);
        
        if (!$unit) {
            throw new \Exception('Unit tidak ditemukan');
        }
        
        $qrBase64 = self::generateQrCode($unitId, 200);
        
        $view = match ($format) {
            'certificate' => 'units.pdf.certificate',
            'tag' => 'units.pdf.tag',
            'label' => 'units.pdf.label',
            default => 'units.pdf.label',
        };
        
        return Pdf::loadView($view, [
            'unit' => $unit,
            'qrCode' => $qrBase64
        ])
        ->setPaper('A4', 'portrait')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10);
    }

    /**
     * Download PDF
     * 
     * @param string $unitId
     * @param string $format
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function downloadPdf(string $unitId, string $format = 'label')
    {
        $pdf = self::generatePdf($unitId, $format);
        $unit = Unit::find($unitId);
        
        return $pdf->download("Unit-{$unit->unitName}-{$format}.pdf");
    }

    /**
     * Generate multiple QR Codes untuk bulk units
     * 
     * @param array $unitIds
     * @param string $format
     * @return string PDF file path
     */
    public static function generateBulkPdf(array $unitIds, string $format = 'label'): string
    {
        $units = Unit::with('room', 'company')
            ->whereIn('unitId', $unitIds)
            ->get();
        
        if ($units->isEmpty()) {
            throw new \Exception('Tidak ada unit yang ditemukan');
        }
        
        $unitsData = $units->map(function ($unit) {
            return [
                'unit' => $unit,
                'qrCode' => self::generateQrCode($unit->unitId, 200)
            ];
        })->toArray();
        
        $pdf = Pdf::loadView('units.pdf.bulk', [
            'units' => $unitsData,
            'format' => $format
        ])->setPaper('A4', 'portrait')
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10);
        
        $fileName = "bulk-units-" . Str::slug(now()->format('Y-m-d H:i:s')) . ".pdf";
        Storage::disk('public')->put("pdfs/{$fileName}", $pdf->output());
        
        return "pdfs/{$fileName}";
    }

    /**
     * Generate QR Code image tag untuk display
     * 
     * @param string $unitId
     * @param int $size
     * @return string HTML img tag
     */
    public static function getQrImageTag(string $unitId, int $size = 200): string
    {
        $qrBase64 = self::generateQrCode($unitId, $size);
        return "<img src='data:image/png;base64,{$qrBase64}' alt='QR Code' style='width:{$size}px; height:{$size}px;'>";
    }
}
