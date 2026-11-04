<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return view('notes.index', ['notes' => Note::latest()->get()]);
    }

    public function store(Request $request)
    {
        Note::create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
        ]));

        return redirect()->route('notes.index')->with('status', 'Note created.');
    }
}
