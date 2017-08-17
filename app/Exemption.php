<?php

namespace App;

use App\MatchedFile;
use App\Events\ExemptionWasCreated;
use App\Events\ExemptionWasUpdated;
use App\Events\ExemptionWasDestroyed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Exemption extends Model
{

    protected $guarded = [];

    protected $events = [
        'created' => ExemptionWasCreated::class,
        'updated' => ExemptionWasUpdated::class,
        'deleting' => ExemptionWasDestroyed::class,
    ];

    protected $casts = [
        'published_flag' => 'bool'
    ];

    /**
     * Limit the scope to only published exemptions
     * @method scopePublished
     *
     * @return   Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->wherePublishedFlag(1);
    }

    /**
     * Unpublish the Exemption
     * @method unpublish
     *
     * @return   bool
     */
    public function unpublish()
    {
        return $this->update(['published_flag' => 0]);
    }

    /**
     * Publish the Exemption
     * @method publish
     *
     * @return   bool
     */
    public function publish()
    {
        return $this->update(['published_flag' => 1]);
    }

    /**
     * Create a new exemption from the specified pattern
     * @method createFromPattern
     *
     * @return   self
     */
    public static function createFromPattern($pattern)
    {
        $ex = static::firstOrCreate(['pattern' => $pattern]);

        MatchedFile::where('file',$ex->pattern)->get()->each->mute();

        MatchedFile::where('file','like',"%{$ex->pattern}")->get()->each->mute();

        MatchedFile::whereHas('pattern', function($query) use ($ex) {
            return $query->where('name',$ex->pattern);
        })->get()->each->mute();

        return $ex->fresh();
    }
}
