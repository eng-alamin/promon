<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\User;
use DomainException;

class UserService
{
    /**
     * Create a user, or update it when one is given.
     * The password is changed only when a new one is given.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws DomainException when the actor tries to lock themselves out
     */
    public function save(?User $user, array $data, User $actor): User
    {
        $role = UserRole::from((string) $data['user_role']);
        $isActive = (bool) $data['is_active'];

        if ($user !== null && $user->is($actor)) {
            $this->guardSelf($role, $isActive);
        }

        $payload = [
            'name' => trim((string) $data['name']),
            'email' => mb_strtolower(trim((string) $data['email'])),
            'phone' => $this->nullable($data['phone'] ?? null),
            'role' => $role,
            'is_active' => $isActive,
        ];

        if (($data['password'] ?? '') !== '') {
            $payload['password'] = (string) $data['password'];
        }

        if ($user === null) {
            $payload['email_verified_at'] = now();

            return User::create($payload);
        }

        $user->update($payload);

        return $user;
    }

    /**
     * @throws DomainException when the actor tries to deactivate themselves
     */
    public function toggleActive(User $user, User $actor): void
    {
        if ($user->is($actor)) {
            $this->guardSelf($user->role, ! $user->is_active);
        }

        $user->update(['is_active' => ! $user->is_active]);
    }

    /**
     * Nobody can remove their own access. Because the person acting is always an active
     * super admin, this also keeps at least one active super admin in the system.
     */
    private function guardSelf(UserRole $role, bool $isActive): void
    {
        if ($role !== UserRole::SuperAdmin) {
            throw new DomainException('You cannot change your own role. Ask another super admin.');
        }

        if (! $isActive) {
            throw new DomainException('You cannot deactivate your own account.');
        }
    }

    private function nullable(mixed $value): ?string
    {
        $text = $value === null ? '' : trim((string) $value);

        return $text === '' ? null : $text;
    }
}
