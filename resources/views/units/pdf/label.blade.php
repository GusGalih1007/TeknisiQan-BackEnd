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
        .label-container {
            width: 10cm;
            height: 10cm;
            background: white;
            border: 2px solid #333;
            padding: 0.5cm;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .label-header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.3cm;
        }
        .label-header h3 {
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
        }
        .label-header p {
            font-size: 10px;
            color: #666;
        }
        .label-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3cm;
        }
        .qr-code {
            width: 5cm;
            height: 5cm;
        }
        .label-footer {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 0.3cm;
        }
        .unit-number {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }
        .room-name {
            font-size: 9px;
            color: #666;
            margin-bottom: 1px;
        }
        .company-name {
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="label-header">
            <h3>{{ config('app.name', 'TeknisiQan') }}</h3>
            <p>Unit Label</p>
        </div>
        
        <div class="label-content">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
        </div>
        
        <div class="label-footer">
            <div class="unit-number">{{ $unit->unitNumber }}</div>
            <div class="room-name">{{ $unit->room->roomName }}</div>
            <div class="company-name">{{ $unit->company->name }}</div>
        </div>
    </div>
</body>
</html>
