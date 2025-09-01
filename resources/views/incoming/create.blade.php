@extends('layouts.app')

@section('title', 'إضافة وارد يدوي')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 rounded-xl shadow-lg text-white mb-6">
        <h1 class="text-3xl font-bold">إضافة وارد يدوي</h1>
        <p class="mt-1 text-blue-100">أدخل بيانات الوارد يدويًا بطريقة سهلة وسريعة</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-6 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-lg">
        <form action="{{ route('incoming.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">القسم</label>
                    <input type="text" name="department" value="{{ old('department') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">التاريخ</label>
                    <input type="date" name="date" value="{{ old('date') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">الكود</label>
                    <input type="text" name="code" value="{{ old('code') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">الصنف</label>
                    <input type="text" name="item" value="{{ old('item') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('item') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">الوحدة</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">الكمية</label>
                    <input type="number" name="quantity" step="0.01" value="{{ old('quantity') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 font-semibold text-gray-700">المورد</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}" 
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('supplier') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow hover:from-blue-700 hover:to-indigo-700 transition transform hover:scale-105">
                    حفظ الوارد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
