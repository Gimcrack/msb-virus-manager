<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Definitions\Facades\Definitions;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $host = ( env('APP_ENV') == 'local' ) ? 'localhost:6001' : 'sentry.itdashboard.matsugov.us:6001';
        
        return view('home', compact('host'));
    }
}
