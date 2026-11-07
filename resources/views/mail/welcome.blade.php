<x-mail::message>
# Welcome, {{ $user->name }}!

Your account is ready. Start writing your first note.

<x-mail::button :url="route('notes.index')">
Open my notes
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
