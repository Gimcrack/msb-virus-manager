<?php

namespace Tests\Unit;

use App\User;
use App\Client;
use App\Pattern;
use Tests\TestCase;
use App\MatchedFile;
use App\Events\MatchedFileWasMuted;
use App\Events\MatchedFileWasCreated;
use App\Events\MatchedFileWasUnmuted;
use App\Events\MatchedFileWasUpdated;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MatchedFileCreatedNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_be_configured_as_an_admin()
    {
        $user = factory(User::class)->states('admin')->create();

        $this->assertTrue( $user->isAdmin() );
    }

    /** @test */
    function a_user_can_be_promoted_to_an_admin()
    {
        $user = factory(User::class)->create();

        $this->assertFalse( $user->fresh()->isAdmin() );

        $user->promoteToAdmin();

        $this->assertTrue( $user->fresh()->isAdmin() );   
    }
}
