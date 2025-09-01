@extends('layouts.app')

@section('title', 'قائمة الوارد')

@section('content')
<div class="max-w-6xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">قائمة الوارد</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('incoming.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">إضافة وارد جديد</a>
        <button id="delete-selected" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">حذف المحدد</button>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2"><input type="checkbox" id="select-all"></th>
                    <th class="px-4 py-2 text-right">القسم</th>
                    <th class="px-4 py-2 text-right">التاريخ</th>
                    <th class="px-4 py-2 text-right">الكود</th>
                    <th class="px-4 py-2 text-right">الصنف</th>
                    <th class="px-4 py-2 text-right">الوحدة</th>
                    <th class="px-4 py-2 text-right">الكمية</th>
                    <th class="px-4 py-2 text-right">المورد</th>
                    <th class="px-4 py-2 text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($items as $item)
                <tr>
                    <td class="px-4 py-2 text-center">
                        <input type="checkbox" class="select-item" value="{{ $item->id }}">
                    </td>
                    <td class="px-4 py-2 text-right">{{ $item->department }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->date->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->code }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->item }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->unit }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                    <td class="px-4 py-2 text-right">{{ $item->supplier }}</td>
                    <td class="px-4 py-2 text-center flex gap-2 justify-center">
                        <a href="{{ route('incoming.edit', $item->id) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">تعديل</a>
                        <form action="{{ route('incoming.destroy', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البند؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // اختيار كل العناصر
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-item');

    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
    });

    // حذف المحدد
    const deleteBtn = document.getElementById('delete-selected');
    deleteBtn.addEventListener('click', () => {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (!selected.length) { alert('اختر البنود التي تريد حذفها'); return; }

        if (!confirm('هل أنت متأكد من حذف البنود المحددة؟')) return;

        axios.post("{{ route('incoming.destroyMultiple') }}", { ids: selected, _token: '{{ csrf_token() }}' })
            .then(res => {
                alert(res.data.message);
                location.reload();
            })
            .catch(err => alert(err.response?.data?.message || 'حدث خطأ'));
    });
</script>
@endsection
