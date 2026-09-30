<?php

namespace App\Services;

use App\Models\Unit;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnitQrCodeMail;
use App\Mail\UnitBulkQrCodeMail;
use App\Services\UnitQrCodeService;

class UnitEmailService
{
    /**
     * Kirim email dengan QR Code PDF ke email tertentu
     *
     * @param string $unitId
     * @param string $email
     * @param string $format 'label' | 'certificate' | 'tag'
     * @param ?string $message Pesan custom
     * @return bool
     */
    public static function sendQrCodeEmail(
        string $unitId,
        string $email,
        string $format = 'label',
        ?string $message = null
    ): bool {
        try {
            $unit = Unit::with('room', 'company')->find($unitId);

            if (!$unit) {
                throw new \Exception('Unit tidak ditemukan');
            }

            $pdf = UnitQrCodeService::generatePdf($unitId, $format);

            Mail::send(new UnitQrCodeMail(
                $unit,
                $pdf,
                $format,
                $message
            )->to($email));

            \Log::info("QR Code email sent successfully to {$email} for unit {$unitId}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send QR Code email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email ke multiple penerima
     *
     * @param string $unitId
     * @param array $emails
     * @param string $format
     * @param ?string $message
     * @return array ['success' => count, 'failed' => count]
     */
    public static function sendQrCodeEmailBulk(
        string $unitId,
        array $emails,
        string $format = 'label',
        ?string $message = null
    ): array {
        $result = ['success' => 0, 'failed' => 0];

        foreach ($emails as $email) {
            if (self::sendQrCodeEmail($unitId, $email, $format, $message)) {
                $result['success']++;
            } else {
                $result['failed']++;
            }
        }

        return $result;
    }

    /**
     * Kirim bulk PDF dengan multiple units ke email
     *
     * @param array $unitIds
     * @param string $email
     * @param string $format
     * @param ?string $message
     * @return bool
     */
    public static function sendBulkQrCodeEmail(
        array $unitIds,
        string $email,
        string $format = 'label',
        ?string $message = null
    ): bool {
        try {
            $units = Unit::with('room', 'company')
                ->whereIn('unitId', $unitIds)
                ->get();

            if ($units->isEmpty()) {
                throw new \Exception('Tidak ada unit yang ditemukan');
            }

            $pdfPath = UnitQrCodeService::generateBulkPdf($unitIds, $format);

            Mail::send(new UnitBulkQrCodeMail(
                $units,
                $pdfPath,
                $format,
                $message
            )->to($email));

            \Log::info("Bulk QR Code email sent successfully to {$email}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send bulk QR Code email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send ke email pemilik unit/admin
     *
     * @param string $unitId
     * @param string $format
     * @param ?string $message
     * @return bool
     */
    public static function sendToUnitAdmin(
        string $unitId,
        string $format = 'label',
        ?string $message = null
    ): bool {
        $unit = Unit::with('company.leader')->find($unitId);

        if (!$unit || !$unit->company->leader || !$unit->company->leader->email) {
            \Log::warning("Unit admin email not found for unit {$unitId}");
            return false;
        }

        return self::sendQrCodeEmail(
            $unitId,
            $unit->company->leader->email,
            $format,
            $message
        );
    }
}
