<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display profile completion form (for incomplete profiles)
     */
    public function completeProfile(Request $request)
    {
        $user = $request->user();
        
        // Jika profil sudah lengkap, redirect ke dashboard
        if ($user->is_profile_complete) {
            return Redirect::route('dashboard')->with('status', 'profile-already-complete');
        }

        return view('profile.complete', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Validation untuk profile completion
        if (!$user->is_profile_complete) {
            $validated = $request->validate([
                'nim' => 'required|string|unique:users,nim,' . $user->id,
                'no_telp' => 'required|string|max:20',
                'jenis_kelamin' => 'required|in:L,P',
                'program_studi' => 'nullable|string',
                'angkatan' => 'nullable|string',
                'alamat' => 'nullable|string',
            ]);

            // Update user data
            $user->update($validated);
            
            // Mark profile as complete
            $user->update(['is_profile_complete' => true]);

            return Redirect::route('dashboard')->with('status', 'profile-completed');
        }

        // Validation untuk profile update biasa
        $validated = $request->validate([
            'nim' => 'sometimes|string|unique:users,nim,' . $user->id,
            'no_telp' => 'sometimes|string|max:20',
            'program_studi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
