<?php

namespace App\Http\Controllers;

use App\Client;
use App\LogEntry;
use Illuminate\Http\Request;

class ClientLogEntryController extends Controller
{

    /**
     * Get an index of the clients LogEntry resources
     * @method index
     *
     * @return   response
     */
    public function index(Client $client)
    {
        $data = $client->logs()->latest()->paginate(25);

        return response()->json( $data, 200 );
    }

    /**
     * Create a new LogEntry for the given client
     * @method store
     *
     * @return   response
     */
    public function store(Client $client)
    {
        // TODO: 5.5 syntax
        // $client->logs()->create(
        //     $this->validate(request(), [
        //         'action' => 'required',
        //         'status' => 'required'
        //     ])
        // );
         
        $this->validate(request(), [
            'action' => 'required',
            'status' => 'required'
        ]);
        
        $client->logs()->create(
           request()->only(['action', 'status']) 
        );

        return response()->json([],201);
    }
}
