<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
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
