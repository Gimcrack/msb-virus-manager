<?php

namespace App\Listeners;

use App\Events\MatchedFileWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogNewMatchedFile
{
    /**
     * Handle the event.
     *
     * @param  MatchedFileWasCreated  $event
     * @return void
     */
    public function handle(MatchedFileWasCreated $event)
    {
        $event->matched_file->client->logs()->create([
            'action' => 'Scan',
            'status' => 'Matched File Found: ' . $event->matched_file->toJson()
        ]);
    }
}
