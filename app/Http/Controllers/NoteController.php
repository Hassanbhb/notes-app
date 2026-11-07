<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        return view('notes.index', ['notes' => $request->user()->notes()->latest()->get()]);
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
