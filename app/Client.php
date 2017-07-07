<?php

namespace App;

use App\MatchedFile;
use App\Events\ClientWasCreated;
use App\Events\ClientWasUpdated;
use App\Events\ClientWasDestroyed;
use Illuminate\Database\Eloquent\Model;

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

         return  
            collect( explode('.',$v) )
                ->transform( function($part) {
                    return 1 * $part;
                })
                ->implode('.');
    }
}
