<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $favoritesOnly = $request->boolean('favorites');

        return view('notes.index', [
            'favoritesOnly' => $favoritesOnly,
            'notes' => $request->user()->notes()
                ->when($favoritesOnly, fn ($query) => $query->where('is_favorite', true))
                ->orderByDesc('is_favorite')->latest()->get(),
        ]);
    }

    public function toggleFavorite(Request $request, Note $note)
    {
        abort_unless($note->user_id === $request->user()->id, 404);

        $note->update(['is_favorite' => ! $note->is_favorite]);

        return redirect()->back(fallback: route('notes.index'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            // No SVG/HTML: files are served from the public disk and could run scripts.
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf,txt', 'max:5120'],
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('attachments', 'public');
        }

        $request->user()->notes()->create($data);

        return redirect()->route('notes.index')->with('status', 'Note created.');
    }
}
