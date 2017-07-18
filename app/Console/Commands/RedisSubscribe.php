<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;
use App\Events\ClientWasUpdated;
use Illuminate\Support\Facades\Redis;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to redis events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::psubscribe(['clients.*.heartbeat'],function($message, $channel) {
            echo $channel . " " . $message . "\n";
            return $this->route($message,$channel);
        });
    }

    /**
     * Route the event
     * @method route
     *
     * @return   void
     */
    private function route($message, $channel)
    {
        switch(true)
        {
            case preg_match("/clients\/(.*)\/heartbeat/", $channel, $matches) :
                return $this->heartbeat($matches[1]);
        }
    }

    /**
     * Advance the heartbeat of the client
     * @method heartbeat
     *
     * @return   void
     */
    private function heartbeat($client)
    {
        // try {
            $client = Client::whereName(strtolower($client))->first();
            $client->heartbeat();

            event( new ClientWasUpdated($client) );
        // }
        // catch( \Exception $e)
        // {
        //     // do nothing
        // }
    }
}
