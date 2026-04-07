<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nutribase')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#06B13D',
                        light: '#FAFCFB',
                        soft: '#D7F487',
                        green: '#79C80E',
                        dark: '#4E6F5C',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #FAFCFB;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="fixed top-0 left-0 right-0 bg-white border-b z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

        <a href="{{ route('home') }}" class="font-bold text-primary text-lg">
            Nutribase
        </a>

        <div class="hidden md:flex gap-8 text-sm font-medium">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'text-primary' : 'text-gray-600' }}">
               Beranda
            </a>

            <a href="{{ route('tentang') }}"
               class="{{ request()->routeIs('tentang') ? 'text-primary' : 'text-gray-600' }}">
               Tentang
            </a>
            <a href="{{ route('kontak') }}"
               class="{{ request()->routeIs('kontak') ? 'text-primary' : 'text-gray-600' }}">
               Kontak
            </a>

            <a href="{{ route('login') }}"
               class="bg-primary text-white px-4 py-2 rounded-lg">
               Masuk
            </a>
        </div>
    </div>
</nav>

<main class="pt-24 min-h-screen">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-white border-t mt-20">
    <div class="max-w-6xl mx-auto px-6 py-6 text-sm text-gray-500 text-center">
        © {{ date('Y') }} Nutribase — Sistem MBG
    </div>
</footer>

</body>
</html>