<?php

namespace Tests\Integration;

use App\Definitions\Experiant;
use Illuminate\Support\Collection;
use App\Definitions\Facades\Definitions;

/**
 * @group external
 */
class ExperiantTest extends DefinitionsProviderTests
{
    public function setUp()
    {
        parent::setUp();

        $this->minCount = 1000;
        
        $this->implementation = Experiant::class;

        $this->implementation_short = 'Experiant';
    }
}
