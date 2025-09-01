<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-green-300 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-2xl rounded-xl p-8 w-full max-w-md">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-green-700">إنشاء حساب جديد</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">اسم المستخدم</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">كلمة المرور</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">نوع الحساب</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    <option value="" disabled selected>اختر نوع الحساب</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>مستخدم</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">القسم</label>
                <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    <option value="" disabled selected>اختر القسم</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                إنشاء الحساب
            </button>
        </form>

        <p class="mt-6 text-sm text-center text-gray-600">
            لديك حساب بالفعل؟
            <a href="/login" class="text-green-700 hover:underline font-semibold">تسجيل الدخول</a>
        </p>
    </div>
</body>
</html>
