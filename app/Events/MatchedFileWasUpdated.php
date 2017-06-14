<?php

namespace App\Events;

use App\User;
use App\MatchedFile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Notifications\MatchedFileIncrementedNotification;

class MatchedFileWasUpdated
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

        $this->handle();
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

    /**
     * Handle the event
     * @method handle
     *
     * @return   void
     */
    public function handle()
    {
        Notification::send( 
            User::admins()->get(), 
            new MatchedFileIncrementedNotification( $this->matched_file ) 
        );
    }
}
