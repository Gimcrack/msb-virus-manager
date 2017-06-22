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
}
