<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('status', 'Please log in to verify your email.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    /**
     * Public verification that does not require an active session.
     */
    public function publicVerify(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->route('login')->with('status', 'Verification link is invalid.');
        }

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('status', 'Verification link is invalid or expired.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Email already verified. Please log in.');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        // Redirect to verification complete page (with auto-redirect to login)
        return redirect()->route('verification.completed');
    }
}
