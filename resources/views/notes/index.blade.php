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

    @forelse ($notes as $note)
        <article class="card note">
            <h2>{{ $note->title }}</h2>
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
        <p class="meta">No notes yet.</p>
    @endforelse
@endsection
