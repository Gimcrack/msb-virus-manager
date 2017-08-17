<?php

namespace App\Http\Controllers;

use App\MatchedFile;
use Illuminate\Http\Request;

class MatchedFileController extends Controller
{
    /**
     * Get an index of the resource
     * @method index
     *
     * @return   response
     */
    public function index()
    {
        return response()->json(MatchedFile::with(['client','pattern'])->get(), 200);
    }

    /**
     * Get a single matched file
     * @method show
     *
     * @return   response
     */
    public function show(MatchedFile $file)
    {
        $file->load(['client','pattern']);
        return response()->json($file,200);
    }

    /**
     * Acknowledge all unacked files
     * @method acknowledge
     *
     * @return   response
     */
    public function acknowledge()
    {
        MatchedFile::whereAcknowledgedFlag(0)->get()->each->acknowledge();

        return response()->json( [], 202 );
    }

    /**
     * Mute the selected files
     * @method mute
     *
     * @return   response
     */
    public function muteMany()
    {
        $this->validate( request(), [
            'matches' => 'required|array'
        ] );

        MatchedFile::find( request('matches') )->each->mute();

        return response()->json([], 202);
    }
}
