<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Support\Collection;
use App\Definitions\Facades\Definitions;

class DefinitionsProviderTests extends TestCase
{
    /**
     * The minimum definition count to expect
     *
     * @var        int
     */
    protected $minCount;

    /**
     * The class of the implementation
     */
    protected $implementation;

    /** @test */
    function definitions_can_be_fetched_from_the_provider()
    {
        $definitions = Definitions::fetch();

        $this->assertInstanceOf( Collection::class, $definitions );

        $definitions->assertNotEmpty();
    }

    /** @test */
    function definitions_can_be_retrieved_after_they_are_fetched()
    {
        // set up
        Definitions::definitions()->assertEmpty();

        // act
        Definitions::fetch();

        // assert
        Definitions::definitions()->assertNotEmpty();
    }

    /** @test */
    function a_certain_number_of_definitions_can_be_expected()
    {
        Definitions::fetch()->assertMinCount( $this->minCount );
    }

    /** @test */
    function the_implementation_of_the_definitions_provider_can_be_obtained()
    {
        $this->assertEquals( $this->implementation, Definitions::implementation() );
    }
}
