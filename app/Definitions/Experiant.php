<?php

namespace App\Definitions;

use Cache;
use Zttp\Zttp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Definitions\Facades\Definitions;

class Experiant extends DefinitionsProvider {

    /**
     * The url of the definitions api
     *
     * @var        string
     */
    private $url = "https://fsrm.experiant.ca/api/v1/combined";

    /**
     * Get the definitions
     * @method fetch
     *
     * @return   Collection
     */
    public function fetch() : Collection
    {
        $response = Cache::remember( Definitions::implementation() . "_definitions", 60 * 24, function() {
            return (object) Zttp::get( $this->url )->json();
        });

        $this->definitions = collect($response->filters);

        $this->lastUpdated = ( new Carbon( $response->lastUpdated['date'], $response->lastUpdated['timezone'] ) )->setTimezone('America/Anchorage');

        return $this->definitions();
    }
}