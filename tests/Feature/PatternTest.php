<?php

namespace Tests\Feature;

use App\Client;
use App\Pattern;
use Carbon\Carbon;
use Tests\TestCase;
use App\MatchedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class PatternTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_list_of_patterns_can_be_viewed()
    {        
        // given some patterns
        $patterns = factory(Pattern::class, 3)->create();

        // act
        $this->get("/api/v1/patterns")
        
        // response assertions
        ->assertJsonCount(3)
        ->response()
            ->assertStatus(200)
            ->assertJsonFragment([ 'name' => $patterns[0]->name ])
            ->assertJsonFragment([ 'name' => $patterns[1]->name ])
            ->assertJsonFragment([ 'name' => $patterns[2]->name ]);
    }

    /** @test */
    function unpublished_patterns_are_not_returned_in_the_list()
    {
        // given some patterns
        factory(Pattern::class, 3)->create();

        // and an unpublished pattern
        factory(Pattern::class)
            ->states('unpublished')
            ->create(['name' => 'unpublished_pattern']); 

        // act
        $this->get("/api/v1/patterns")

        // response assertions
        ->assertJsonCount(3)
        ->response()
            ->assertJsonMissing([ 'name' => 'unpublished_pattern' ]);

    }

    /** @test */
    function a_published_pattern_can_be_unpublished()
    {
        // given a pattern
        $pattern = factory(Pattern::class)->states('published')->create(); 

        // act
        $this->post("/api/v1/patterns/{$pattern->id}/unpublish")

        ->response()
            ->assertStatus(202);

        $this->assertFalse( !! $pattern->fresh()->published_flag );
    }

    /** @test */
    function an_unpublished_pattern_can_be_published()
    {
        // given a pattern
        $pattern = factory(Pattern::class)->states('unpublished')->create(); 

        // act
        $this->post("/api/v1/patterns/{$pattern->id}/publish")

        ->response()
            ->assertStatus(202);

        $this->assertTrue( !! $pattern->fresh()->published_flag );
    }

    /** @test */
    function a_pattern_can_be_viewed()
    {
        $this->disableExceptionHandling();
        
        // given a pattern
        $pattern = factory(Pattern::class)->create(); 

        // act
        $this->get("/api/v1/patterns/{$pattern->id}")

        ->response()
            ->assertStatus(200)
            ->assertJsonFragment([ 'name' => $pattern->name ]);
    }
}