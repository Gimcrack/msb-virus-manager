<?php

namespace App\Events;

use App\LogEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LogEntryWasCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log_entry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LogEntry $log_entry)
    {
        $this->log_entry = $log_entry;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('log_entries');
    }

    /**
     * The data to broadcast with the event
     * @method broadcastWith
     *
     * @return   array
     */
    public function broadcastWith()
    {
        $this->log_entry->load('client');

        return [
            'log_entry' => $this->log_entry
        ];
    }
}
