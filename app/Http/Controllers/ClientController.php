<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Events\ClientShouldUpgrade;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ClientController extends Controller
{
    use ValidatesRequests;

    /**
     * Retrieve an index of the resource
     * @method index
     *
     * @return   Collection<App\Client>
     */
    public function index()
    {
        return response()->json( Client::all(), 200 );
    }

    /**
     * Show the requested client
     * @method show
     *
     * @return   App\Client
     */
    public function show(Client $client)
    {
        return response()->json( $client, 200);
    }

    /**
     * Instruct the client to upgrade
     * @method upgrade
     *
     * @return   App\Client
     */
    public function upgrade(Client $client)
    {
        event( new ClientShouldUpgrade($client));

        return response()->json( [], 202);
    }

    /**
     * Store a client with the given name
     * @method store
     *
     * @return   App\Client
     */
    public function store($name)
    {
        $this->validate( request(), [
            'version' => 'required'
        ]);

        return response()->json( Client::firstOrCreate([ 
            'name' => $name,
            'version' => request('version')
        ]), 201);
    }

    /**
     * Update the client's updated_at timestamp
     * @method touch
     *
     * @return   response
     */
    public function touch(Client $client)
    {
        $client->touch();

        return response()->json([],202);
    }

    /**
     * Update the client with the new data
     * @method update
     *
     * @return   response
     */
    public function update(Client $client)
    {
        $client->update([
            'version' => request('version')
        ]);

        return response()->json([],202);
    }
}
