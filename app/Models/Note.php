<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory;

    protected $fillable = ['title', 'body', 'attachment_path', 'is_favorite'];

    protected $casts = ['is_favorite' => 'boolean'];

    public function attachmentIsImage(): bool
    {
        return in_array(strtolower(pathinfo((string) $this->attachment_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
