<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use App\Client;
use App\Pattern;
use App\LogEntry;
use App\Exemption;
use App\MatchedFile;
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
        // initial state
        
        $clients = Client::all();
        $chats = Chat::with('user')->latest()->limit(25)->get();
        $matches = MatchedFile::with(['client','pattern'])->get();
        $exemptions = Exemption::published()->get();
        $definitions = Definitions::active();
        $lastUpdated = Definitions::lastUpdated();
        $definitions = $definitions->values();
        $patterns = Pattern::all();
        $logs = LogEntry::with('client')->latest()->paginate(25);
        $users = User::all();


        $initial_state = collect(compact('clients','chats', 'matches', 'exemptions', 'definitions','patterns','logs','users'));

        return view('home', compact('initial_state') );
    }
}
