<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, CloudinaryService $cloudinary)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif,avif', 'max:5120'],
        ]);

        // Remove avatar from validated — handled separately to avoid storing UploadedFile as string
        unset($validated['avatar']);

        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $result = $cloudinary->upload($request->file('avatar'), 'avatars');
            if ($result) {
                // Delete old avatar from Cloudinary if it exists
                if ($user->avatar && str_contains($user->avatar, 'cloudinary')) {
                    $oldPublicId = $cloudinary->getPublicIdFromUrl($user->avatar);
                    if ($oldPublicId) {
                        $cloudinary->delete($oldPublicId);
                    }
                }
                $user->avatar = $result['url'];
            } else {
                return back()->withErrors(['avatar' => 'Échec de l\'upload de l\'avatar. Vérifiez les credentials Cloudinary.'])
                    ->withInput();
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}