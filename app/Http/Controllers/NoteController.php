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
        $request->user()->notes()->create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
        ]));

        return redirect()->route('notes.index')->with('status', 'Note created.');
    }
}
