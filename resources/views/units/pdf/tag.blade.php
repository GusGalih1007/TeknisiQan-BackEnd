<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
        }
        .tag-container {
            width: 6cm;
            height: 8cm;
            background: white;
            border: 3px solid #667eea;
            border-radius: 0.5cm;
            padding: 0.4cm;
            margin: 0.5cm auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            page-break-inside: avoid;
        }
        .tag-header {
            text-align: center;
            width: 100%;
        }
        .tag-header h4 {
            font-size: 12px;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .tag-header p {
            font-size: 8px;
            color: #666;
        }
        .tag-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.2cm;
        }
        .qr-code {
            width: 3.5cm;
            height: 3.5cm;
        }
        .tag-footer {
            text-align: center;
            width: 100%;
            border-top: 1px solid #ddd;
            padding-top: 0.3cm;
        }
        .unit-number {
            font-size: 11px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1px;
        }
        .room-name {
            font-size: 8px;
            color: #666;
        }
        @media print {
            .tag-container {
                margin: 0.3cm;
            }
        }
    </style>
</head>
<body>
    <div class="tag-container">
        <div class="tag-header">
            <h4>UNIT TAG</h4>
            <p>{{ config('app.name', 'TeknisiQan') }}</p>
        </div>
        
        <div class="tag-content">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
        </div>
        
        <div class="tag-footer">
            <div class="unit-number">{{ $unit->unitNumber }}</div>
            <div class="room-name">{{ $unit->room->roomName }}</div>
        </div>
    </div>
</body>
</html>
