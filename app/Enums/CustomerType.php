<?php

declare(strict_types=1);

namespace App\Enums;

enum CustomerType: string
{
    case Retailer = 'retailer';
    case Wholesaler = 'wholesaler';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Retailer => 'Retailer',
            self::Wholesaler => 'Wholesaler',
            self::Other => 'Other',
        };
    }
}
