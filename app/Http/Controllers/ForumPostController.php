<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\ForumPost;

class ForumPostController extends Controller
{
    public function index()
    {
        try {
            $items = ForumPost::with('user')->get();

            return response()->json([
                'success' => true,
                'message' => 'Success getting Posts',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Posts data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
            ]);

            $post = ForumPost::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success posting',
                'data' => $post
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when posting',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $post = ForumPost::with('user', 'replies.user')->findOrfail($id);

            return response()->json([
                'success' => true,
                'message' => 'Success getting Post',
                'data' => $post
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Post',
                'errors' => $e->getMessage()
            ], 400);
        }
    }
}
