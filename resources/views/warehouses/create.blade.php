@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-bold mb-4">إضافة مخزن جديد</h2>

    <form action="{{ route('warehouses.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1">اسم المخزن</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
            @error('name')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1">الوصف</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">حفظ</button>
    </form>
@endsection
