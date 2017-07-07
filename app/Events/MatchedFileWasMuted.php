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

class MatchedFileWasMuted implements ShouldBroadcast
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
        return new Channel( strtolower("clients.{$this->matched_file->client->name}.matches.{$this->matched_file->id}") );
    }
    
    /**
     * Get the attributes to broadcast
     * @method broadcastWith
     *
     * @return   array
     */
    public function broadcastWith()
    {
        return ['matched_file' => $this->matched_file->load(['pattern','client'])->toArray() ];
    }
}
