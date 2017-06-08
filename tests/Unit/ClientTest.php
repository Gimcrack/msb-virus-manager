<?php

namespace Tests\Unit;

use App\Client;
use App\LogEntry;
use Tests\TestCase;
use App\MatchedFile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_client_must_have_a_name()
    {
        try {
            $client = factory(Client::class)->create(['name' => null]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->assertCount(0, Client::all() );
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_client_must_have_a_version()
    {
        try {
            $client = factory(Client::class)->create(['version' => null]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->assertCount(0, Client::all() );
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_client_may_have_associated_logs()
    {
        // given a client
        $client = factory(Client::class)->create();
        
        // and some logs associated with the client
        factory(LogEntry::class)->create(['client_id' => $client->id]);
        factory(LogEntry::class)->create(['client_id' => $client->id]);
        factory(LogEntry::class)->create(['client_id' => $client->id]);

        $this->assertCount( 3, $client->logs );
        
    }

    /** @test */
    function a_client_may_have_associated_matched_files()
    {
        // given a client
        $client = factory(Client::class)->create();
        
        // and some logs associated with the client
        factory(MatchedFile::class)->create(['client_id' => $client->id]);
        factory(MatchedFile::class)->create(['client_id' => $client->id]);
        factory(MatchedFile::class)->create(['client_id' => $client->id]);

        $this->assertCount( 3, $client->matched_files );
        
    }
}
