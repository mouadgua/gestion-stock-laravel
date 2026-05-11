<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleSocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'La connexion avec Google a échoué. Veuillez réessayer.');
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'Impossible de récupérer les informations Google. Veuillez réessayer.');
        }

        // Check if user already exists with this Google email
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            $updates = [];
            if (empty($existingUser->google_id)) {
                $updates['google_id'] = $googleUser->getId();
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (is_null($existingUser->email_verified_at)) {
                $updates['email_verified_at'] = now();
            }
            if (!empty($updates)) {
                $existingUser->update($updates);
            }

            Auth::login($existingUser);
            $request->session()->regenerate();

            if ($existingUser->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        // Create a new user — Google already verifies email, so mark it as verified
        $user = User::create([
            'name' => $googleUser->getName() ?? $googleUser->getEmail(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'password' => Hash::make(bin2hex(random_bytes(16))),
            'role' => 'acheteur',
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
