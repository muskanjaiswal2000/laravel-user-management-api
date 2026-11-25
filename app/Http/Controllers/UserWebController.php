<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserWebController extends Controller
{
    public function showProfile()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $token = session('api_token');

        $requestData = [
            'name' => $request->name,
            'email' => $request->email
        ];

        $response = Http::withToken($token)->put(config('app.url') . '/api/profile', $requestData);

        if ($response->successful()) {
            $data = $response->json();
            session(['user' => $data['data']]);

            if ($request->hasFile('avatar')) {
                $avatarResponse = Http::withToken($token)
                    ->attach('avatar', file_get_contents($request->file('avatar')), $request->file('avatar')->getClientOriginalName())
                    ->post(config('app.url') . '/api/profile/avatar');

                if ($avatarResponse->successful()) {
                    $avatarData = $avatarResponse->json();
                    session(['user' => $avatarData['data']]);
                }
            }

            return back()->with('success', 'Profile updated successfully');
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }

    public function deleteAvatar(Request $request)
    {
        $token = session('api_token');

        $response = Http::withToken($token)->delete(config('app.url') . '/api/profile/avatar');

        if ($response->successful()) {
            $data = $response->json();
            session(['user' => $data['data']]);
            return back()->with('success', 'Avatar removed successfully');
        }

        return back()->with('error', 'Failed to remove avatar');
    }
}
