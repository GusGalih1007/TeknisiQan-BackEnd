<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR {{ $unit->unitNumber }}</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; color: #222; background: #f3f4f6; }
        .toolbar { padding: 16px; text-align: center; }
        .toolbar button, .toolbar a { display: inline-block; margin: 4px; padding: 10px 18px; border: 0; border-radius: 8px; text-decoration: none; cursor: pointer; }
        .print { background: #5003c0; color: #fff; }
        .back { background: #fff; color: #333; }
        .label { width: 320px; margin: 0 auto 24px; padding: 24px; box-sizing: border-box; background: #fff; border: 2px solid #5003c0; border-radius: 16px; text-align: center; }
        .qr-code { display: flex; justify-content: center; }
        .qr-code svg { width: 220px; height: 220px; }
        .number { margin: 12px 0 6px; color: #5003c0; font-size: 24px; font-weight: 700; }
        .name { font-size: 18px; font-weight: 700; }
        .meta { margin-top: 8px; color: #666; font-size: 13px; }
        @page {
            size: 85mm 100mm;
            margin: 10mm;
        }

        @media print {
            html,
            body {
                width: 65mm;
                height: 80mm;
                margin: 0;
                padding: 0;
                background: #fff;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .toolbar {
                display: none;
            }

            .label {
                width: 65mm;
                height: 80mm;
                margin: 0;
                padding: 3mm;
                border-radius: 4mm;
                page-break-inside: avoid;
            }

            .qr-code svg {
                width: 48mm;
                height: 48mm;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a class="back" href="{{ route('units.index') }}">Kembali</a>
        <button class="print" type="button" onclick="window.print()">Cetak QR</button>
    </div>
    <main class="label">
        @if ($qrCode)
            <div class="qr-code" role="img" aria-label="QR Code {{ $unit->unitNumber }}">{!! $qrCode !!}</div>
        @else
            <p>QR Code gagal dibuat.</p>
        @endif
        <div class="number">{{ $unit->unitNumber }}</div>
        <div class="name">{{ $unit->unitName }}</div>
        <div class="meta">{{ $unit->room?->roomName ?? '-' }} &bull; {{ $unit->company?->name ?? '-' }}</div>
    </main>
</body>
</html>