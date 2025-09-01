@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- العنوان -->
    <h1 class="text-4xl font-extrabold mb-8 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500 flex items-center gap-2 animate-fade-in">
        📦 إدارة المخزون
    </h1>

    <!-- بطاقات إحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/70 backdrop-blur-md shadow-xl rounded-3xl p-6 text-center hover:scale-105 transition-all duration-300">
            <p class="text-sm text-gray-500">إجمالي الأصناف</p>
            <p class="text-2xl font-bold text-indigo-600">{{ count($rows) }}</p>
        </div>
        <div class="bg-white/70 backdrop-blur-md shadow-xl rounded-3xl p-6 text-center hover:scale-105 transition-all duration-300">
            <p class="text-sm text-gray-500">إجمالي الرصيد</p>
            <p class="text-2xl font-bold text-green-600">{{ $rows->sum('balance') }}</p>
        </div>
        <div class="bg-white/70 backdrop-blur-md shadow-xl rounded-3xl p-6 text-center hover:scale-105 transition-all duration-300">
            <p class="text-sm text-gray-500">إجمالي الموردين</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $rows->unique('supplier')->count() }}</p>
        </div>
    </div>

    <!-- الأزرار -->
    <div class="flex flex-wrap gap-3 mb-6">
        <button id="deleteSelected" class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:scale-105 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold transition-all duration-200">
            🗑️ حذف المحدد
        </button>
        <button id="clearAll" class="flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:scale-105 text-white px-6 py-3 rounded-2xl shadow-lg font-semibold transition-all duration-200">
            ⚠️ تصفير المخزون
        </button>
    </div>

    <!-- الجدول -->
    <div class="overflow-x-auto">
        <div class="bg-white/70 backdrop-blur-md shadow-2xl rounded-3xl border border-gray-200">
            <table id="stockTable" class="min-w-full text-gray-700">
                <thead class="bg-gradient-to-r from-indigo-100 to-indigo-200 text-gray-800 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="selectAll" class="rounded focus:ring-2 focus:ring-blue-400"></th>
                        <th class="px-4 py-3">🏷️ الكود</th>
                        <th class="px-4 py-3">📦 الصنف</th>
                        <th class="px-4 py-3">⚖️ الوحدة</th>
                        <th class="px-4 py-3">🏭 القسم</th>
                        <th class="px-4 py-3">🤝 المورد</th>
                        <th class="px-4 py-3 text-green-700">⬆️ إجمالي وارد</th>
                        <th class="px-4 py-3 text-red-700">⬇️ إجمالي صرف</th>
                        <th class="px-4 py-3 text-indigo-700">📊 الرصيد</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($rows as $row)
                        <tr data-code="{{ $row->code }}" class="hover:bg-indigo-50 hover:scale-[1.01] transition-all duration-150">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" name="selected[]" value="{{ $row->code }}" class="rounded focus:ring-2 focus:ring-blue-400">
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $row->code }}</td>
                            <td class="px-4 py-3">{{ $row->item }}</td>
                            <td class="px-4 py-3">{{ $row->unit }}</td>
                            <td class="px-4 py-3">{{ $row->department }}</td>
                            <td class="px-4 py-3">{{ $row->supplier }}</td>
                            <td class="px-4 py-3 text-green-600 font-bold">{{ $row->total_in }}</td>
                            <td class="px-4 py-3 text-red-600 font-bold">{{ $row->total_out }}</td>
                            <td class="px-4 py-3 text-indigo-700 font-extrabold">{{ $row->balance }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // تحديد الكل
    $('#selectAll').click(function () {
        $('input[name="selected[]"]').prop('checked', this.checked);
    });

    // حذف الصفوف المحددة بدون Reload
    $('#deleteSelected').click(function(e){
        e.preventDefault();
        let selected = $('input[name="selected[]"]:checked').map(function(){ return this.value }).get();

        if(selected.length === 0){
            alert('اختر على الأقل صنف واحد للحذف');
            return;
        }

        if(confirm('هل أنت متأكد من حذف العناصر المحددة؟')){
            $.ajax({
                url: "{{ route('stock.bulkDelete') }}",
                method: 'DELETE',
                data: {_token: "{{ csrf_token() }}", selected: selected},
                success: function(res){
                    alert(res.message);
                    selected.forEach(code => {
                        $('#stockTable').find(`tr[data-code="${code}"]`).remove();
                    });
                }
            });
        }
    });

    // تصفير المخزون بالكامل بدون Reload
    $('#clearAll').click(function(e){
        e.preventDefault();
        if(confirm('هل أنت متأكد من تصفير المخزون بالكامل؟')){
            $.ajax({
                url: "{{ route('stock.clearAll') }}",
                method: 'DELETE',
                data: {_token: "{{ csrf_token() }}"},
                success: function(res){
                    alert(res.message);
                    $('#stockTable tbody').empty();
                },
                error: function(xhr){
                    alert('حدث خطأ: ' + xhr.responseJSON?.error || 'غير معروف');
                }
            });
        }
    });

});
</script>

<style>
@keyframes fade-in {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.6s ease-out forwards; }
</style>
@endsection
