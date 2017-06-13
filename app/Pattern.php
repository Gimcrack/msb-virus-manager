<?php

namespace App;

use App\Events\PatternWasCreated;
use App\Events\PatternWasUpdated;
use App\Events\PatternWasDestroyed;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pattern extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'published_flag' => 'bool'
    ];

    protected $events = [
        'created' => PatternWasCreated::class,
        'updated' => PatternWasUpdated::class,
        'deleted' => PatternWasDestroyed::class,
    ];

    /**
     * Publish the pattern
     * @method publish
     *
     * @return   bool
     */
    public function publish()
    {
        return $this->update([
            'published_flag' => 1
        ]);
    }

    /**
     * Unpublish the pattern
     * @method unpublish
     *
     * @return   bool
     */
    public function unpublish()
    {
        return $this->update([
            'published_flag' => 0
        ]);
    }

    /**
     * Scope the results to only published patterns
     * @method scopePublished
     *
     * @return   Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->wherePublishedFlag(1);
    }
}
