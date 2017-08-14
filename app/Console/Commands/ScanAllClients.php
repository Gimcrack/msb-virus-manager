<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class ScanAllClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentry:scanAllClients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tell all the clients to perform a full scan';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Client::all()->each->scan();
    }
}
