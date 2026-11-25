<?php

namespace Modules\ProfileManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProfileManagement\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'role' => $user->role,
                'created_at' => $user->created_at
            ]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id
        ]);

        $profile = $this->profileService->updateProfile($request->user()->id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'avatar' => $profile->avatar ? asset('storage/' . $profile->avatar) : null,
                'role' => $profile->role,
                'created_at' => $profile->created_at
            ]
        ], 200);
    }

    public function uploadAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $profile = $this->profileService->updateAvatar($request->user()->id, $validated['avatar']);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'avatar' => $profile->avatar ? asset('storage/' . $profile->avatar) : null,
                'role' => $profile->role,
                'created_at' => $profile->created_at
            ]
        ], 200);
    }

    public function deleteAvatar(Request $request)
    {
        $profile = $this->profileService->deleteAvatar($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Avatar deleted successfully',
            'data' => [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'avatar' => null,
                'role' => $profile->role,
                'created_at' => $profile->created_at
            ]
        ], 200);
    }
}
