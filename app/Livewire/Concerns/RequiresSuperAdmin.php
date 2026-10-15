<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait RequiresSuperAdmin
{
    /**
     * Stops the action unless a super admin is logged in. Called inside every sensitive
     * Livewire action, so it holds even if the route middleware is somehow skipped.
     */
    protected function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin() === true, 403);
    }
}
