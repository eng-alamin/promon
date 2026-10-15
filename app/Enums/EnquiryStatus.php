<?php

declare(strict_types=1);

namespace App\Enums;

enum EnquiryStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Quoted = 'quoted';
    case Confirmed = 'confirmed';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Contacted => 'Contacted',
            self::Quoted => 'Quoted',
            self::Confirmed => 'Confirmed',
            self::Closed => 'Closed',
        };
    }
}
