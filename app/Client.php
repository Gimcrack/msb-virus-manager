<?php

namespace App;

use Carbon\Carbon;
use App\MatchedFile;
use App\Events\ClientWasCreated;
use App\Events\ClientWasUpdated;
use App\Events\ClientWasDestroyed;
use Illuminate\Database\Eloquent\Model;
use App\Events\ClientShouldSendHeartbeat;

class Client extends Model
{
    protected $guarded = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    protected $events = [
        'created' => ClientWasCreated::class,
        'updated' => ClientWasUpdated::class,
        'deleting' => ClientWasDestroyed::class,
    ];

    protected $dates = [
        'heartbeat_at'
    ];

    protected $appends = [
        'heartbeat_status'
    ];

    /**
     * Set the name attribute
     * @method setNameAttribute
     *
     * @return   void
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name) ?: null;
    }

    /**
     * Get the heartbeat_status attribute
     * @method getHeartbeatStatusAttribute
     *
     * @return   success|warning|danger
     */
    public function getHeartbeatStatusAttribute()
    {
        if (! $this->heartbeat_at ) return 'danger';

        $diff = Carbon::now()->diffInMinutes($this->heartbeat_at);

        if ( $diff < 30 ) return 'success';
        if ( $diff < 300 ) return 'warning';
        return 'danger';
    }

    /**
     * A client may have many log entries
     * @method logs
     *
     * @return   Collection<App\LogEntry>
     */
    public function logs()
    {
        return $this->hasMany(LogEntry::class);
    }

    /**
     * A client may have many matched files
     * @method logs
     *
     * @return   Collection<App\MatchedFile>
     */
    public function matched_files()
    {
        return $this->hasMany(MatchedFile::class);
    }

    /**
     * Set the scanned file count
     * @method scannedCount
     *
     * @return   void
     */
    public function scannedCount($count)
    {
        $this->update( ['scanned_files_count' => $count]);
    }

    /**
     * Set the current scanned file count
     * @method scannedCurrent
     *
     * @return   void
     */
    public function scannedCurrent($count)
    {
        if ( $count > $this->scanned_files_count )
        {
            $this->update( [
                'scanned_files_current' => $count,
                'scanned_files_count' => $count
            ]);
        }

        else
        {
            $this->update( [
                'scanned_files_current' => $count,
            ]);
        }
    }

    /**
     * Advance the heartbeat timestamp
     * @method heartbeat
     *
     * @return   void
     */
    public function heartbeat()
    {
        $this->update(['heartbeat_at' => Carbon::now()]);

        event( new ClientWasUpdated($this) );
    }

    /**
     * Request a heartbeat from the client
     * @method requestHeartbeat
     *
     * @return   void
     */
    public function requestHeartbeat()
    {
        event( new ClientShouldSendHeartbeat($this) );
    }

    /**
     * Get the max version
     * @method max_version
     *
     * @return   string
     */
    public static function max_version()
    {
        $v = static::pluck('version')
            ->transform( function($v) {
                return collect( explode('.', $v) )
                    ->transform( function($part) {
                        return substr('0000' . $part, -3);
                    })
                    ->implode('.');
            })
            ->max();

        if ( ! $v ) return null;

         return  
            collect( explode('.',$v) )
                ->transform( function($part) {
                    return 1 * $part;
                })
                ->implode('.');
    }
}
