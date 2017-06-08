<?php

namespace App\Http\Controllers;

use App\Client;
use App\MatchedFile;
use Illuminate\Http\Request;

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
        $data = $this->validate(request(), [
            'pattern_id' => 'required|exists:patterns,id',
            'file' => 'required'
        ]);

        $code = MatchedFile::createOrIncrement( $client, $data ) ? 202 : 201;

        return response()->json([], $code);
    }
}
