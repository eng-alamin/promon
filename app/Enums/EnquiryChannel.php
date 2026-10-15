<?php

declare(strict_types=1);

namespace App\Enums;

enum EnquiryChannel: string
{
    case Whatsapp = 'whatsapp';
    case Form = 'form';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Whatsapp => 'WhatsApp',
            self::Form => 'Website form',
            self::Manual => 'Added by admin',
        };
    }
}
