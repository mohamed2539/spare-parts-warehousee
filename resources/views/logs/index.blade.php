@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">📜 سجل الحذف</h2>

    <table class="w-full border border-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">الموديل</th>
                <th class="p-2 border">رقم السجل</th>
                <th class="p-2 border">تفاصيل</th>
                <th class="p-2 border">تم الحذف بواسطة</th>
                <th class="p-2 border">الوقت</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="p-2 border">{{ $log->id }}</td>
                <td class="p-2 border">{{ $log->model }}</td>
                <td class="p-2 border">{{ $log->record_id }}</td>
                <td class="p-2 border">{{ Str::limit($log->details, 50) }}</td>
                <td class="p-2 border">{{ $log->deleted_by }}</td>
                <td class="p-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
