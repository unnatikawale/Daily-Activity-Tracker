<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $userId = $request->user()->id;
        
        // Get fresh user data directly from database
        $user = \DB::table('users')->where('id', $userId)->first();
        
        // Convert to object for view compatibility
        $userObject = (object) [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $user->profile_photo,
        ];
        
        return view('profile.edit', [
            'user' => $userObject,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        
        // Prepare update data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Get current photo before update
            $currentPhoto = DB::table('users')->where('id', $user->id)->value('profile_photo');
            
            // Delete old photo if exists
            if ($currentPhoto) {
                Storage::disk('public')->delete($currentPhoto);
            }
            
            // Store new photo with unique name
            $fileName = time() . '_' . $request->file('profile_photo')->getClientOriginalName();
            $photoPath = $request->file('profile_photo')->storeAs('profile-photos', $fileName, 'public');
            $updateData['profile_photo'] = $photoPath;
        }
        
        // Handle photo removal
        if ($request->boolean('remove_photo')) {
            // Get current photo before update
            $currentPhoto = DB::table('users')->where('id', $user->id)->value('profile_photo');
            
            if ($currentPhoto) {
                Storage::disk('public')->delete($currentPhoto);
            }
            $updateData['profile_photo'] = null;
        }
        
        // Handle email verification reset
        if ($user->email !== $validated['email']) {
            $updateData['email_verified_at'] = null;
        }
        
        // Update using direct database query
        DB::table('users')->where('id', $user->id)->update($updateData);
        
        // DEBUG: Log what was updated
        Log::info('User updated. Data: ' . json_encode($updateData));

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
