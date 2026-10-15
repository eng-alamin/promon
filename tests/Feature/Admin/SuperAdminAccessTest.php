<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Livewire\Admin\Settings\SettingsForm;
use App\Livewire\Admin\Users\UserTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.settings.edit'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('admin.login'));
    }

    public function test_staff_cannot_open_settings_or_users(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Staff]));

        $this->get(route('admin.settings.edit'))->assertForbidden();
        $this->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_a_super_admin_can_open_both_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));

        $this->get(route('admin.settings.edit'))->assertOk()->assertSeeLivewire(SettingsForm::class);
        $this->get(route('admin.users.index'))->assertOk()->assertSeeLivewire(UserTable::class);
    }

    public function test_staff_can_still_use_the_other_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Staff]));

        $this->get(route('admin.customers.index'))->assertOk();
        $this->get(route('admin.faqs.index'))->assertOk();
        $this->get(route('admin.enquiries.index'))->assertOk();
    }

    public function test_the_menu_shows_users_and_settings_only_to_a_super_admin(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]))
            ->get(route('admin.dashboard'))
            ->assertSee(route('admin.users.index'))
            ->assertSee(route('admin.settings.edit'));

        $this->actingAs(User::factory()->create(['role' => UserRole::Staff]))
            ->get(route('admin.dashboard'))
            ->assertDontSee(route('admin.users.index'))
            ->assertDontSee(route('admin.settings.edit'));
    }
}
