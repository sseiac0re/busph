<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate basic input (allow 'admin' as email for hardcoded admin)
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
        $email = $validated['email'];
        $password = $validated['password'];
        
        // Check for hardcoded admin credentials
        if ($email === 'admin' && $password === 'admin123') {
            // Find or create the admin user
            $admin = User::firstOrCreate(
                ['email' => 'admin@busph.local'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'email_verified_at' => now(), // Skip email verification for hardcoded admin
                ]
            );
            
            // Update role to admin if user exists but isn't admin
            if ($admin->role !== 'admin') {
                $admin->update(['role' => 'admin']);
            }
            
            // Log in the admin user
            Auth::login($admin, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Redirect to admin dashboard
            return redirect()->route('admin.dashboard');
        }
        
        // For normal users, validate email format
        $request->validate([
            'email' => ['email'],
        ], [
            'email.email' => 'The email must be a valid email address.',
        ]);
        
        // Use LoginRequest for normal authentication
        $loginRequest = LoginRequest::createFrom($request);
        $loginRequest->setContainer(app());
        $loginRequest->authenticate();

        $request->session()->regenerate();

        // Check if user is admin and redirect accordingly
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
