<?php

use App\Models\Note;
use App\Models\User;

test('users can favorite and unfavorite their notes', function () {
    $note = Note::factory()->create();

    $this->actingAs($note->user)->patch(route('notes.favorite', $note))->assertRedirect('/notes');
    expect($note->fresh()->is_favorite)->toBeTrue();

    $this->actingAs($note->user)->patch(route('notes.favorite', $note));
    expect($note->fresh()->is_favorite)->toBeFalse();
});

test('users cannot favorite someone else\'s note', function () {
    $note = Note::factory()->create();

    $this->actingAs(User::factory()->create())->patch(route('notes.favorite', $note))->assertNotFound();
    expect($note->fresh()->is_favorite)->toBeFalse();
});

test('favorites are listed first', function () {
    $user = User::factory()->create();
    Note::factory()->for($user)->create(['title' => 'Plain newer']);
    Note::factory()->for($user)->create(['title' => 'Starred older', 'is_favorite' => true, 'created_at' => now()->subDay()]);

    $this->actingAs($user)->get('/notes')->assertSeeInOrder(['Starred older', 'Plain newer']);
});

test('the favorites filter shows only favorite notes', function () {
    $user = User::factory()->create();
    Note::factory()->for($user)->create(['title' => 'Plain one']);
    Note::factory()->for($user)->create(['title' => 'Starred one', 'is_favorite' => true]);

    $this->actingAs($user)->get('/notes?favorites=1')
        ->assertSee('Starred one')
        ->assertDontSee('Plain one');
});

test('toggling a favorite keeps the current filter', function () {
    $note = Note::factory()->create(['is_favorite' => true]);

    $this->actingAs($note->user)
        ->from('/notes?favorites=1')
        ->patch(route('notes.favorite', $note))
        ->assertRedirect('/notes?favorites=1');
});

test('prune deletes old favorite notes too', function () {
    $note = Note::factory()->create(['is_favorite' => true, 'created_at' => now()->subDays(40)]);

    $this->artisan('notes:prune')->assertSuccessful();

    expect($note->fresh())->toBeNull();
});
