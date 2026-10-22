<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Garuda - Booking Tiket Pesawat')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white text-gray-900">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 py-10 mt-20">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm">
            &copy; {{ date('Y') }} Garuda. All rights reserved.
        </div>
    </footer>

</body>
</html>