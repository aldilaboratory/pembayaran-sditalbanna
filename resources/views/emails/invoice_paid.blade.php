{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}
@component('mail::message')
# Pembayaran Diterima

Halo {{ $t->student->nama }},

Pembayaran **{{ strtoupper($t->schoolFee?->jenis_tagihan ?? 'SPP') }}**
tahun ajaran **{{ strtoupper($t->schoolFee?->academicYear->academic_year ?? 'SPP') }}**
periode **{{ $t->schoolFee?->nama_bulan ?? '-' }}** telah kami terima.

- Invoice: **{{ $t->invoice_code }}**
- Jumlah: **Rp{{ number_format($t->jumlah,0,',','.') }}**

Invoice PDF terlampir pada email ini.

Terima kasih,  
**SDIT Albanna**
@endcomponent
