<?php

namespace App\Http\Controllers;

use App\Exemption;
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

    /**
     * unmute the selected files
     * @method unmute
     *
     * @return   response
     */
    public function unmuteMany()
    {
        $this->validate( request(), [
            'matches' => 'required|array'
        ] );

        MatchedFile::find( request('matches') )->each->unmute();

        return response()->json([], 202);
    }

    /**
     * Exempt the selected matched files by file
     * @method exemptMany
     *
     * @return   response
     */
    public function exemptMany()
    {
        $this->validate( request(), [
            'matches' => 'required|array'
        ]);

        MatchedFile::find( request('matches') )->each( function($match) {
            Exemption::createFromPattern($match->file);
        });

        return response()->json([],202);
    }

    /**
     * Exempt the selected matched files by filename
     * @method exemptManyByFilename
     *
     * @return   response
     */
    public function exemptManyByFilename()
    {
        $this->validate( request(), [
            'matches' => 'required|array'
        ]);

        MatchedFile::find( request('matches') )->each( function($match) {
            Exemption::createFromPattern($match->filename);
        });

        return response()->json([],202);
    }
}
