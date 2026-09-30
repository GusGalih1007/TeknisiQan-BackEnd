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
        }
        .certificate {
            width: 21cm;
            height: 29.7cm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2cm;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            margin: 0 auto;
        }
        .cert-header {
            border: 3px solid white;
            padding: 1cm;
            border-radius: 10px;
            width: 100%;
        }
        .cert-header h1 {
            font-size: 36px;
            margin-bottom: 0.5cm;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .cert-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .cert-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1cm;
            width: 100%;
        }
        .qr-section {
            background: white;
            padding: 1cm;
            border-radius: 10px;
        }
        .qr-code {
            width: 4cm;
            height: 4cm;
        }
        .unit-info {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 1cm 2cm;
            border-radius: 10px;
            width: 100%;
        }
        .unit-info h2 {
            font-size: 24px;
            margin-bottom: 0.5cm;
        }
        .info-row {
            display: flex;
            justify-content: space-around;
            gap: 2cm;
            margin: 0.5cm 0;
            font-size: 14px;
        }
        .cert-footer {
            width: 100%;
            border-top: 2px solid white;
            padding-top: 0.5cm;
            font-size: 12px;
        }
        .cert-footer p {
            margin: 0.3cm 0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="cert-header">
            <h1>Unit Certificate</h1>
            <p>{{ config('app.name', 'Teknisi Qan') }}</p>
        </div>
        
        <div class="cert-content">
            <div class="qr-section">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
            </div>
            
            <div class="unit-info">
                <h2>{{ $unit->unitNumber }}</h2>
                <div class="info-row">
                    <div>
                        <strong>Ruangan:</strong><br>
                        {{ $unit->room->roomName }}
                    </div>
                    <div>
                        <strong>Instansi:</strong><br>
                        {{ $unit->company->name }}
                    </div>
                </div>
                <div class="info-row">
                    <div style="flex: 1;">
                        <strong>Unit Name:</strong><br>
                        {{ $unit->unitName }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cert-footer">
            <p>Dibuat: {{ $unit->created_at->format('d F Y H:i') }}</p>
            <p>ID: {{ $unit->unitId }}</p>
        </div>
    </div>
</body>
</html>
