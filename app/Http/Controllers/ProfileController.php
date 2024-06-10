<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $user = Profile::with('user')->findOrfail(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Success getting Profile data',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Profile data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Profile::with('user')->where('user_id', Auth::id())->first();

            $request->validate([
                'phone' => 'required|string|max:255',
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $path = $request->file('profile_picture') ? $request->file('profile_picture')->store('public/profile') : null;

            $user->update([
                'phone' => $request->phone,
                'profile_picture' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success updating Profile data',
                'data' => $user
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when updating Profile data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }
}
