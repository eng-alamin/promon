<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingService
{
    /**
     * The settings the admin can edit. Currency is fixed (GBP) and is not editable.
     */
    public const KEYS = [
        'company_name',
        'tagline',
        'whatsapp',
        'delivery_time',
        'email',
        'phone',
        'address',
        'working_hours',
    ];

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        return Setting::query()->pluck('value', 'key')->all();
    }

    /**
     * Save the editable settings. Empty values are saved as NULL.
     *
     * @param  array<string, mixed>  $data
     */
    public function save(array $data): void
    {
        DB::transaction(function () use ($data): void {
            foreach (self::KEYS as $key) {
                $value = isset($data[$key]) ? trim((string) $data[$key]) : '';

                Setting::updateOrCreate(['key' => $key], ['value' => $value === '' ? null : $value]);
            }
        });
    }
}
