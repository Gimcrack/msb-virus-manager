<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'published_flag' => 'bool'
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
