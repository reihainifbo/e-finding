<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// Models
use App\Models\ForumReply;

class NewReplyPosted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(ForumReply $comment)
    {
        $this->comment = $comment;
        Log::info('NewReplyPosted event created', ['comment' => $comment]);
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting on channel: forum-post.' . $this->comment->post_id);
        return new Channel('forum-post.' . $this->comment->post_id);
    }

    public function broadcastWith()
    {
        $data = [
            'id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'user_id' => $this->comment->user_id,
            'content' => $this->comment->content,
            'created_at' => $this->comment->created_at->toDateTimeString(),
            'updated_at' => $this->comment->updated_at->toDateTimeString(),
        ];

        Log::info('Broadcasting data: ', $data);
        return $data;
    }
}
