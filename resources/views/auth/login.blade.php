<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-indigo-300 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-2xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-indigo-700">تسجيل الدخول</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">اسم المستخدم</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">كلمة المرور</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                تسجيل الدخول
            </button>
        </form>

        <p class="mt-6 text-sm text-center text-gray-600">
            ليس لديك حساب؟
            <a href="/register" class="text-indigo-700 hover:underline font-semibold">إنشاء حساب جديد</a>
        </p>
    </div>
</body>
</html>
