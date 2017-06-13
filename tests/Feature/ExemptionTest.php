<?php

namespace Tests\Feature;

use App\Client;
use App\Exemption;
use Carbon\Carbon;
use Tests\TestCase;
use App\MatchedFile;
use App\Events\ExemptionWasCreated;
use App\Events\ExemptionWasUpdated;
use App\Events\ExemptionWasDestroyed;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExemptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_list_of_exemptions_can_be_viewed()
    {        
        // given some exemptions
        $exemptions = factory(Exemption::class, 3)->create();

        // act
        $this->get("/api/v1/exemptions")
        
        // response assertions
        ->assertJsonCount(3)
        ->response()
            ->assertStatus(200)
            ->assertJsonFragment([ 'pattern' => $exemptions[0]->pattern ])
            ->assertJsonFragment([ 'pattern' => $exemptions[1]->pattern ])
            ->assertJsonFragment([ 'pattern' => $exemptions[2]->pattern ]);
    }

    /** @test */
    function unpublished_exemptions_are_not_returned_in_the_list()
    {
        // given some exemptions
        factory(Exemption::class, 3)->create();

        // and an unpublished exemption
        factory(Exemption::class)
            ->states('unpublished')
            ->create(['pattern' => 'unpublished_exemption']); 

        // act
        $this->get("/api/v1/exemptions")

        // response assertions
        ->assertJsonCount(3)
        ->response()
            ->assertJsonMissing([ 'pattern' => 'unpublished_exemption' ]);

    }

    /** @test */
    function a_published_exemption_can_be_unpublished()
    {
        // given a exemption
        $exemption = factory(Exemption::class)->states('published')->create(); 

        // act
        $this->post("/api/v1/exemptions/{$exemption->id}/unpublish")

        ->response()
            ->assertStatus(202);

        $this->assertFalse( !! $exemption->fresh()->published_flag );
    }

    /** @test */
    function an_unpublished_exemption_can_be_published()
    {
        $this->disableExceptionHandling();
        // given a exemption
        $exemption = factory(Exemption::class)->states('unpublished')->create(); 

        // act
        $this->post("/api/v1/exemptions/{$exemption->id}/publish")

        ->response()
            ->assertStatus(202);

        $this->assertTrue( !! $exemption->fresh()->published_flag );
    }

    /** @test */
    function an_exemption_can_be_viewed()
    {
        // given a exemption
        $exemption = factory(Exemption::class)->create(); 

        // act
        $this->get("/api/v1/exemptions/{$exemption->id}")

        ->response()
            ->assertStatus(200)
            ->assertJsonFragment([ 'pattern' => $exemption->pattern ]);
    }

    /** @test */
    function an_event_is_dispatched_when_an_exemption_is_created()
    {
        $exemption = factory(Exemption::class)->create();

        $this->assertEvent(ExemptionWasCreated::class, [ 'exemption' => $exemption ]);
    }

    /** @test */
    function an_event_is_dispatched_when_an_exemption_is_updated()
    {   
        // given a published exemption
        $exemption = factory(Exemption::class)->create();

        // act - update the exemption
        $exemption->update([
            'pattern' => 'new_pattern'
        ]);

        $this->assertEvent(ExemptionWasUpdated::class, [ 'exemption' => $exemption ]);
    }

    /** @test */
    function an_event_is_dispatched_when_an_exemption_is_destroyed()
    {   
        // given a exemption
        $exemption = factory(Exemption::class)->create();

        // act - update the exemption
        $exemption->delete();

        $this->assertEvent(ExemptionWasDestroyed::class, [ 'exemption' => $exemption ]);
    }
}