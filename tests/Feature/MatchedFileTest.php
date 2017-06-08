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

class MatchedFileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_matched_file_can_be_entered_for_a_valid_client()
    {
        // given a client
        $client = factory(Client::class)->create();

        // act
        $this->post("/api/v1/clients/{$client->name}/matches", [
            'file' => 'some_file',
            'pattern_id' => factory(Pattern::class)->create()->id
        ])

        // database assertions
        ->assertDatabaseHas('matched_files', [
            'client_id' => $client->id,
            'file' => 'some_file'
        ])
        
        // response assertions
        ->response()
            ->assertStatus(201);
    }

    /** @test */
    function a_matched_file_cannot_be_entered_for_an_invalid_client()
    {
        // act
        $this->post("/api/v1/clients/invalid_client_name/matches", [
            'file' => 'some_file',
            'pattern_id' => factory(Pattern::class)->create()->id
        ])

        // database assertions
        ->assertDatabaseMissing('matched_files', [
            'file' => 'some_file'
        ])
        
        // response assertions
        ->response()
            ->assertStatus(404);
    }

    /** @test */
    function a_matched_file_cannot_be_entered_for_an_invalid_pattern()
    {
        // given a valid client
        $client = factory(Client::class)->create();

        // act
        $this->from("/api/v1/clients/{$client->name}/matches")
            ->post("/api/v1/clients/{$client->name}/matches", [
                'file' => 'some_file',
                'pattern_id' => 9999 // some invalid pattern id
            ])

        // database assertions
        ->assertDatabaseMissing('matched_files', [
            'file' => 'some_file'
        ])
        
        // response assertions
        ->response()
            ->assertRedirect("/api/v1/clients/{$client->name}/matches");
    }

    /** @test */
    function a_matched_file_is_incremented_when_it_is_matched_again_by_the_same_client()
    {
        $this->disableExceptionHandling();
        
        // given a matched file
        $file = factory(MatchedFile::class)->create();

        // act
        $this->post("/api/v1/clients/{$file->client->name}/matches", [
            'file' => $file->file,
            'pattern_id' => $file->pattern_id
        ])

        ->response()
            ->assertStatus(202);

        $this->assertEquals(2, $file->fresh()->times_matched);
    }

    
    
}