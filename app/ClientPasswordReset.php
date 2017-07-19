<?php

namespace App;

use App\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Events\ClientPasswordResetWasCompleted;
use App\Events\ClientPasswordResetWasRequested;

class ClientPasswordReset extends Model
{
    protected $guarded = [];

    protected $dates = [
        'requested_at',
        'completed_at'
    ];

    /**
     * A ClientPasswordReset belongs to one Client
     * @method client
     *
     * @return   App\Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Set the requested_at value to now
     * @method request
     *
     * @return   void
     */
    public function request($password)
    {
        $this->update(['requested_at' => Carbon::now()]); 

        event( new ClientPasswordResetWasRequested($this->client, $password) );
    }

    /**
     * Set the completed_at value to now
     * @method complete
     *
     * @return   void
     */
    public function complete()
    {
        $this->update(['completed_at' => Carbon::now()]); 

        event( new ClientPasswordResetWasCompleted($this->client) );
    }

    /**
     * Get or create a ClientPasswordReset model for the specified client
     * @method forClient
     *
     * @return   static
     */
    public static function forClient(Client $client)
    {
        return static::firstOrCreate(['client_id' => $client->id]);
    }
}
