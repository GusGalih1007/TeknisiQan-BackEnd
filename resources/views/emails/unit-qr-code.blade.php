@component('mail::message')
# Unit QR Code - {{ $unit->unitName }}

Dear Recipient,

Berikut adalah QR Code untuk unit **{{ $unit->unitNumber }}** ({{ $unit->unitName }}).

**Informasi Unit:**
- **Nomor Unit:** {{ $unit->unitNumber }}
- **Nama Unit:** {{ $unit->unitName }}
- **Ruangan:** {{ $unit->room->roomName }}
- **Instansi:** {{ $unit->company->name }}
- **Format:** {{ ucfirst($format) }}
- **Dibuat:** {{ $unit->created_at->format('d F Y H:i') }}

@if($message)
**Pesan:**
{{ $message }}
@endif

File PDF dengan QR Code terlampir di email ini. Anda dapat mengunduh, mencetak, atau menggunakan sesuai kebutuhan.

@component('mail::button', ['url' => config('app.url')])
Lihat di Sistem
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
