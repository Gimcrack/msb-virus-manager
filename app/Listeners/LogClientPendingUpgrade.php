<?php

namespace App\Listeners;

use App\Events\ClientShouldUpgrade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogClientPendingUpgrade
{
    /**
     * Handle the event.
     *
     * @param  ClientShouldUpgrade  $event
     * @return void
     */
    public function handle(ClientShouldUpgrade $event)
    {
        $event->client->logs()->create([
            'action' => 'Upgrade',
            'status' => 'Client has been instructed to upgrade.'
        ]);
    }
}
