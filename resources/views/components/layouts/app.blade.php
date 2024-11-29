@props([
    'title',
    'slot',
])

    <!DOCTYPE html>
<html lang="de" class="text-zinc-900 dark:text-zinc-100">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf_token" content="{{ csrf_token() }}"/>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon-emoji.png') }}">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 text-base">

<div class="w-full h-full min-h-dvh p-8">
    {{ $slot }}
</div>

@fluxScripts
</body>
</html>
