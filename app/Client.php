<?php

namespace App;

use Carbon\Carbon;
use App\MatchedFile;
use App\ClientPasswordReset;
use App\Events\ClientShouldScan;
use App\Events\ClientWasCreated;
use App\Events\ClientWasUpdated;
use App\Events\ClientWasDestroyed;
use App\Events\ClientSentHeartbeat;
use App\Events\ClientShouldUpgrade;
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
        'heartbeat_at',
        'password_reset'
    ];

    protected $appends = [
        'heartbeat_status',
        'password_reset_recently'
    ];

    protected $casts = [
        'scanned_files_count' => 'int',
        'scanned_files_current' => 'int'
    ];

    protected $with = [
        'password_reset'
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
     * A client may have one password reset record
     * @method password_reset
     *
     * @return   App\ClientPasswordReset
     */
    public function password_reset()
    {
        return $this->hasOne(ClientPasswordReset::class);
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
        \DB::statement(vsprintf("UPDATE clients SET heartbeat_at='%s' WHERE id = %s", [Carbon::now(), $this->id]));

        event( new ClientSentHeartbeat($this->fresh()) );
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
     * Perform a scan on the client
     * @method scan
     *
     * @return   void
     */
    public function scan()
    {
        event( new ClientShouldScan($this));
    }

    /**
     * Perform an upgrade on the client
     * @method upgrade
     *
     * @return   void
     */
    public function upgrade()
    {
        event( new ClientShouldUpgrade($this));
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

    /**
     * Get the password_recent_recently attribute
     * @method getPasswordResetRecentlyAttribute
     *
     * @return   bool
     */
    public function getPasswordResetRecentlyAttribute()
    {
        if ( ! $this->password_reset || ! $this->password_reset->completed_at ) return false;

        return $this->password_reset->completed_at->gt( Carbon::now()->subDays(30) );
    }
}
