<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'business_name',
        'contact_name',
        'email',
        'phone',
        'whatsapp',
        'city',
        'country',
        'type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CustomerType::class,
        ];
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }
}
