<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientPasswordReset;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ClientPasswordResetController extends Controller
{
    use ValidatesRequests;

    /**
     * Request a password reset for the specified client
     * @method request
     *
     * @return   response
     */
    public function request(Client $client)
    {
        $this->validate(request(), [
            'password' => [
                'required',
                'confirmed',
                'min:20',
                'complex'
            ],
            'master_password' => 'required|master_password'
        ]);

        ClientPasswordReset::forClient($client)->request(request('password'));

        return response([],202);
    }

    /**
     * Request a password reset for multiple clients
     * @method multiRequest
     *
     * @return   response
     */
    public function multiRequest()
    {
        $this->validate(request(), [
            'password' => [
                'required',
                'confirmed',
                'min:20',
                'complex'
            ],
            'clients' => 'required|array',
            'master_password' => 'required|master_password'
        ]);

        collect(request('clients'))
            ->map( function($name) {
                $client = Client::where('name',$name)->first();
                if ($client->exists())
                    ClientPasswordReset::forClient($client)->request(request('password'));
            });

        return response([],202); 
    }

    /**
     * Complete the password reset request
     * @method complete
     *
     * @return   void
     */
    public function complete(Client $client)
    {
        ClientPasswordReset::forClient($client)->complete();

        return response([],202);
    }
}
