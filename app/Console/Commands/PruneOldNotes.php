<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Signature('notes:prune {--days=30 : Delete notes older than this many days}')]
#[Description('Delete notes older than the given number of days, with their attachments')]
class PruneOldNotes extends Command
{
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $deleted = 0;

        Note::where('created_at', '<', now()->subDays($days))
            ->chunkById(500, function ($notes) use (&$deleted) {
                Storage::disk('public')->delete($notes->pluck('attachment_path')->filter()->all());
                $deleted += Note::whereKey($notes->modelKeys())->delete();
            });

        Log::info("notes:prune deleted {$deleted} notes older than {$days} days.");
        $this->info("Deleted {$deleted} notes older than {$days} days.");

        return self::SUCCESS;
    }
}
