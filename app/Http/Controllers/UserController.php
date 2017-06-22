<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Get an index of the resource
     * @method index
     *
     * @return   response
     */
    public function index()
    {
        return response()->json( User::all(), 200 );
    }

    /**
     * Promote the user to admin
     * @method promote
     *
     * @return   response
     */
    public function promote(User $user)
    {
        $user->promoteToAdmin();

        return response()->json([],202);
    }
}
