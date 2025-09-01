@extends('layouts.app')

@section('title', 'عمليات الصرف')

@section('content')
<div class="max-w-7xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📦 قائمة عمليات الصرف</h1>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('outgoings.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                ➕ إضافة صرف جديد
            </a>
            <form method="POST" action="{{ route('outgoings.destroyAll') }}">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('⚠️ هل أنت متأكد من مسح كل العمليات؟')"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow transition">
                    🗑️ مسح الكل
                </button>
            </form>
        </div>
    </div>

    {{-- رسائل نجاح --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5 shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form id="bulk-delete-form" method="POST" action="{{ route('outgoings.destroySelected') }}">
        @csrf
        @method('DELETE')

        {{-- أكشنات الجدول --}}
        <div class="mb-4 flex items-center space-x-3 space-x-reverse">
            <button type="submit"
                    onclick="return confirm('⚠️ هل أنت متأكد من حذف المحدد؟')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition">
                🗑️ حذف المحدد
            </button>
        </div>

        {{-- الجدول --}}
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="w-full table-auto text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-3 py-3 border"><input type="checkbox" id="select-all"></th>
                        <th class="px-3 py-3 border">📅 التاريخ</th>
                        <th class="px-3 py-3 border">🔢 الكود</th>
                        <th class="px-3 py-3 border">📦 الصنف</th>
                        <th class="px-3 py-3 border">⚖️ الوحدة</th>
                        <th class="px-3 py-3 border">🔢 الكمية</th>
                        <th class="px-3 py-3 border">📑 سند الصرف</th>
                        <th class="px-3 py-3 border">👤 المستلم</th>
                        <th class="px-3 py-3 border">🏢 القسم</th>
                        <th class="px-3 py-3 border">📌 السبب</th>
                        <th class="px-3 py-3 border">📝 ملاحظات</th>
                        <th class="px-3 py-3 border">⚙️ إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outgoings as $outgoing)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2 border text-center">
                                <input type="checkbox" name="ids[]" value="{{ $outgoing->id }}">
                            </td>
                            <td class="px-3 py-2 border">{{ $outgoing->date }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->code }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->item }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->unit }}</td>
                            <td class="px-3 py-2 border font-semibold text-blue-700">{{ $outgoing->quantity }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->voucher }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->receiver }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->department }}</td>
                            <td class="px-3 py-2 border">{{ $outgoing->reason }}</td>
                            <td class="px-3 py-2 border text-gray-500">{{ $outgoing->notes }}</td>
                            <td class="px-3 py-2 border">
                                <form action="{{ route('outgoings.destroy', $outgoing->id) }}" method="POST"
                                      onsubmit="return confirm('⚠️ هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
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
    </form>

    {{-- الباجينج --}}
    <div class="mt-6">
        {{ $outgoings->links() }}
    </div>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
