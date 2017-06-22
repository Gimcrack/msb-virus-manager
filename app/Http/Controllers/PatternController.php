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
        if ( request('published') )
        {
            return response()->json( Pattern::published()->get(), 200 );
        }

        return response()->json( Pattern::all(), 200 );
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
     * Store the pattern
     * @method store
     *
     * @return   response
     */
    public function store()
    {
        $pattern = Pattern::firstOrNew( request()->only(['name']) );

        $code = ( $pattern->exists() ) ? 202 : 201;
            
        $pattern->save();
        return response()->json($pattern, $code);
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

    /**
     * Destroy the specified pattern
     * @method destroy
     *
     * @return   response
     */
    public function destroy(Pattern $pattern)
    {
        $pattern->delete();

        return response()->json([], 202);
    }
}
