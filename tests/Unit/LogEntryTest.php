<?php

namespace Tests\Unit;

use App\Client;
use App\LogEntry;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_log_entry_must_have_a_client_id()
    {
        try {
            $log_entry = factory(LogEntry::class)->create(['client_id' => null]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->assertCount(0, LogEntry::all() );
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_log_entry_must_have_an_action()
    {
        try {
            $log_entry = factory(LogEntry::class)->create(['action' => null]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->assertCount(0, LogEntry::all() );
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_log_entry_must_have_a_status()
    {
        try {
            $log_entry = factory(LogEntry::class)->create(['status' => null]);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->assertCount(0, LogEntry::all() );
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_log_entry_has_an_associated_client()
    {
        // given a log entry with a valid client
        $entry = factory(LogEntry::class)->create([
            'client_id' => factory(Client::class)->create()->id
        ]);

        // make sure the log entry has a client
        $this->assertInstanceOf(Client::class,$entry->client);
    }
}
