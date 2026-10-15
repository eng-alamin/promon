<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Public address of the file (needs `php artisan storage:link`).
     * asset() follows the current host and folder, so it also works under XAMPP sub folders.
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => asset('storage/' . $this->path));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
