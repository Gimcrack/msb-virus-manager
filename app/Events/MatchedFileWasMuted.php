<?php

namespace App\Events;

use App\MatchedFile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MatchedFileWasMuted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matched_file;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MatchedFile $matched_file)
    {
        $this->matched_file = $matched_file;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
