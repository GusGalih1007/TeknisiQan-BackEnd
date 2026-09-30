<?php

namespace App\Services;

use App\Models\Unit;
use Carbon\Carbon;

class UnitNumberGeneratorService
{
    /**
     * Generate unit number berdasarkan room
     * Format: [ROOM_CODE]-[SEQUENTIAL_NUMBER]
     * Contoh: ROOM-A-001, ROOM-A-002, ROOM-B-001
     * 
     * @param string $roomId
     * @param string $roomName (optional, untuk prefix custom)
     * @return string
     */
    public static function generateByRoom(string $roomId, ?string $roomName = null): string
    {
        // Buat prefix dari room name (ambil 2 huruf pertama, uppercase)
        $prefix = $roomName ? strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $roomName), 0, 2)) : 'RM';
        
        // Cari unit terakhir di ruangan ini
        $lastUnit = Unit::where('roomId', $roomId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Tentukan nomor urutan
        $sequence = 1;
        if ($lastUnit && $lastUnit->unitNumber) {
            // Extract nomor dari format PREFIX-XXX
            preg_match('/(\d+)$/', $lastUnit->unitNumber, $matches);
            if (!empty($matches[1])) {
                $sequence = (int)$matches[1] + 1;
            }
        }
        
        // Format: PREFIX-[PAD 3 DIGITS]
        return sprintf('%s-%03d', $prefix, $sequence);
    }

    /**
     * Generate unit number berdasarkan unit name
     * Format: [UNIT_INITIAL][SEQUENTIAL_NUMBER]
     * Contoh: A001, A002, B001
     * 
     * @param string $unitName
     * @return string
     */
    public static function generateByUnitName(string $unitName): string
    {
        // Ambil huruf pertama dari unit name
        $initial = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $unitName), 0, 1));
        if (empty($initial)) {
            $initial = 'U';
        }
        
        // Cari unit terakhir dengan initial yang sama
        $lastUnit = Unit::where('unitNumber', 'like', $initial . '%')
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Tentukan nomor urutan
        $sequence = 1;
        if ($lastUnit && $lastUnit->unitNumber) {
            preg_match('/(\d+)$/', $lastUnit->unitNumber, $matches);
            if (!empty($matches[1])) {
                $sequence = (int)$matches[1] + 1;
            }
        }
        
        // Format: INITIAL[PAD 3 DIGITS]
        return sprintf('%s%03d', $initial, $sequence);
    }

    /**
     * Generate unit number berdasarkan timestamp/tanggal
     * Format: [YEAR][MONTH][DAY][SEQUENTIAL]
     * Contoh: 20260914001, 20260914002, 20260915001
     * 
     * @param ?Carbon $date
     * @return string
     */
    public static function generateByDate(?Carbon $date = null): string
    {
        if (!$date) {
            $date = Carbon::now();
        }
        
        $datePrefix = $date->format('Ymd');
        
        // Cari unit yang dibuat pada tanggal yang sama
        $lastUnit = Unit::whereDate('created_at', $date->format('Y-m-d'))
            ->where('unitNumber', 'like', $datePrefix . '%')
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Tentukan nomor urutan
        $sequence = 1;
        if ($lastUnit && $lastUnit->unitNumber) {
            preg_match('/(\d+)$/', $lastUnit->unitNumber, $matches);
            if (!empty($matches[1])) {
                $sequence = (int)$matches[1] + 1;
            }
        }
        
        // Format: DATEPREFIX[PAD 3 DIGITS]
        return sprintf('%s%03d', $datePrefix, $sequence);
    }

    /**
     * Generate unit number dengan custom format dan scope
     * 
     * @param string $type 'room' | 'name' | 'date'
     * @param ?string $scopeValue (roomId untuk 'room', unitName untuk 'name', null untuk 'date')
     * @param ?string $additionalValue (roomName untuk 'room')
     * @return string
     */
    public static function generate(
        string $type = 'room',
        ?string $scopeValue = null,
        ?string $additionalValue = null
    ): string {
        return match ($type) {
            'room' => self::generateByRoom($scopeValue, $additionalValue),
            'name' => self::generateByUnitName($scopeValue),
            'date' => self::generateByDate(),
            default => self::generateByRoom($scopeValue, $additionalValue),
        };
    }

    /**
     * Generate unit number otomatis berdasarkan setting atau default
     * 
     * @param array $config Array berisi type, roomId, unitName, roomName
     * @return string
     */
    public static function generateAuto(array $config = []): string
    {
        $type = $config['type'] ?? config('units.number_generator_type', 'room');
        
        return match ($type) {
            'room' => self::generateByRoom(
                $config['roomId'] ?? null,
                $config['roomName'] ?? null
            ),
            'name' => self::generateByUnitName($config['unitName'] ?? 'Unit'),
            'date' => self::generateByDate($config['date'] ?? null),
            default => self::generateByDate(),
        };
    }
}
