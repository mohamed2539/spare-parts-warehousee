<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- نافبار -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-700">لوحة التحكم</h1>
        <div class="hidden md:flex items-center space-x-6">

            <a href="{{ route('incoming.create') }}" class="text-gray-700 hover:text-blue-600 font-medium">➕ إضافة وارد</a>
            <a href="{{ route('withdrawals.create') }}" class="text-gray-700 hover:text-blue-600 font-medium">📝 صرف يدوي</a>

            <!-- Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @keydown.escape="open = false"
                        class="flex items-center text-gray-700 hover:text-blue-600 font-medium focus:outline-none">
                    المزيد
                    <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute right-0 mt-2 w-56 bg-white border rounded-md shadow-lg z-50">
                    <a href="{{ route('imports.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">📥 استيراد وارد</a>
                    <a href="{{ route('incoming.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">📦 الوارد</a>
                    <a href="{{ route('withdrawals.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">📤 عرض الصرف</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('items.searchPage') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">🔍 بحث عن صنف</a>
                    <a href="{{ route('items.addForm') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">➕ إضافة كمية</a>
                </div>
            </div>
        </div>

        <!-- زر تسجيل خروج -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-600 text-white px-4 py-2 rounded-lg">تسجيل الخروج</button>
        </form>
    </nav>

    <!-- المحتوى -->
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">مرحباً {{ auth()->user()->name }}</h2>

        <!-- الكروت -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">

            <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center">
                <p class="text-2xl font-bold text-blue-600">150</p>
                <p class="text-gray-600 mt-2">إجمالي الأصناف</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center">
                <p class="text-2xl font-bold text-green-600">12,350</p>
                <p class="text-gray-600 mt-2">إجمالي الكمية بالمخزن</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center">
                <p class="text-2xl font-bold text-indigo-600">1,250</p>
                <p class="text-gray-600 mt-2">إجمالي الوارد</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center">
                <p class="text-2xl font-bold text-red-600">980</p>
                <p class="text-gray-600 mt-2">إجمالي المصروف</p>
            </div>
        </div>

        <!-- الرسم البياني -->
        <div class="bg-white shadow rounded-xl p-6 mt-10">
            <h2 class="text-xl font-bold text-gray-700 mb-4">تقرير الوارد والمصروف (شهري)</h2>
            <canvas id="statsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو'],
                datasets: [
                    {
                        label: 'الوارد',
                        data: [120, 90, 150, 80, 200, 170],
                        backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    },
                    {
                        label: 'المصروف',
                        data: [100, 70, 120, 60, 150, 130],
                        backgroundColor: 'rgba(220, 38, 38, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    </script>

</body>
</html>
