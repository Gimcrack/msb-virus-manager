<?php

namespace App\Definitions;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class FakeDefinitions extends DefinitionsProvider {

    /**
     * Get the definitions
     * @method fetch
     *
     * @return   Collection
     */
    public function fetch() : Collection
    {
        $this->definitions = collect([
            'foo', 'bar', 'biz'
        ]);

        $this->lastUpdated = Carbon::now();

        return $this->definitions();
    }
}