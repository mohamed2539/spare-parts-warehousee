<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','نظام المخزن')</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 text-gray-900">
@include('layouts.navbar')
  <main class="container mx-auto p-4">
    @yield('content')
  </main>

  <script>
    // Helper: إحضار التوكن للـ fetch
    function csrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
  </script>
  @yield('scripts')
</body>
</html>
