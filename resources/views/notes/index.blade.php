@extends('layouts.app')

@section('content')
    <h1>Notes</h1>

    @if (session('status'))
        <div class="card status" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" class="card">
        @csrf
        <label for="title">Title</label>
        <input id="title" name="title" value="{{ old('title') }}" required maxlength="255">
        @error('title') <p class="error">{{ $message }}</p> @enderror

        <label for="body">Body</label>
        <textarea id="body" name="body" rows="4" required>{{ old('body') }}</textarea>
        @error('body') <p class="error">{{ $message }}</p> @enderror

        <label for="attachment">Attachment <span class="meta">(optional: image, PDF or text, max 5 MB)</span></label>
        <input id="attachment" type="file" name="attachment" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.txt">
        @error('attachment') <p class="error">{{ $message }}</p> @enderror

        <button type="submit">Add note</button>
    </form>

    <p>
        @if ($favoritesOnly)
            <strong>Showing favorites</strong> · <a href="{{ route('notes.index') }}">Show all notes</a>
        @else
            <a href="{{ route('notes.index', ['favorites' => 1]) }}">★ Show only favorites</a>
        @endif
    </p>

    @forelse ($notes as $note)
        <article class="card note">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <h2>{{ $note->title }}</h2>
                <form method="POST" action="{{ route('notes.favorite', $note) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="star" aria-pressed="{{ $note->is_favorite ? 'true' : 'false' }}"
                        aria-label="{{ $note->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}">
                        {{ $note->is_favorite ? '★' : '☆' }}
                    </button>
                </form>
            </div>
            <p class="meta">{{ $note->created_at->diffForHumans() }}</p>
            <p>{{ $note->body }}</p>
            @if ($note->attachment_path)
                @php($url = Storage::disk('public')->url($note->attachment_path))
                @if ($note->attachmentIsImage())
                    <a href="{{ $url }}" target="_blank"><img src="{{ $url }}" alt="Attachment for {{ $note->title }}" style="max-width: 200px; border-radius: 6px;"></a>
                @else
                    <a href="{{ $url }}" target="_blank">View attachment ({{ strtoupper(pathinfo($note->attachment_path, PATHINFO_EXTENSION)) }})</a>
                @endif
            @endif
        </article>
    @empty
        <p class="meta">{{ $favoritesOnly ? 'No favorite notes yet.' : 'No notes yet.' }}</p>
    @endforelse
@endsection
