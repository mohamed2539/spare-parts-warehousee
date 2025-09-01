@extends('layouts.app')

@section('title','طلبات الشراء')

@section('content')
<div class="max-w-7xl mx-auto p-6" x-data="prPage()">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">طلبات الشراء</h1>
        <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ طلب جديد</button>
    </div>

    <!-- فلاتر بسيطة -->
    <form method="get" class="mb-4 flex gap-3">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="بحث بالكود / الصنف / المورد" class="border p-2 rounded w-64">
        <select name="status" class="border p-2 rounded">
            <option value="">كل الحالات</option>
            @foreach(['pending'=>'قيد الانتظار','ordered'=>'تم الطلب','received'=>'تم الاستلام','cancelled'=>'ملغي'] as $k=>$v)
                <option value="{{ $k }}" @selected(($status ?? '') === $k)>{{ $v }}</option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-gray-800 text-white rounded">بحث</button>
        <a href="{{ route('purchase-requests.index') }}" class="px-4 py-2 bg-gray-200 rounded">مسح</a>
    </form>

    <!-- الجدول -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <th class="px-4 py-3 text-right">الكود</th>
                    <th class="px-4 py-3 text-right">الصنف</th>
                    <th class="px-4 py-3 text-right">الوحدة</th>
                    <th class="px-4 py-3 text-right">الكمية</th>
                    <th class="px-4 py-3 text-right">المورد</th>
                    <th class="px-4 py-3 text-right">القسم الطالب</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($requests as $r)
                <tr>
                    <td class="px-4 py-2">{{ optional($r->date)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">{{ $r->code }}</td>
                    <td class="px-4 py-2">{{ $r->item }}</td>
                    <td class="px-4 py-2">{{ $r->unit }}</td>
                    <td class="px-4 py-2">{{ rtrim(rtrim(number_format($r->requested_qty,4,'.',''), '0'), '.') }}</td>
                    <td class="px-4 py-2">{{ $r->supplier }}</td>
                    <td class="px-4 py-2">{{ $r->request_department }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-white
                            @switch($r->status)
                                @case('pending') bg-yellow-500 @break
                                @case('ordered') bg-blue-600 @break
                                @case('received') bg-green-600 @break
                                @case('cancelled') bg-red-600 @break
                            @endswitch
                        ">{{ ['pending'=>'قيد الانتظار','ordered'=>'تم الطلب','received'=>'تم الاستلام','cancelled'=>'ملغي'][$r->status] }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <select class="border p-1 rounded" @change="updateStatus({{ $r->id }}, $event.target.value)">
                            @foreach(['pending','ordered','received','cancelled'] as $st)
                                <option value="{{ $st }}" @selected($r->status===$st)>{{ $st }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
                @if(!$requests->count())
                <tr><td colspan="9" class="px-4 py-4 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>

    <!-- Modal إنشاء طلب -->
    <div x-show="open" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl p-6 space-y-4" @click.outside="open=false">
            <h2 class="text-xl font-semibold">طلب شراء جديد</h2>
            <div class="grid md:grid-cols-2 gap-3">
                <input x-model="form.date" type="date" class="border p-2 rounded" placeholder="التاريخ">
                <input x-model="form.code" type="text" class="border p-2 rounded" placeholder="الكود">
                <input x-model="form.item" type="text" class="border p-2 rounded md:col-span-2" placeholder="اسم الصنف">
                <input x-model="form.unit" type="text" class="border p-2 rounded" placeholder="الوحدة">
                <input x-model.number="form.requested_qty" type="number" step="0.01" class="border p-2 rounded" placeholder="الكمية">
                <input x-model="form.supplier" type="text" class="border p-2 rounded" placeholder="المورد">
                <input x-model="form.request_department" type="text" class="border p-2 rounded" placeholder="القسم الطالب">
                <input x-model="form.requester_name" type="text" class="border p-2 rounded" placeholder="اسم الطالب">
                <textarea x-model="form.reason" class="border p-2 rounded md:col-span-2" placeholder="سبب الطلب"></textarea>
                <textarea x-model="form.notes" class="border p-2 rounded md:col-span-2" placeholder="ملاحظات"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button @click="open=false" class="px-4 py-2 rounded bg-gray-200">إلغاء</button>
                <button @click="submit()" class="px-4 py-2 rounded bg-blue-600 text-white">حفظ</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function prPage(){
    return {
        open: false,
        form: { date:'', code:'', item:'', unit:'', requested_qty:'', supplier:'', request_department:'', requester_name:'', reason:'', notes:'' },
        async submit(){
            try{
                const res = await axios.post("{{ route('purchase-requests.store') }}", this.form);
                alert(res.data.message || 'تم');
                location.reload();
            }catch(e){
                alert(e.response?.data?.message || 'خطأ');
            }
        },
        async updateStatus(id, status){
            try{
                await axios.post(`{{ url('/purchase-requests') }}/${id}/status`, { status });
            }catch(e){ alert('تعذر تحديث الحالة'); }
        }
    }
}
</script>
@endsection
@endsection
