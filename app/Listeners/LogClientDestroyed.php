<?php

namespace App\Listeners;

use App\Events\ClientWasDestroyed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogClientDestroyed
{
    /**
     * Handle the event.
     *
     * @param  ClientWasDestroyed  $event
     * @return void
     */
    public function handle(ClientWasDestroyed $event)
    {
        $event->client->logs()->create([
            'action' => 'Destroy',
            'status' => 'Client record was removed'
        ]);
    }
}
