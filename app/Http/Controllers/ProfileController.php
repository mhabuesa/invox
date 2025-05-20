<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    use ImageSaveTrait;
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($user->image)) {
                $this->deleteImage(public_path($user->image));
            }

            // Save new image and get image name
            $image_name = $this->saveImage('profile', $request->file('image'), 400, 400);
        }

        // Update user details
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $image_name ?? $user->image,
        ]);


        return redirect()->route('profile.index')->with('success', 'Profile Updated Successfully');
    }

    /**
     * Update the user's password.
     */
    public function password(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Get the user (profile)
        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.index')->with('active_tab', 'password')->with('error', 'Current Password does not match');
        }

        // Update the password
        User::find($user->id)->update(['password' => Hash::make($request->password)]);

        // Redirect with success message
        return redirect()->route('profile.index')->with('active_tab', 'password')->with('success', 'Password Updated Successfully');
    }
}
