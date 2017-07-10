<?php

namespace App\Listeners;

use App\Events\ClientWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogNewClient
{
    /**
     * Handle the event.
     *
     * @param  ClientWasCreated  $event
     * @return void
     */
    public function handle(ClientWasCreated $event)
    {
        $event->client->logs()->create([
            'action' => 'Register',
            'status' => 'Registration Successful'
        ]);
    }
}
