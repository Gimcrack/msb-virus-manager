<?php

namespace Tests\Unit;

use App\Client;
use App\Pattern;
use Tests\TestCase;
use App\MatchedFile;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MatchedFileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_matched_file_must_have_a_valid_client()
    {
        try {
            factory(MatchedFile::class)->create(['client_id' => null]);
        }
        catch (QueryException $e)
        {
            $this->assertCount(0, MatchedFile::all());
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_matched_file_must_have_a_pattern()
    {
        try {
            factory(MatchedFile::class)->create(['pattern' => null]);
        }
        catch (QueryException $e)
        {
            $this->assertCount(0, MatchedFile::all());
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_matched_file_must_have_a_file()
    {
        try {
            factory(MatchedFile::class)->create(['file' => null]);
        }
        catch (QueryException $e)
        {
            $this->assertCount(0, MatchedFile::all());
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }

    /** @test */
    function a_matched_file_has_an_associated_client()
    {
        // given a matched file with a valid client
        $file = factory(MatchedFile::class)->create([
            'client_id' => factory(Client::class)->create()->id
        ]);

        // make sure the file has a client
        $this->assertInstanceOf(Client::class,$file->client);
    }

    /** @test */
    function a_matched_file_has_an_associated_pattern()
    {
        // given a matched file with a valid pattern
        $file = factory(MatchedFile::class)->create([
            'pattern_id' => factory(Pattern::class)->create()->id
        ]);

        // make sure the file has a pattern
        $this->assertInstanceOf(Pattern::class,$file->pattern);
    }

    /** @test */
    function a_matched_file_can_be_incremented_when_matched_multiple_times()
    {
        $file = factory(MatchedFile::class)->create();

        // act
        $file->incrementMatch();

        $this->assertEquals(2, $file->fresh()->times_matched);
    }

    /** @test */
    function a_matched_file_must_have_a_unique_client_pattern_and_file()
    {
        $file = factory(MatchedFile::class)->create();

        try {
            factory(MatchedFile::class)->create([
                'client_id' => $file->client_id,
                'pattern_id' => $file->pattern_id,
                'file' => $file->file
            ]);
        }

        catch( QueryException $e )
        {
            $this->assertCount(1, MatchedFile::all());
            return;
        }

        $this->fail("Expected a query exception, but did not get one.");
    }
}
