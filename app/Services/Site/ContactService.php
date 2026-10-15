<?php

declare(strict_types=1);

namespace App\Services\Site;

use App\Services\Admin\SettingService;

/**
 * Public contact details. A value saved in the admin Settings page wins;
 * when a setting is empty the PROMON LTD default is used.
 */
class ContactService
{
    public const DEFAULT_COMPANY = 'PROMON LTD';

    public const DEFAULT_EMAIL = 'promonltd@gmail.com';

    public const DEFAULT_WHATSAPP = '+447405124730';

    public function __construct(private readonly SettingService $settings)
    {
    }

    /**
     * @return array{company: string, email: string, whatsapp: string, wa_digits: string}
     */
    public function details(): array
    {
        $all = $this->settings->all();

        $whatsapp = $this->pick($all, 'whatsapp', self::DEFAULT_WHATSAPP);
        $digits = (string) preg_replace('/\D/', '', $whatsapp);

        return [
            'company' => $this->pick($all, 'company_name', self::DEFAULT_COMPANY),
            'email' => $this->pick($all, 'email', self::DEFAULT_EMAIL),
            'whatsapp' => $this->display($digits, $whatsapp),
            'wa_digits' => $digits,
        ];
    }

    /** @param  array<string, string|null>  $all */
    private function pick(array $all, string $key, string $default): string
    {
        $value = trim((string) ($all[$key] ?? ''));

        return $value !== '' ? $value : $default;
    }

    /** UK mobile numbers are shown as +44 7405 124730; anything else is shown as typed. */
    private function display(string $digits, string $original): string
    {
        if (strlen($digits) === 12 && str_starts_with($digits, '44')) {
            return '+44 ' . substr($digits, 2, 4) . ' ' . substr($digits, 6);
        }

        return $original;
    }
}
