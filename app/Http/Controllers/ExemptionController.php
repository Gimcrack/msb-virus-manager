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
     * Create an exemption
     * @method create
     *
     * @return   response
     */
    public function create()
    {
        Exemption::create( request()->only(['pattern']) );
        return response()->json([],201);
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

    /**
     * Delete the specified exemption
     * @method destroy
     *
     * @return   response
     */
    public function destroy(Exemption $exemption)
    {
        $exemption->delete();

        return response()->json([], 202);
    }
}
