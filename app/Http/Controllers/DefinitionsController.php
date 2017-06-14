<?php

namespace App\Http\Controllers;

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
        $definitions = Definitions::fetch();

        return response()->json( compact('definitions'), 200);
    }
}
