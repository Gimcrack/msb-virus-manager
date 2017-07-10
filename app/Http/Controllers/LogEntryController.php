<?php

namespace App\Http\Controllers;

use App\LogEntry;
use Illuminate\Http\Request;

class LogEntryController extends Controller
{
    /**
     * Get a listing of the resource
     * @method index
     *
     * @return   response
     */
    public function index()
    {
        return response()->json( LogEntry::with('client')->latest()->paginate(25), 200);
    }
}
