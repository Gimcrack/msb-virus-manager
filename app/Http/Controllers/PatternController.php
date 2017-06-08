<?php

namespace App\Http\Controllers;

use App\Pattern;
use Illuminate\Http\Request;

class PatternController extends Controller
{
    /**
     * Get an index of the resource
     * @method index
     *
     * @return   response
     */
    public function index()
    {
        return response()->json( Pattern::published()->get(), 200 );
    }

    /**
     * Show the specified pattern
     * @method show
     *
     * @return   response
     */
    public function show(Pattern $pattern)
    {
        return response()->json( $pattern, 200);
    }

    /**
     * Unpublish the specified pattern
     * @method unpublish
     *
     * @return   response
     */
    public function unpublish(Pattern $pattern)
    {
        $pattern->unpublish();

        return response()->json([],202);
    }

    /**
     * Publish the specified pattern
     * @method publish
     *
     * @return   response
     */
    public function publish(Pattern $pattern)
    {
        $pattern->publish();

        return response()->json([],202);
    }
}
