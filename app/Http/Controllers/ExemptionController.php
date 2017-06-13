<?php

namespace App\Http\Controllers;

use App\Exemption;
use Illuminate\Http\Request;

class ExemptionController extends Controller
{
    /**
     * Get an index of the resource
     * @method index
     *
     * @return   response
     */
    public function index()
    {
        return response()->json( Exemption::published()->get(), 200 );
    }

    /**
     * Show the specified Exemption
     * @method show
     *
     * @return   response
     */
    public function show(Exemption $exemption)
    {
        return response()->json($exemption, 200);
    }

    /**
     * Unpublish the specified exemption
     * @method unpublish
     *
     * @return   response
     */
    public function unpublish(Exemption $exemption)
    {
        $exemption->unpublish();

        return response()->json([], 202);
    }

    /**
     * Publish the specified exemption
     * @method publish
     *
     * @return   response
     */
    public function publish(Exemption $exemption)
    {
        $exemption->publish();

        return response()->json([], 202);
    }
}
