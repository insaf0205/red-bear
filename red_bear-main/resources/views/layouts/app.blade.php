<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Red Bear')</title>

    <script src="https://cdn.tailwindcss.com/3.4.17"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Unbounded:wght@700;800&family=Golos+Text:wght@400;500;600&display=swap">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            red:   '#d6202b',
                            dark:  '#b01922',
                            ink:   '#1c1d1f',
                            card:  '#26272a',
                            chalk: '#f1f1ed',
                        },
                        error: '#ff7a7f',
                        plate: { blue: '#2563b8', yellow: '#f0c02e', green: '#1f8a4c' },
                    },
                    fontFamily: {
                        display: ['Unbounded', 'Arial Black', 'sans-serif'],
                        sans:    ['"Golos Text"', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <style>
        :root {
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        *, *::before, *::after { box-sizing: inherit; }

        @media (prefers-reduced-motion: no-preference) { html { scroll-behavior: smooth; } }
        html { scroll-padding-top: 80px; }

        body { background: #1c1d1f; color: #f1f1ed; font-family: "Golos Text", system-ui, sans-serif; }

        .display { font-family: Unbounded, "Arial Black", sans-serif; font-weight: 800; letter-spacing: -0.03em; }

        a:focus-visible { outline: 3px solid #f0c02e; outline-offset: 3px; }
    </style>
</head>
<body class="antialiased">

<main id="top">
    @yield('content')
</main>

<x-toast />

</body>
</html>
