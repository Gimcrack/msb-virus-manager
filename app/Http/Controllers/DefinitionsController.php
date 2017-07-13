<?php

namespace App\Http\Controllers;

use App\Exemption;
use Illuminate\Http\Request;
use App\Definitions\Facades\Definitions;

class DefinitionsController extends Controller
{
    /**
     * Get an index of the resouce
     * @method index
     *
     * @return   response()
     */
    public function index()
    {
        $definitions = Definitions::active();
        
        $lastUpdated = Definitions::lastUpdated();

        return response()->json( $definitions->values(), 200);
    }

    /**
     * Get the definitons status
     * @method status
     *
     * @return   response
     */
    public function status()
    {
        return response()->json( ['definitions' => Definitions::status() ], 200);
    }
}
