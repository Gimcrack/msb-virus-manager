<?php

namespace App\Listeners;

use App\Events\PatternWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePatterns
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PatternWasCreated  $event
     * @return void
     */
    public function handle(PatternWasCreated $event)
    {
        //
    }
}
