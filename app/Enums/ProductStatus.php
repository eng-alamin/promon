<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case InStock = 'in_stock';
    case AdvanceOrder = 'advance_order';
    case Unavailable = 'unavailable';

    public function label(): string
    {
        return match ($this) {
            self::InStock => 'In stock',
            self::AdvanceOrder => 'Advance order',
            self::Unavailable => 'Unavailable',
        };
    }
}
