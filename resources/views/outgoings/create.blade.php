@extends('layouts.app')

@section('title', 'إضافة عملية صرف')

@section('content')
<div class="max-w-4xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">إضافة عملية صرف جديدة</h1>

    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('outgoings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block mb-2">التاريخ</label>
                <input type="date" name="date" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">الكود</label>
                <input type="text" name="code" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">الصنف</label>
                <input type="text" name="item" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">الوحدة</label>
                <input type="text" name="unit" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">الكمية</label>
                <input type="number" step="0.01" name="quantity" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">سند الصرف</label>
                <input type="text" name="voucher" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">سبب الصرف</label>
                <input type="text" name="reason" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">المستلم</label>
                <input type="text" name="receiver" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block mb-2">القسم الطالب</label>
                <input type="text" name="department" class="w-full border rounded p-2">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2">ملاحظات</label>
                <textarea name="notes" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="md:col-span-2 text-right">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
