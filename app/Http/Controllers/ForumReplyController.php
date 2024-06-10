<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\ForumReply;

// Event
use App\Events\NewReplyPosted;

class ForumReplyController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $reply = ForumReply::create([
            'post_id' => $postId,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        broadcast(new NewReplyPosted($reply))->toOthers();

        return response()->json($reply, 201);
    }
}
