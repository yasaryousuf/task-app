<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertStatus(200);
});

test('task api response', function () {
    User::factory()->count(3)->create();
    Task::factory()->count(3)->create();
    $response = $this->get('/api/task');
    $response->assertHeader('Content-Type', 'application/json')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => ['*' => [
                'id',
                'title',
                'description',
                'priority',
                'status',
                'is_overdue',
                'assigned_to_user' => [
                    'id',
                    'name',
                    'email'
                ],
                'corrective_action'
            ]]
        ]);
});
