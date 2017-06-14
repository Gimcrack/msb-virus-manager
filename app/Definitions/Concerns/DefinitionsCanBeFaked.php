<?php 

namespace App\Definitions\Concerns;

use App\Definitions\FakeDefinitions;
use App\Definitions\Facades\Definitions;

trait DefinitionsCanBeFaked 
{

    /**
     * Swap out the definitions provider
     * @method fake
     *
     * @return   this
     */
    public function fake()
    {
        Definitions::swap( new FakeDefinitions );

        return $this;
    }
}