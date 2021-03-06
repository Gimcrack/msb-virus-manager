<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PatternWasCreated' => [],
        'App\Events\PatternWasUpdated' => [],
        'App\Events\PatternWasDestroyed' => [],

        'App\Events\ClientWasCreated' => [
            'App\Listeners\LogNewClient'
        ],
        'App\Events\ClientWasUpdated' => [],
        'App\Events\ClientWasDestroyed' => [
            'App\Listeners\LogClientDestroyed'
        ],
        'App\Events\ClientShouldUpgrade' => [
            'App\Listeners\LogClientPendingUpgrade'
        ],

        'App\Events\ExemptionWasCreated' => [],
        'App\Events\ExemptionWasUpdated' => [],
        'App\Events\ExemptionWasDestroyed' => [],

        'App\Events\MatchedFileWasCreated' => [
            'App\Listeners\LogNewMatchedFile'
        ],
        'App\Events\MatchedFileWasUpdated' => [],
        'App\Events\MatchedFileWasMuted' => [],
        'App\Events\MatchedFileWasUnmuted' => [],

        'App\Events\NewBuild' => [],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
