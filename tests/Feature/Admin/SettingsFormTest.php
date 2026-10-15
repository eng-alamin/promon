<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Livewire\Admin\Settings\SettingsForm;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));
    }

    public function test_it_loads_the_stored_values(): void
    {
        Setting::create(['key' => 'company_name', 'value' => 'PROMON LTD']);
        Setting::create(['key' => 'whatsapp', 'value' => '+447405124730']);

        Livewire::test(SettingsForm::class)
            ->assertSet('company_name', 'PROMON LTD')
            ->assertSet('whatsapp', '+447405124730')
            ->assertSet('email', '');
    }

    public function test_it_saves_the_settings_and_stores_empty_values_as_null(): void
    {
        Livewire::test(SettingsForm::class)
            ->set('company_name', '  PROMON LTD ')
            ->set('delivery_time', '10 working days')
            ->set('email', 'info@promon.example')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('settings', ['key' => 'company_name', 'value' => 'PROMON LTD']);
        $this->assertDatabaseHas('settings', ['key' => 'delivery_time', 'value' => '10 working days']);
        $this->assertDatabaseHas('settings', ['key' => 'tagline', 'value' => null]);
    }

    public function test_saving_twice_does_not_duplicate_keys(): void
    {
        $component = Livewire::test(SettingsForm::class)->set('company_name', 'PROMON LTD');
        $component->call('save');
        $component->set('company_name', 'PROMON UK')->call('save');

        $this->assertSame(1, Setting::where('key', 'company_name')->count());
        $this->assertDatabaseHas('settings', ['key' => 'company_name', 'value' => 'PROMON UK']);
    }

    public function test_company_name_is_required(): void
    {
        Livewire::test(SettingsForm::class)
            ->set('company_name', '')
            ->call('save')
            ->assertHasErrors(['company_name' => 'required']);
    }

    public function test_the_whatsapp_number_is_cleaned_and_checked(): void
    {
        Livewire::test(SettingsForm::class)
            ->set('company_name', 'PROMON LTD')
            ->set('whatsapp', '+44 7405-124730')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('settings', ['key' => 'whatsapp', 'value' => '+447405124730']);

        Livewire::test(SettingsForm::class)
            ->set('company_name', 'PROMON LTD')
            ->set('whatsapp', 'call me')
            ->call('save')
            ->assertHasErrors(['whatsapp' => 'regex']);
    }

    public function test_the_email_must_be_valid(): void
    {
        Livewire::test(SettingsForm::class)
            ->set('company_name', 'PROMON LTD')
            ->set('email', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }
}
