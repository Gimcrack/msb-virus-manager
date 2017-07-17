<?php

namespace App\Http\Controllers;

use App\Client;
use App\Events\NewBuild;
use Illuminate\Http\Request;
use App\Events\ClientShouldScan;
use App\Events\ClientWasUpgraded;
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
     * Instruct the client to scan
     * @method scan
     *
     * @return   App\Client
     */
    public function scan(Client $client)
    {
        event( new ClientShouldScan($client));

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

        $exists = !! Client::whereName(strtolower($name))->first();

        return response()->json(Client::updateOrCreate([ 
            'name' => strtolower($name),
        ], 
        [ 
            'version' => request('version') 
        ]), $exists ? 202 : 201);
    }

    /**
     * Update the client's heartbeat_at timestamp
     * @method heartbeat
     *
     * @return   response
     */
    public function heartbeat(Client $client)
    {
        $client->heartbeat();

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
        if ( request('version') != $client->version )
        {
            event( new ClientWasUpgraded($client) );
        }

        $client->update([
            'version' => request('version')
        ]);


        return response()->json([],202);
    }

    /**
     * A new build has been made, make an event
     * @method build
     *
     * @return   response
     */
    public function build()
    {
        event( new NewBuild );

        return response()->json([],202);
    }

    /**
     * Get the latest agent build
     * @method agentBuild
     *
     * @return   response
     */
    public function agentBuild()
    {
        return response()->json(
            [ 'version' => Client::max_version() ],
            200
        );
    }

    /**
     * Destroy the specified client
     * @method destroy
     *
     * @return    void
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json([],202);
    }

    /**
     * Set the client's scanned files count
     * @method count
     *
     * @return   void
     */
    public function count(Client $client)
    {
        $this->validate( request(), [
            'count' => 'required|integer'
        ]);

        $client->scannedCount( request('count') );

        return response()->json([],202);
    }

    /**
     * Set the client's scanned files countCurrent
     * @method countCurrent
     *
     * @return   void
     */
    public function countCurrent(Client $client)
    {
        $this->validate( request(), [
            'count' => 'required|integer'
        ]);

        $client->scannedCurrent( request('count') );

        return response()->json([],202);
    }
}
