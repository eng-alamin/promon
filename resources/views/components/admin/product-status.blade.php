@props(['status'])

@php
    $class = match ($status) {
        \App\Enums\ProductStatus::InStock => 'ok',
        \App\Enums\ProductStatus::AdvanceOrder => 'warn',
        default => '',
    };
@endphp

<span {{ $attributes->class(['pill', $class]) }}>{{ $status->label() }}</span>
