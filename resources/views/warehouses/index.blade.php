@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">قائمة المخازن</h2>
        <a href="{{ route('warehouses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ إضافة مخزن</a>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">#</th>
                <th class="p-2">اسم المخزن</th>
                <th class="p-2">الوصف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouses as $warehouse)
                <tr class="border-b">
                    <td class="p-2">{{ $warehouse->id }}</td>
                    <td class="p-2">{{ $warehouse->name }}</td>
                    <td class="p-2">{{ $warehouse->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
