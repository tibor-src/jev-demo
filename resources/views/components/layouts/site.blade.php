@props([
    'title',
    'description',
    'canonical',
    'ogType' => 'website',
    'schemaKey' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $description }}">
        <meta name="author" content="tibor.io">
        <meta name="robots" content="index, follow">
        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:site_name" content="Jev - A Decision Model">
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:image" content="{{ \App\Jev\Pages::Image }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="{{ \App\Jev\Pages::ImageAlt }}">
        <meta property="og:image:width" content="{{ \App\Jev\Pages::ImageWidth }}">
        <meta property="og:image:height" content="{{ \App\Jev\Pages::ImageHeight }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ \App\Jev\Pages::Image }}">
        <meta name="twitter:image:alt" content="{{ \App\Jev\Pages::ImageAlt }}">
        <title>{{ $title }}</title>
        @if (is_string($schemaKey))
            <script type="application/ld+json">{!! \App\Jev\Pages::json($schemaKey) !!}</script>
        @endif
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] antialiased dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-8 px-6 py-12">
            <header class="flex flex-col gap-4">
                <h1 class="text-3xl font-medium tracking-tight">
                    <a href="{{ route('jev.create') }}" class="text-[#f53003] dark:text-[#FF4433]">Jev - A Decision Model</a>
                </h1>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <nav aria-label="Pages">
                        <ul class="flex flex-wrap gap-x-4 gap-y-2 text-sm">
                            @foreach ([
                                ['label' => 'Demo', 'route' => 'jev.create'],
                                ['label' => 'FAQs', 'route' => 'jev.faqs'],
                                ['label' => 'What is Jev?', 'route' => 'jev.what'],
                                ['label' => 'Resources', 'route' => 'jev.resources'],
                                ['label' => 'Lore', 'route' => 'jev.lore'],
                            ] as $item)
                                <li>
                                    <a
                                        href="{{ route($item['route']) }}"
                                        @class([
                                            'font-medium',
                                            'text-[#f53003] dark:text-[#FF4433]' => request()->routeIs($item['route']),
                                            'text-[#1b1b18] dark:text-[#EDEDEC]' => ! request()->routeIs($item['route']),
                                        ])
                                        @if (request()->routeIs($item['route'])) aria-current="page" @endif
                                    >{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                    @isset($lang)
                        {{ $lang }}
                    @endisset
                </div>
            </header>

            {{ $slot }}

            <footer class="flex flex-wrap items-baseline gap-x-4 gap-y-2 text-sm">
                <ul class="flex flex-wrap gap-x-4 gap-y-2">
                    <li><a href="https://typesafe.ai/" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Jev by TypeSafe</a></li>
                    <li><a href="https://packagist.org/packages/laravel/ai" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Laravel AI SDK</a></li>
                    <li><a href="https://docs.typesafe.ai/sdk/javascript" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">TypeSafe JavaScript SDK</a></li>
                    <li><a href="https://openrouter.ai/" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">OpenRouter</a></li>
                </ul>
                <p class="ml-auto text-[#706f6c] dark:text-[#A1A09A]">
                    made by <a href="https://tibor.io" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">tibor.io</a>
                </p>
            </footer>
        </main>
    </body>
</html>
