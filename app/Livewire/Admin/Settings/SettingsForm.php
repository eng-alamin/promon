<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Settings;

use App\Livewire\Concerns\RequiresSuperAdmin;
use App\Services\Admin\SettingService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SettingsForm extends Component
{
    use RequiresSuperAdmin;

    public string $company_name = '';

    public string $tagline = '';

    public string $whatsapp = '';

    public string $delivery_time = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $working_hours = '';

    public function mount(SettingService $settings): void
    {
        $this->authorizeSuperAdmin();

        $stored = $settings->all();

        foreach (SettingService::KEYS as $key) {
            $this->{$key} = (string) ($stored[$key] ?? '');
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'delivery_time' => ['nullable', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'working_hours' => ['nullable', 'string', 'max:160'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'company_name.required' => 'The company name is required.',
            'whatsapp.regex' => 'Use digits only with the country code, for example +447405124730.',
        ];
    }

    public function save(SettingService $settings): void
    {
        $this->authorizeSuperAdmin();

        foreach (SettingService::KEYS as $key) {
            $this->{$key} = trim($this->{$key});
        }

        // Spaces, dashes and brackets in the WhatsApp number are removed before checking.
        $this->whatsapp = (string) preg_replace('/[\s\-()]/', '', $this->whatsapp);

        $data = $this->validate();
        $settings->save($data);

        $this->dispatch('toast', message: 'Settings saved.');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.settings-form');
    }
}
