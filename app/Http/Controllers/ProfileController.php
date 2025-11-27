<?php

namespace App\Http\Controllers;

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
     * Update profile user.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'program_studi' => 'nullable|string',
            'angkatan' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
                unlink(public_path($user->foto_profil));
            }
            $image = $request->file('foto_profil');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images/profile'), $imageName);
            $validated['foto_profil'] = 'images/profile/'.$imageName;
        }

        $user->update($validated);

        if (!$user->is_profile_complete) {
            $user->update(['is_profile_complete' => true]);
        }

        return redirect()->route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete user.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
