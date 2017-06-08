<?php

namespace App\Http\Controllers;

use App\Client;
use App\LogEntry;
use Illuminate\Http\Request;

class ClientLogEntryController extends Controller
{
    /**
     * Create a new LogEntry for the given client
     * @method store
     *
     * @return   response
     */
    public function store(Client $client)
    {
        $client->logs()->create(
            $this->validate(request(), [
                'action' => 'required',
                'status' => 'required'
            ])
        );

        return response()->json([],201);
    }
}
