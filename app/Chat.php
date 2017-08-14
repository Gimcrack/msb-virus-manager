<?php

namespace App;


use App\Events\ChatMessageReceived;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];

    protected $events = [
        'created' => ChatMessageReceived::class,
    ];

    /**
     * A chat belongs to one user
     * @method user
     *
     * @return   App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
