<x-mail::message>
# Introduction

Thank you for your order.

**Invoice ID:** {{ $invoice->id }}
**Amount:** {{ $invoice->total }}

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
