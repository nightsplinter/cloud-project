<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (null === $user) {
            throw new Exception('Auth user needed');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                route('dashboard', absolute: false)
                . '?verified=1'
            );
        }

        if ($user->markEmailAsVerified()
            && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
            event(new Verified($user));
        }

        return redirect()->intended(
            route('dashboard', absolute: false) . '?verified=1'
        );
    }
}
