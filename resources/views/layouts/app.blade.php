<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Stride Footwear | Premium Minimalist Footwear' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Compiled Tailwind CSS & Alpine.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-fixed">

    <div class="fixed top-0 left-0 right-0 z-50">
        <!-- Announcement Bar -->
        <x-announcement-bar />

        <!-- Header Navigation -->
        <x-header />
    </div>

    <!-- Mini Cart Drawer & Backdrop -->
    <x-mini-cart />

    <!-- Live Search Overlay -->
    <x-search-overlay />

    <!-- Main Content Area with Header Offset -->
    <main class="w-full pt-28 bg-surface min-h-[calc(100vh-120px)]">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Toast Notification -->
    <x-toast />

</body>
</html>
