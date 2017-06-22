<?php

namespace App\Http\Controllers;

use App\Client;
use App\MatchedFile;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOperationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientMatchedFilesController extends Controller
{
    /**
     * Store a MatchedFile record for the specified client
     * @method store
     *
     * @return   response
     */
    public function store(Client $client)
    {
        // TODO: 5.5 syntax
        // $data = $this->validate(request(), [
        //     'pattern_id' => 'required|exists:patterns,id',
        //     'file' => 'required'
        // ]);
         
        $this->validate(request(), [
            'pattern_id' => 'required|exists:patterns,id',
            'file' => 'required'
        ]);
        
        $data = request()->only(['pattern_id','file']);

        $code = MatchedFile::createOrIncrement( $client, $data ) ? 202 : 201;

        return response()->json([], $code);
    }

    /**
     * Mute the specified matched file for the specified client
     * @method mute
     *
     * @return   response
     */
    public function mute(Client $client, MatchedFile $match)
    {
        if ( ! $match->client->is($client) )
            throw new ModelNotFoundException;

        $match->mute();

        return response()->json([], 202);
    }

    /**
     * Acknowledge the specified matched file for the specified client
     * @method acknowledge
     *
     * @return   response
     */
    public function acknowledge(Client $client, MatchedFile $match)
    {
        if ( ! $match->client->is($client) )
            throw new ModelNotFoundException;

        $match->acknowledge();

        return response()->json([], 202);
    }

    /**
     * Mute the specified matched file for the specified client
     * @method unmute
     *
     * @return   response
     */
    public function unmute(Client $client, MatchedFile $match)
    {
        if ( ! $match->client->is($client) )
            throw new ModelNotFoundException;
        
        $match->unmute();

        return response()->json([], 202);
    }
}
