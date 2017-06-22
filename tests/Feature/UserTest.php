<?php

namespace Tests\Feature;

use App\User;
use App\Client;
use Carbon\Carbon;
use Tests\TestCase;
use App\Events\ClientShouldUpgrade;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_be_promoted_to_admin_by_another_admin()
    {   
        $user = factory(User::class)->create();

        $this
            ->actingAsAdmin()
            ->post("api/v1/users/{$user->id}/promote")

            ->response()
                ->assertStatus(202);
        
        $this->assertTrue( $user->fresh()->isAdmin() );
    }

    /** @test */
    function a_user_cannot_be_promoted_to_admin_by_a_nonadmin()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAsUser()
            ->post("api/v1/users/{$user->id}/promote")

            ->response()
                ->assertStatus(422);
        
        $this->assertFalse( $user->fresh()->isAdmin() );
    }

    /** @test */
    function a_listing_of_users_can_be_retrieved_by_an_admin()
    {
        $users = factory(User::class,3)->create();

        $this
            ->actingAsAdmin()
            ->get("api/v1/users")
            ->response()
                ->assertStatus(200);

        $this->assertJsonCount(4);

    }
    
}