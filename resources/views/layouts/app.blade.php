<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Story Blog')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50 min-h-screen flex flex-col">
    @include('partials.header')
    <main class="container mx-auto p-4 flex-1">
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>


