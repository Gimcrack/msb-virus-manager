<?php

namespace App\Definitions;

use Illuminate\Support\Collection;
use App\Definitions\Concerns\DefinitionsCanBeFaked;
use App\Definitions\Contracts\Definitions as DefinitionsContract;

abstract class DefinitionsProvider implements DefinitionsContract {

    use DefinitionsCanBeFaked;

    /**
     * The definitions collection
     */
    protected $definitions;

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
     * Return the definitions
     * @method definitions
     *
     * @return   Collection
     */
    public function definitions() : Collection
    {
        return $this->definitions ?? collect([]);
    }
}