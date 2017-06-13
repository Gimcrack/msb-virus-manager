<?php

namespace App\Events;

use App\Exemption;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExemptionWasDestroyed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exemption;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Exemption $exemption)
    {
        $this->exemption = $exemption;
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
