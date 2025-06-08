<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be'Lights</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f8f6f2] text-[#153448] min-h-screen flex flex-col">

    @include('partials.navbar')

    <main class="flex-grow py-8">
        @yield('content')
    </main>

    <footer class="bg-[#153448] text-[#dfd1b7] text-center py-4">
        &copy; {{ date('Y') }} PT Be'Lights. All rights reserved.
    </footer>

    {{-- Notifikasi Visual --}}
    @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    @vite('resources/js/app.js')
@yield('scripts')
@stack('scripts')
</body>
</html>
