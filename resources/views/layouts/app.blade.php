<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Notes')</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f6f7f9; color: #1f2328; margin: 0; }
        main { max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: #fff; border: 1px solid #e1e4e8; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
        label { display: block; font-weight: 600; margin: .75rem 0 .25rem; }
        input, textarea { width: 100%; box-sizing: border-box; padding: .5rem; border: 1px solid #d0d7de; border-radius: 6px; font: inherit; }
        button { margin-top: 1rem; padding: .5rem 1rem; border: 0; border-radius: 6px; background: #1f6feb; color: #fff; font: inherit; cursor: pointer; }
        .error { color: #cf222e; font-size: .9rem; margin: .25rem 0 0; }
        .status { background: #dafbe1; border-color: #aceebb; }
        .meta { color: #656d76; font-size: .85rem; }
        .note p { white-space: pre-line; }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>
