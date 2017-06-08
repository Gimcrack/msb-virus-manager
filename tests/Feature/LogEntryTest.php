<?php

namespace Tests\Feature;

use App\Client;
use App\LogEntry;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class LogEntryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_log_entry_can_be_entered_for_a_valid_client()
    {
        // given a client
        $client = factory(Client::class)->create();

        // act - post a log entry for the client
        $this->post("/api/v1/clients/{$client->name}/logs", [
            'action' => 'some_action',
            'status' => 'some_status'
        ])

        // database assertions
        ->assertDatabaseHas('log_entries', [
            'client_id' => $client->id,
            'action' => 'some_action',
            'status' => 'some_status'
        ])
        
        // response assertions
        ->response()
            ->assertStatus(201);
    }

    /** @test */
    function a_log_entry_cannot_be_entered_for_an_invalid_client()
    {
        // act - post a log entry for the client
        $this->post("/api/v1/clients/invalid_client_name/logs", [
            'action' => 'some_action',
            'status' => 'some_status'
        ])

        // database assertions
        ->assertDatabaseMissing('log_entries', [
            'action' => 'some_action',
            'status' => 'some_status'
        ])
        
        // response assertions
        ->response()
            ->assertStatus(404);
    }

    
    
}