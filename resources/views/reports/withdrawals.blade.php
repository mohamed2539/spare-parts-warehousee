@extends('layouts.app')
@section('title','تقرير الصرف')

@section('content')
<div class="max-w-7xl mx-auto p-6" x-data="wdReport()">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">تقرير الصرف</h1>
        <a :href="exportUrl()" class="bg-green-600 text-white px-4 py-2 rounded">⬇️ تصدير Excel</a>
    </div>

    <div class="bg-white rounded-xl shadow p-4 mb-4 grid md:grid-cols-6 gap-3">
        <input x-model="filters.q" class="border p-2 rounded" placeholder="بحث بالكود/الصنف">
        <input x-model="filters.from" type="date" class="border p-2 rounded">
        <input x-model="filters.to" type="date" class="border p-2 rounded">
        <input x-model="filters.department" class="border p-2 rounded" placeholder="القسم الطالب">
        <input x-model="filters.receiver" class="border p-2 rounded" placeholder="المستلم">
        <button @click="load()" class="bg-blue-600 text-white rounded px-4">بحث</button>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right">التاريخ</th>
                    <th class="px-4 py-2 text-right">الكود</th>
                    <th class="px-4 py-2 text-right">الصنف</th>
                    <th class="px-4 py-2 text-right">الوحدة</th>
                    <th class="px-4 py-2 text-right">الكمية</th>
                    <th class="px-4 py-2 text-right">سند الصرف</th>
                    <th class="px-4 py-2 text-right">سبب الصرف</th>
                    <th class="px-4 py-2 text-right">المستلم</th>
                    <th class="px-4 py-2 text-right">القسم الطالب</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="row in rows.data" :key="row.id">
                    <tr class="border-t">
                        <td class="px-4 py-2" x-text="row.date"></td>
                        <td class="px-4 py-2" x-text="row.code"></td>
                        <td class="px-4 py-2" x-text="row.item"></td>
                        <td class="px-4 py-2" x-text="row.unit"></td>
                        <td class="px-4 py-2" x-text="row.quantity"></td>
                        <td class="px-4 py-2" x-text="row.voucher"></td>
                        <td class="px-4 py-2" x-text="row.reason"></td>
                        <td class="px-4 py-2" x-text="row.receiver"></td>
                        <td class="px-4 py-2" x-text="row.request_department"></td>
                    </tr>
                </template>
                <tr x-show="(rows.data ?? []).length===0">
                    <td class="px-4 py-6 text-center text-gray-500" colspan="9">لا بيانات</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex gap-2 mt-4" x-show="rows.total">
        <button class="px-3 py-1 border rounded" :disabled="!rows.prev_page_url" @click="go(rows.prev_page_url)">السابق</button>
        <div class="px-3 py-1">صفحة <span x-text="rows.current_page"></span> من <span x-text="rows.last_page"></span></div>
        <button class="px-3 py-1 border rounded" :disabled="!rows.next_page_url" @click="go(rows.next_page_url)">التالي</button>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function wdReport(){
    return {
        filters: { q:'', from:'', to:'', department:'', receiver:'' },
        rows: { data:[] },
        url(){
            const p = new URLSearchParams(this.filters).toString();
            return `{{ route('reports.withdrawals') }}?ajax=1&${p}`;
        },
        exportUrl(){
            const p = new URLSearchParams(this.filters).toString();
            return `{{ route('reports.withdrawals.export') }}?${p}`;
        },
        async load(){ this.rows = (await axios.get(this.url())).data; },
        async go(u){
            const url = new URL(u); url.searchParams.set('ajax','1');
            this.rows = (await axios.get(url.toString())).data;
        },
        async init(){ await this.load(); }
    }
}
</script>
@endsection
@endsection
