<?php

namespace App;

use Log;
use App\Events\MatchedFileWasMuted;
use App\Events\MatchedFileWasCreated;
use App\Events\MatchedFileWasUnmuted;
use App\Events\MatchedFileWasUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Events\MatchedFileWasIncremented;
use App\Events\AllMatchedFilesAcknowledged;

class MatchedFile extends Model
{
    protected $guarded = [];

    protected $events = [
        'created' => MatchedFileWasCreated::class,
    ];

    protected $dates = [
        'file_created_at',
        'file_modified_at',
    ];

    protected $casts = [
        'client_id' => 'int',
        'pattern_id' => 'int',
        'times_matched' => 'int',
        'muted_flag' => 'bool',
        'acknowledged_flag' => 'bool',
    ];

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

        if ( ! $this->muted_flag ) {
            $this->acknowledged_flag = 0;
            $this->save();
            event(new MatchedFileWasIncremented($this));
        }

        return $this;
    }

    /**
     * Acknowledge the matched file
     * @method acknowledge
     *
     * @return   $this
     */
    public function acknowledge()
    {
        $this->acknowledged_flag = 1;
        $this->save();
        if ( ! $this->muted_flag)
            event(new MatchedFileWasUpdated($this));

        if ( ! static::whereAcknowledgedFlag(0)->count() )
            event(new AllMatchedFilesAcknowledged);

        return $this;
    }


    /**
     * Mute the matched file
     * @method mute
     *
     * @return   $this
     */
    public function mute()
    {
        $this->muted_flag = 1;
        $this->save();

        if ( ! $this->acknowledged_flag )
        {
            $this->acknowledge();
        }

        event(new MatchedFileWasMuted($this));

        return $this;
    }

    /**
     * Unmute the matched file
     * @method unmute
     *
     * @return   $this
     */
    public function unmute()
    {
        $this->muted_flag = 0;
        $this->save();

        event(new MatchedFileWasUnmuted($this));

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

        Log::debug($params);

        $f = static::firstOrNew( [
            'client_id' => $client->id,
            'file' => $params['file'],
            'pattern_id' => $params['pattern_id'],
        ], [
            'file_created_at' => $params['file_created_at'],
            'file_modified_at' => $params['file_modified_at'],
        ]);

        $exists = $f->exists();

        $f->save();
        $f->incrementMatch();

        return $exists;
    }

    /**
     * Get the filename attribute
     * @method getFilenameAttribute
     *
     * @return   string
     */
    public function getFilenameAttribute()
    {
        $exploded = explode("\\",$this->attributes['file']);
        return array_pop($exploded);
    }
}
