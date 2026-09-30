@component('mail::message')
# Bulk Unit QR Codes - {{ $count }} Units

Dear Recipient,

Berikut adalah file PDF berisi QR Code untuk **{{ $count }} units** dalam format **{{ ucfirst($format) }}**.

**Ringkasan Units:**
@foreach($units as $unit)
- {{ $unit->unitNumber }} - {{ $unit->unitName }} ({{ $unit->room->roomName }})
@endforeach

@if($message)
**Pesan:**
{{ $message }}
@endif

File PDF terlampir dapat langsung dicetak atau digunakan sesuai kebutuhan Anda.

@component('mail::button', ['url' => config('app.url')])
Lihat di Sistem
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
