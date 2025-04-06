<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;




class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function create()
    {
        $vendor = Vendor::with('user')
            ->where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->get();

        return view('profile.create', compact('vendor'));
    }
    public function show($id): View
    {
        $vendor = Vendor::findOrFail($id);

        return view('profile.show', compact('vendor'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = Auth::user();

            $vendor = Auth::user()->vendor;
            if (!$vendor) {
                throw new \Exception('Vendor record not found for this user.');
            }

            // Update vendor details
            $vendor->update([
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'] ?? null,
                'lastname' => $validated['lastname'],
                'contact_info' => $validated['contact_info'],
                'address' => $validated['address'],
            ]);

            // Reset email verification if email changed
            if ($user->email !== $validated['email']) {
                $user->email_verified_at = null;
            }
            // Update user details
            $user->update([
                'name' => trim("{$validated['firstname']} {$validated['middlename']} {$validated['lastname']}"),
                'email' => $validated['email'],
                // 'role' => $validated['role'],
            ]);

            flash()->success('Profile updated successfully.');
            return redirect()->route('profile.show', Auth::user()->vendor)->with('tab', 'account');
        } catch (\Exception $e) {
            flash()->error('Error updating profile: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function update_profile(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the vendor record instead of user
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return redirect()->back()->withErrors(['error' => 'Vendor profile not found.']);
        }

        // Delete the old profile photo if it exists
        if ($vendor->profile_photo) {
            Storage::delete('public/uploads/' . $vendor->profile_photo);
        }

        // Store the new profile photo
        $file = $request->file('profile_photo');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Save in storage/uploads/
        $file->storeAs('uploads', $filename, 'public');

        // Update vendor profile photo
        $vendor->profile_photo = $filename;
        $vendor->save();

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info('Delete account attempt by user: ' . $request->user()->id);

        $validated = $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete the user
        $user->delete();

        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        flash()->info('User account deleted: ' . $user->id);

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}
