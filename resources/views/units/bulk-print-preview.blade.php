<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $unitsData->count() }} QR Unit</title>
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; font-family: Arial, sans-serif; color: #222; background: #f3f4f6; }
        .toolbar { position: sticky; top: 0; z-index: 10; padding: 14px; text-align: center; background: rgba(243, 244, 246, .95); }
        .toolbar button, .toolbar a { display: inline-block; margin: 4px; padding: 10px 18px; border: 0; border-radius: 8px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .print { background: #5003c0; color: #fff; }
        .back { background: #fff; color: #333; }
        .sheet { width: 210mm; min-height: 297mm; margin: 12px auto; padding: 10mm; background: #fff; display: grid; grid-template-columns: repeat(3, 1fr); grid-auto-rows: 64mm; gap: 5mm; align-content: start; }
        .label { min-width: 0; padding: 3mm; border: .5mm solid #5003c0; border-radius: 3mm; text-align: center; break-inside: avoid; overflow: hidden; }
        .qr-code { display: flex; justify-content: center; }
        .qr-code svg { width: 42mm; height: 42mm; }
        .number { margin-top: 1.5mm; color: #5003c0; font-size: 14pt; font-weight: 700; line-height: 1.1; }
        .name { margin-top: 1mm; font-size: 10pt; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .meta { margin-top: 1mm; color: #666; font-size: 7pt; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .qr-error { height: 42mm; display: flex; align-items: center; justify-content: center; color: #b91c1c; font-size: 9pt; }

        @page { size: A4 portrait; margin: 10mm; }
        @media print {
            html, body { width: 210mm; background: #fff; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .toolbar { display: none; }
            .sheet { width: 190mm; min-height: 277mm; margin: 0; padding: 0; gap: 5mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a class="back" href="{{ route('units.index') }}">Kembali</a>
        <button class="print" type="button" onclick="window.print()">Cetak A4 ({{ $unitsData->count() }} QR)</button>
    </div>

    <main class="sheet">
        @foreach ($unitsData as $unitData)
            <article class="label">
                @if ($unitData['qrCode'])
                    <div class="qr-code" role="img" aria-label="QR Code {{ $unitData['unit']->unitNumber }}">{!! $unitData['qrCode'] !!}</div>
                @else
                    <div class="qr-error">QR gagal dibuat</div>
                @endif
                <div class="number">{{ $unitData['unit']->unitNumber }}</div>
                <div class="name">{{ $unitData['unit']->unitName }}</div>
                <div class="meta">{{ $unitData['unit']->room?->roomName ?? '-' }} &bull; {{ $unitData['unit']->company?->name ?? '-' }}</div>
            </article>
        @endforeach
    </main>
</body>
</html>