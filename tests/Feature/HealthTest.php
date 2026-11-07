<?php

use Illuminate\Support\Facades\DB;

test('health check reports app and database ok', function () {
    $this->get('/up')
        ->assertOk()
        ->assertExactJson(['app' => 'ok', 'database' => 'ok'])
        ->assertCookieMissing(config('session.cookie'));
});

test('health check returns 503 when the database is down', function () {
    DB::shouldReceive('select')->andThrow(new RuntimeException('no db'));

    $this->get('/up')
        ->assertStatus(503)
        ->assertExactJson(['app' => 'ok', 'database' => 'down']);
});
