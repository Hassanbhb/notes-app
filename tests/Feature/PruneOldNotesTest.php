<?php

use App\Models\Note;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

test('notes older than 30 days are deleted with their attachments', function () {
    Storage::fake('public');
    Log::spy();
    $path = UploadedFile::fake()->image('old.png')->store('attachments', 'public');

    $old = Note::factory()->create(['created_at' => now()->subDays(31), 'attachment_path' => $path]);
    $recent = Note::factory()->create(['created_at' => now()->subDays(29)]);

    $this->artisan('notes:prune')
        ->expectsOutput('Deleted 1 notes older than 30 days.')
        ->assertSuccessful();

    expect(Note::find($old->id))->toBeNull()
        ->and(Note::find($recent->id))->not->toBeNull();
    Storage::disk('public')->assertMissing($path);
    Log::shouldHaveReceived('info')->with('notes:prune deleted 1 notes older than 30 days.');
});

test('notes:prune is scheduled daily', function () {
    $this->artisan('schedule:list')->expectsOutputToContain('notes:prune');
});
