<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Livewire sends actions to /livewire/update, not to the original page route.
        // Without this line a deactivated user could keep using an already open page.
        Livewire::addPersistentMiddleware([
            EnsureUserIsActive::class,
            EnsureUserIsSuperAdmin::class,
        ]);
    }
}
