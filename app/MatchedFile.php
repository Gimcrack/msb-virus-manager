<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchedFile extends Model
{
    protected $guarded = [];

    /**
     * A MatchedFile belongs to a Client
     * @method client
     *
     * @return   App\Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * A MatchedFile belongs to a Pattern
     * @method pattern
     *
     * @return   App\Pattern
     */
    public function pattern()
    {
        return $this->belongsTo(Pattern::class);
    }

    /**
     * A MatchedFile can be incremented when matched again
     * @method incrementMatch
     *
     * @return   $this
     */
    public function incrementMatch()
    {
        $this->increment('times_matched');

        return $this;
    }

    /**
     * Create the record or increment an existing one
     * @method createOrIncrement
     *
     * @return   boolean - false if update, true if create
     */
    public static function createOrIncrement(Client $client, $params)
    {
        $f = static::firstOrNew( [
            'client_id' => $client->id,
            'pattern_id' => $params['pattern_id'],
            'file' => $params['file']
        ]);

        $exists = $f->exists();

        $f->save();
        $f->incrementMatch();

        return $exists;
    }
}
