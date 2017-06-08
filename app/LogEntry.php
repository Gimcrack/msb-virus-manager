<?php

namespace App;

use App\Client;
use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $guarded = [];

    /**
     * A LogEntry belongs to one Client
     * @method client
     *
     * @return   Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
