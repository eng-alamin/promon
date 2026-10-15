<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && ! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Thrown (not returned) so the request is also stopped when Livewire runs this middleware.
            throw new HttpResponseException(
                redirect()
                    ->route('admin.login')
                    ->withErrors(['email' => 'Your account is inactive. Please contact the administrator.'])
            );
        }

        return $next($request);
    }
}
