<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Only real, known details are filled. Empty values stay empty (nothing is invented).
        $settings = [
            'company_name' => 'PROMON LTD',
            'tagline' => 'Bringing You the Best',
            'whatsapp' => '+447405124730',
            'delivery_time' => '10 working days',
            'currency' => 'GBP',
            'email' => 'promonltd@gmail.com',
            'phone' => '',
            'address' => '1A Wilmot Street, Bolton, Manchester, BL1 3LL, United Kingdom',
            'working_hours' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
