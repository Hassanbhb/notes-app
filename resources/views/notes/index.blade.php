@extends('layouts.app')

@section('content')
    <h1>Notes</h1>

    @if (session('status'))
        <div class="card status" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('notes.store') }}" class="card">
        @csrf
        <label for="title">Title</label>
        <input id="title" name="title" value="{{ old('title') }}" required maxlength="255">
        @error('title') <p class="error">{{ $message }}</p> @enderror

        <label for="body">Body</label>
        <textarea id="body" name="body" rows="4" required>{{ old('body') }}</textarea>
        @error('body') <p class="error">{{ $message }}</p> @enderror

        <button type="submit">Add note</button>
    </form>

    @forelse ($notes as $note)
        <article class="card note">
            <h2>{{ $note->title }}</h2>
            <p class="meta">{{ $note->created_at->diffForHumans() }}</p>
            <p>{{ $note->body }}</p>
        </article>
    @empty
        <p class="meta">No notes yet.</p>
    @endforelse
@endsection
