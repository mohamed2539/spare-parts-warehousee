@extends('layouts.app')

@section('title', 'عمليات الصرف')

@section('content')
<div class="max-w-7xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">💸 عمليات الصرف</h1>
    </div>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5 shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('withdrawals.destroyMultiple') }}">
        @csrf
        @method('DELETE')

        {{-- الجدول --}}
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="w-full table-auto text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 border"><input type="checkbox" id="select-all"></th>
                        <th class="px-3 py-2 border">📅 التاريخ</th>
                        <th class="px-3 py-2 border">🔢 الكود</th>
                        <th class="px-3 py-2 border">📦 الصنف</th>
                        <th class="px-3 py-2 border">⚖️ الوحدة</th>
                        <th class="px-3 py-2 border">🔢 الكمية</th>
                        <th class="px-3 py-2 border">📑 سند الصرف</th>
                        <th class="px-3 py-2 border">📌 السبب</th>
                        <th class="px-3 py-2 border">👤 المستلم</th>
                        <th class="px-3 py-2 border">🏢 القسم الطالب</th>
                        <th class="px-3 py-2 border">📝 ملاحظات</th>
                        <th class="px-3 py-2 border">⚙️ إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2 border text-center">
                                <input type="checkbox" name="ids[]" value="{{ $w->id }}">
                            </td>
                            <td class="px-3 py-2 border">{{ $w->date }}</td>
                            <td class="px-3 py-2 border">{{ $w->code }}</td>
                            <td class="px-3 py-2 border">{{ $w->item }}</td>
                            <td class="px-3 py-2 border">{{ $w->unit }}</td>
                            <td class="px-3 py-2 border font-semibold text-blue-700">{{ $w->quantity }}</td>
                            <td class="px-3 py-2 border">{{ $w->voucher }}</td>
                            <td class="px-3 py-2 border">{{ $w->reason }}</td>
                            <td class="px-3 py-2 border">{{ $w->receiver }}</td>
                            <td class="px-3 py-2 border">{{ $w->request_department }}</td>
                            <td class="px-3 py-2 border text-gray-500">{{ $w->notes }}</td>
                            <td class="px-3 py-2 border">
                                <form method="POST" action="{{ route('withdrawals.destroy', $w->id) }}"
                                      onsubmit="return confirm('⚠️ هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow text-xs transition">
                                        🗑️ حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-gray-500">🚫 لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- زرار حذف المحدد --}}
        <div class="mt-5">
            <button type="submit"
                    onclick="return confirm('⚠️ هل أنت متأكد من حذف المحدد؟')"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition">
                🗑️ حذف المحدد
            </button>
        </div>
    </form>

    {{-- الباجينج --}}
    <div class="mt-6">
        {{ $withdrawals->links() }}
    </div>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
