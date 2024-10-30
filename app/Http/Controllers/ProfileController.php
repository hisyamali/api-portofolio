<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
// use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'profession' => 'required',
            'experience' => 'required',
            'year_experience' => 'required',
            'project' => 'required',
            'max_project' => 'required',
            'description' => 'required',
            'images1' => 'nullable|image|file|max:5130',
            'images2' => 'nullable|image|file|max:5130',
            'images3' => 'nullable|image|file|max:5130',
        ], [
            'profession.required' => 'Profession is required',
            'experience.required' => 'Experience is required',
            'year_experience.required' => 'Year of experience is required',
            'project.required' => 'Project is required',
            'max_project.required' => 'Year of project is required',
            'description.required' => 'Description is required',
            'images1.image' => 'The image must be an image',
            'images1.file' => 'The image must be a file',
            'images1.max' => 'The image must not be greater than 5 MB',
            'images2.image' => 'The image must be an image',
            'images2.file' => 'The image must be a file',
            'images2.max' => 'The image must not be greater than 5 MB',
            'images3.image' => 'The image must be an image',
            'images3.file' => 'The image must be a file',
            'images3.max' => 'The image must not be greater than 5 MB',
        ]);

        $newProfile = new Profile();
        $newProfile->profession = $request->profession;
        $newProfile->experience = $request->experience;
        $newProfile->year_experience = $request->year_experience;
        $newProfile->project = $request->project;
        $newProfile->max_project = $request->max_project;
        $newProfile->description = $request->description;
        if ($request->hasFile('images1')) {
            $newProfile->images1 = $request->file('images1')->store('/profile/images1/img', 'public');
        }
        if ($request->hasFile('images2')) {
            $newProfile->images2 = $request->file('images2')->store('/profile/images2/img', 'public');
        }
        if ($request->hasFile('images3')) {
            $newProfile->images3 = $request->file('images3')->store('/profile/images3/img', 'public');
        }
        $newProfile->save();
        return response()->json([
            'message' => 'Profile updated successfully.'
        ]);
    }

    public function getProfile()
    {
        //ambil profile terakhir
        $profile = Profile::latest()->first();
        return response()->json([
            'data' => $profile
        ]);
    }

    public function updateProfile(Request $request, $profileId)
    {
        $request->validate([
            'profession' => 'required',
            'experience' => 'required',
            'year_experience' => 'required',
            'project' => 'required',
            'max_project' => 'required',
            'description' => 'required',
            'images1' => 'nullable|image|file|max:5130',
            'images2' => 'nullable|image|file|max:5130',
            'images3' => 'nullable|image|file|max:5130',
        ], [
            'profession.required' => 'Profession is required',
            'experience.required' => 'Experience is required',
            'year_experience.required' => 'Year of experience is required',
            'project.required' => 'Project is required',
            'max_project.required' => 'Year of project is required',
            'description.required' => 'Description is required',
            'images1.image' => 'The image must be an image',
            'images1.file' => 'The image must be a file',
            'images1.max' => 'The image must not be greater than 5 MB',
            'images2.image' => 'The image must be an image',
            'images2.file' => 'The image must be a file',
            'images2.max' => 'The image must not be greater than 5 MB',
            'images3.image' => 'The image must be an image',
            'images3.file' => 'The image must be a file',
            'images3.max' => 'The image must not be greater than 5 MB',
        ]);

        $profile = Profile::find($profileId);
        $profile->profession = $request->profession;
        $profile->experience = $request->experience;
        $profile->year_experience = $request->year_experience;
        $profile->project = $request->project;
        $profile->max_project = $request->max_project;
        $profile->description = $request->description;
        if ($request->hasFile('images1')) {
            $profile->images1 = $request->file('images1')->store('/profile/images1/img', 'public');
        }
        if ($request->hasFile('images2')) {
            $profile->images2 = $request->file('images2')->store('/profile/images2/img', 'public');
        }
        if ($request->hasFile('images3')) {
            $profile->images3 = $request->file('images3')->store('/profile/images3/img', 'public');
        }
        $profile->save();
        return response()->json([
            'message' => 'Profile updated successfully.'
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function editPassword(Request $request)
    {
        return response()->json([
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
        // return Inertia::render('Profile/Edit', [
        //     'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
        //     'status' => session('status'),
        // ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updatePassword(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
