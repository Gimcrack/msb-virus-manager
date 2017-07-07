<?php

namespace App\Events;

use App\Pattern;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PatternWasDestroyed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pattern;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;

        $this->handle();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("patterns");
    }

    /**
     * Handle the event
     * @method handle
     *
     * @return   void
     */
    public function handle()
    {
        $this->pattern->matched_files->each->delete();
    }
}
