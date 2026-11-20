<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#064e3b">

    <meta name="description" content="Sistem Informasi Manajemen Apotek untuk mengelola data obat, stok, transaksi, dan laporan.">

    <title>{{ config('app.name', 'MediStock Pharmacy Management') }}</title>

    <!-- APPEARANCE -->
    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <!-- GLOBAL STYLE -->
    <style>
        html {
            background-color: #f8faf9;
            scroll-behavior: smooth;
        }

        html.dark {
            background-color: #090f0c;
        }

        body {
            margin: 0;
            min-height: 100vh;
        }
    </style>

    <!-- MEDISTOCK FAVICON -->
    <link
        rel="icon"
        type="image/svg+xml"
        href="{{ asset('medical-logo.svg') }}?v=1"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('medical-logo.svg') }}"
    >

    <!-- FONTS -->
    @fonts

    <!-- VITE -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.ts',
        "resources/js/pages/{$page['component']}.vue"
    ])

    <!-- INERTIA HEAD -->
    <x-inertia::head>
        <title>{{ config('app.name', 'MediStock Pharmacy Management') }}</title>
    </x-inertia::head>

</head>

<body class="font-sans antialiased">

    <x-inertia::app />

</body>

</html>
