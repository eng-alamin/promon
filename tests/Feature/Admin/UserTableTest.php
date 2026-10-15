<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Livewire\Admin\Users\UserTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserTableTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::SuperAdmin, 'name' => 'Boss']);
        $this->actingAs($this->admin);
    }

    public function test_it_creates_a_user_with_a_hashed_password(): void
    {
        Livewire::test(UserTable::class)
            ->call('create')
            ->set('name', 'Karim Staff')
            ->set('email', '  Karim@Example.COM ')
            ->set('password', 'secret-pass-1')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $user = User::where('email', 'karim@example.com')->firstOrFail()->fresh();

        $this->assertSame(UserRole::Staff, $user->role);
        $this->assertTrue($user->is_active);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('secret-pass-1', $user->password));
    }

    public function test_a_password_is_required_for_a_new_user_and_must_be_long_enough(): void
    {
        Livewire::test(UserTable::class)
            ->call('create')
            ->set('name', 'Karim Staff')
            ->set('email', 'karim@example.com')
            ->call('save')
            ->assertHasErrors(['password' => 'required'])
            ->set('password', 'short')
            ->call('save')
            ->assertHasErrors(['password' => 'min']);

        $this->assertDatabaseMissing('users', ['email' => 'karim@example.com']);
    }

    public function test_the_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::test(UserTable::class)
            ->call('create')
            ->set('name', 'Karim Staff')
            ->set('email', 'taken@example.com')
            ->set('password', 'secret-pass-1')
            ->call('save')
            ->assertHasErrors(['email' => 'unique']);
    }

    public function test_editing_keeps_the_password_when_none_is_given(): void
    {
        $staff = User::factory()->create(['password' => 'old-password-1']);
        $oldHash = $staff->password;

        Livewire::test(UserTable::class)
            ->call('edit', $staff->id)
            ->assertSet('password', '')
            ->set('name', 'New Name')
            ->call('save')
            ->assertHasNoErrors();

        $staff->refresh();

        $this->assertSame('New Name', $staff->name);
        $this->assertSame($oldHash, $staff->password);
    }

    public function test_editing_can_set_a_new_password(): void
    {
        $staff = User::factory()->create(['password' => 'old-password-1']);

        Livewire::test(UserTable::class)
            ->call('edit', $staff->id)
            ->set('password', 'brand-new-pass')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('brand-new-pass', $staff->fresh()->password));
    }

    public function test_it_changes_the_role_and_toggles_active_for_another_user(): void
    {
        $staff = User::factory()->create();

        Livewire::test(UserTable::class)
            ->call('edit', $staff->id)
            ->set('user_role', 'super_admin')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(UserRole::SuperAdmin, $staff->fresh()->role);

        Livewire::test(UserTable::class)->call('toggleActive', $staff->id);

        $this->assertFalse($staff->fresh()->is_active);
    }

    public function test_the_role_must_exist(): void
    {
        Livewire::test(UserTable::class)
            ->call('create')
            ->set('name', 'Karim Staff')
            ->set('email', 'karim@example.com')
            ->set('password', 'secret-pass-1')
            ->set('user_role', 'owner')
            ->call('save')
            ->assertHasErrors(['user_role']);
    }

    public function test_nobody_can_remove_their_own_access(): void
    {
        Livewire::test(UserTable::class)
            ->call('edit', $this->admin->id)
            ->set('user_role', 'staff')
            ->call('save')
            ->assertDispatched('toast')
            ->assertSet('showForm', true);

        Livewire::test(UserTable::class)
            ->call('edit', $this->admin->id)
            ->set('is_active', false)
            ->call('save')
            ->assertDispatched('toast');

        Livewire::test(UserTable::class)
            ->call('toggleActive', $this->admin->id)
            ->assertDispatched('toast');

        $this->admin->refresh();

        $this->assertSame(UserRole::SuperAdmin, $this->admin->role);
        $this->assertTrue($this->admin->is_active);
    }

    public function test_a_super_admin_can_edit_their_own_name(): void
    {
        Livewire::test(UserTable::class)
            ->call('edit', $this->admin->id)
            ->set('name', 'Boss Two')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Boss Two', $this->admin->fresh()->name);
    }

    public function test_it_searches_by_name_and_email(): void
    {
        User::factory()->create(['name' => 'Rahim Staff', 'email' => 'rahim@example.com']);
        User::factory()->create(['name' => 'Karim Staff', 'email' => 'karim@example.com']);

        Livewire::test(UserTable::class)
            ->set('search', 'rahim')
            ->assertSee('Rahim Staff')
            ->assertDontSee('Karim Staff');
    }
}
