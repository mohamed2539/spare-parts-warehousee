@extends('layouts.app')

@section('title', 'سجل الوارد')

@section('content')
<div class="max-w-6xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">سجل الوارد</h1>

    @if(session('success'))
      <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <a href="{{ route('imports.export') }}" class="px-4 py-2 bg-blue-600 text-white rounded mb-4 inline-block">تصدير Excel</a>

    <table class="min-w-full table-auto text-sm border">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-3 py-2">القسم</th>
                <th class="px-3 py-2">التاريخ</th>
                <th class="px-3 py-2">الكود</th>
                <th class="px-3 py-2">الصنف</th>
                <th class="px-3 py-2">الوحدة</th>
                <th class="px-3 py-2">الكمية</th>
                <th class="px-3 py-2">المورد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td class="border px-2 py-1">{{ $item->department }}</td>
                <td class="border px-2 py-1">{{ $item->date->format('Y-m-d') }}</td>
                <td class="border px-2 py-1">{{ $item->code }}</td>
                <td class="border px-2 py-1">{{ $item->item }}</td>
                <td class="border px-2 py-1">{{ $item->unit }}</td>
                <td class="border px-2 py-1">{{ $item->quantity }}</td>
                <td class="border px-2 py-1">{{ $item->supplier }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection
