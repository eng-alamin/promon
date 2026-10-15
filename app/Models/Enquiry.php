<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    protected $fillable = [
        'reference',
        'customer_id',
        'handled_by',
        'channel',
        'status',
        'message',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'channel' => EnquiryChannel::class,
            'status' => EnquiryStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Enquiry $enquiry): void {
            if (empty($enquiry->reference)) {
                $enquiry->reference = 'ENQ-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * The categories the customer asked about, in the same order as the website.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->ordered();
    }
}
