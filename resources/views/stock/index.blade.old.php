@extends('layouts.app')
@section('title','الرصيد الحالي')

@section('content')
<div class="max-w-7xl mx-auto p-6" x-data="stockPage()" x-init="init()">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h1 class="text-2xl font-bold">📦 الرصيد الحالي</h1>
        <a :href="exportUrl()" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">⬇️ تصدير Excel</a>
    </div>

    <div class="bg-white rounded-xl shadow p-4 mb-4">
        <div class="grid md:grid-cols-4 gap-3">
            <input
                x-model.trim="filters.q"
                @input="onFilterInput()"
                type="text"
                placeholder="بحث بالكود/الصنف/الوحدة"
                class="border p-2 rounded">

            <input
                x-model.trim="filters.department"
                @input="onFilterInput()"
                type="text"
                placeholder="القسم (ID أو اسم)"
                class="border p-2 rounded">

            <input
                x-model.trim="filters.supplier"
                @input="onFilterInput()"
                type="text"
                placeholder="المورد"
                class="border p-2 rounded">

            <button @click="load(true)" class="bg-blue-600 text-white rounded px-4">🔍 بحث</button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right">#</th>
                    <th class="px-4 py-2 text-right">الكود</th>
                    <th class="px-4 py-2 text-right">الصنف</th>
                    <th class="px-4 py-2 text-right">الوحدة</th>
                    <th class="px-4 py-2 text-right">القسم</th>
                    <th class="px-4 py-2 text-right">المورد</th>
                    <th class="px-4 py-2 text-right">إجمالي وارد</th>
                    <th class="px-4 py-2 text-right">إجمالي صرف</th>
                    <th class="px-4 py-2 text-right">الرصيد</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row,idx) in rows.data" :key="idx">
                    <tr class="border-t">
                        <td class="px-4 py-2" x-text="(rows.from ?? 1) + idx"></td>
                        <td class="px-4 py-2" x-text="row.code"></td>
                        <td class="px-4 py-2" x-text="row.item"></td>
                        <td class="px-4 py-2" x-text="row.unit"></td>
                        <td class="px-4 py-2" x-text="row.department ?? '-'"></td>
                        <td class="px-4 py-2" x-text="row.supplier ?? '-'"></td>
                        <td class="px-4 py-2" x-text="row.total_in"></td>
                        <td class="px-4 py-2" x-text="row.total_out"></td>
                        <td class="px-4 py-2 font-semibold"
                            :class="Number(row.balance)<=0 ? 'text-red-600' : 'text-gray-800'"
                            x-text="row.balance"></td>
                    </tr>
                </template>
                <tr x-show="(rows.data ?? []).length === 0">
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex gap-2 mt-4" x-show="rows.total">
        <button class="px-3 py-1 border rounded"
                :disabled="!rows.prev_page_url"
                @click="go(rows.prev_page_url)">السابق</button>
        <div class="px-3 py-1">صفحة <span x-text="rows.current_page"></span> من <span x-text="rows.last_page"></span></div>
        <button class="px-3 py-1 border rounded"
                :disabled="!rows.next_page_url"
                @click="go(rows.next_page_url)">التالي</button>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function stockPage(){
    return {
        filters: { q:'', department:'', supplier:'' },
        rows: { data:[] },
        _debounceTimer: null,

        baseUrl(){ return `{{ route('stock.index') }}`; },

        // دايمًا نبدأ من الصفحة 1 عند أي تغيير فلاتر
        url(){ 
            const p = new URLSearchParams(this.filters).toString();
            return `${this.baseUrl()}?ajax=1&${p}`;
        },
        exportUrl(){
            const p = new URLSearchParams(this.filters).toString();
            return `{{ route('stock.export') }}?${p}`;
        },

        async load(force=false){
            // لو مش force، هنستخدم debounce 300ms (أمان إضافي بجانب @input)
            if (!force) {
                clearTimeout(this._debounceTimer);
                this._debounceTimer = setTimeout(async () => {
                    const res = await axios.get(this.url(), { headers:{'X-Requested-With':'XMLHttpRequest'} });
                    this.rows = res.data;
                }, 300);
                return;
            }
            const res = await axios.get(this.url(), { headers:{'X-Requested-With':'XMLHttpRequest'} });
            this.rows = res.data;
        },

        // لما المستخدم يكتب أو يمسح
        onFilterInput(){
            // دايمًا رجّع للصفحة الأولى
            // (إحنا مش حاطين page في url() أصلاً، فده يضمن start fresh)
            this.load(false);
        },

        async go(u){
            const url = new URL(u);
            url.searchParams.set('ajax','1');
            const res = await axios.get(url.toString(), { headers:{'X-Requested-With':'XMLHttpRequest'} });
            this.rows = res.data;
            // بعد التنقل بين الصفحات، لو المستخدم لمس أي فلتر نرجعه للصفحة 1 تلقائيًا
        },

        async init(){ await this.load(true); }
    }
}
</script>
@endsection
@endsection
