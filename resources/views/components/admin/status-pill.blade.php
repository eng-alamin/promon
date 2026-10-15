@props(['status'])

@php
    $class = match ($status) {
        \App\Enums\EnquiryStatus::New => 'warn',
        \App\Enums\EnquiryStatus::Contacted, \App\Enums\EnquiryStatus::Quoted => 'info',
        \App\Enums\EnquiryStatus::Confirmed => 'ok',
        default => '',
    };
@endphp

<span {{ $attributes->class(['pill', $class]) }}>{{ $status->label() }}</span>
