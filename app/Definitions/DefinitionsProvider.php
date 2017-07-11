<?php

namespace App\Definitions;

use Cache;
use App\Exemption;
use Carbon\Carbon;
use ReflectionClass;
use Illuminate\Support\Collection;
use App\Events\DefinitionsWereUpdated;
use App\Definitions\Concerns\DefinitionsCanBeFaked;
use App\Definitions\Contracts\Definitions as DefinitionsContract;

abstract class DefinitionsProvider implements DefinitionsContract {

    use DefinitionsCanBeFaked;

    /**
     * The definitions collection
     */
    protected $definitions;

    /**
     * The date last updated
     */
    protected $lastUpdated;

    /**
     * Return the implementation
     * @method implementation
     *
     * @return   void
     */
    public function implementation() : string
    {
        return get_called_class();
    }

    /**
     * Get the short name of the implementation class
     * @method implementation_shot
     *
     * @return   string
     */
    public function implementation_short() : string
    {
        $class = get_called_class();
        $reflect = new ReflectionClass( new $class  );

        return $reflect->getShortName();
    }

    /**
     * Return the definitions
     * @method definitions
     *
     * @return   Collection
     */
    public function definitions() : Collection
    {
        return $this->definitions ?? collect([]);
    }

    /**
     * Get the date last updated
     * @method lastUpdated
     *
     * @return   Carbon
     */
    public function lastUpdated() : Carbon
    {
        $prevUpdate = Cache::forever('definitionsLastUpdated', $this->lastUpdated) ?? Carbon::parse("1900-01-01 00:00:00");

        if ($this->lastUpdated && $this->lastUpdated->gt( $prevUpdate->addHour() ) )
        {
            Cache::forget('definitionsLastUpdated');

            $prevUpdate = Cache::forever('definitionsLastUpdated', 
                $this->lastUpdated ? ( $this->lastUpdated->timestamp ) : ( new Carbon("1900-01-01 00:00:00"))->timestamp
            );

            event( new DefinitionsWereUpdated );
        }

        return $this->lastUpdated;
    }

    /**
     * Get the Definitions status
     * @method status
     *
     * @return   string
     */
    public function status()
    {
        $this->fetch();
        
        return $this->implementation_short() 
            . "::" 
            . $this->lastUpdated() 
            . " ({$this->active()->count()} Active Patterns)";
    }

    /**
     * Get the active definitions
     * @method active
     *
     * @return   collection
     */
    public function active() : Collection
    {
        if ( ! $this->lastUpdated ) $this->fetch();

        $exemptions = Exemption::published()->pluck('pattern')->all();

        return $this
            ->definitions()
            ->flip()
            ->except($exemptions)
            ->flip()
            ->map( function($pattern, $id) {
                $status = 'active';
                return compact('pattern','id','status');
            });
    }
}