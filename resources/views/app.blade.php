<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2C2C2C">
    <meta name="robots" content="{{ $meta['robots'] }}">
    <meta name="description" content="{{ $meta['description'] }}">
    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('/assets/pito-mascotte/PITO_HOMEPAGE_HERO_CREATIVE_V8.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/svg+xml" href="/assets/pito-logo-officieel.svg">
    <title>{{ $meta['title'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">Ga naar de inhoud</a>
    <div id="app"></div>
    <noscript><main class="shell"><h1>{{ $meta['h1'] }}</h1><p>{{ $meta['description'] }}</p><p>Schakel JavaScript in om het actuele aanbod te bekijken.</p></main></noscript>
</body>
</html>
