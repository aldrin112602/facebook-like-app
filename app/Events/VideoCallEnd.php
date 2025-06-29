<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallEnd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        if (isset($this->data['caller_id'])) {
            return new PrivateChannel('user.' . $this->data['caller_id']);
        }
        return new PrivateChannel('user.' . $this->data['target_user_id']);
    }

    public function broadcastAs()
    {
        return 'video.call.end';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}