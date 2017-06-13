<?php

namespace App;

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
        'deleted' => ExemptionWasDestroyed::class,
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
}
