<?php

namespace Tests\Feature;

use Definitions;
use Tests\TestCase;

class DefinitionsTest extends TestCase {
    
    /** @test */
    function the_current_definitions_can_be_retrieved()
    {   
        // setup
        Definitions::fake();

        // act
        $this->get('api/v1/definitions')

        // assert
        ->response()
            ->assertStatus(200)
            ->assertJsonFragment(['definitions'])
            ->assertJsonFragment(['foo'])
            ->assertJsonFragment(['bar'])
            ->assertJsonFragment(['biz']);

    }
}