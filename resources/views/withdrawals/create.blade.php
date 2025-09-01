@extends('layouts.app')

@section('title', 'إضافة صرف يدوي')

@section('content')
<div class="max-w-4xl mx-auto mt-6 p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إضافة صرف يدوي</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <form action="{{ route('withdrawals.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">التاريخ</label>
                    <input type="date" name="date" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400" value="{{ old('date') }}">
                </div>

                <div class="relative">
                    <label class="block mb-1 font-semibold text-gray-700">الكود / الصنف</label>
                    <input type="text" id="item-search" name="code" placeholder="اكتب الكود أو الصنف" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                    <ul id="suggestions" class="absolute w-full border bg-white rounded shadow-lg mt-1 max-h-44 overflow-y-auto hidden z-50"></ul>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">الصنف</label>
                    <input type="text" id="item-name" name="item" class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">الوحدة</label>
                    <input type="text" id="item-unit" name="unit" class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">الكمية المتاحة</label>
                    <input type="text" id="available-qty" class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">الكمية</label>
                    <input type="number" name="quantity" step="0.01" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">سند الصرف</label>
                    <input type="text" name="voucher" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">سبب الصرف</label>
                    <input type="text" name="reason" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">المستلم</label>
                    <input type="text" name="receiver" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">القسم الطالب</label>
                    <input type="text" name="request_department" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 font-semibold text-gray-700">ملاحظات</label>
                    <textarea name="notes" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">حفظ الصرف</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
const searchInput = document.getElementById('item-search');
const suggestions = document.getElementById('suggestions');
const itemName = document.getElementById('item-name');
const itemUnit = document.getElementById('item-unit');
const availableQty = document.getElementById('available-qty');

searchInput.addEventListener('input', function() {
    const query = this.value;
    if (!query) {
        suggestions.classList.add('hidden');
        availableQty.value = '';
        itemName.value = '';
        itemUnit.value = '';
        return;
    }

    axios.get("{{ route('withdrawals.searchItem') }}", { params: { query } })
        .then(res => {
            const items = res.data;
            if (!items.length) { 
                suggestions.classList.add('hidden'); 
                return; 
            }
            suggestions.innerHTML = '';
            items.forEach(i => {
                const li = document.createElement('li');
                li.textContent = `${i.code} - ${i.item} (متاح: ${i.quantity_available ?? i.quantity ?? 0})`;
                li.classList.add('p-2', 'hover:bg-gray-200', 'cursor-pointer');
                li.addEventListener('click', function() {
                    itemName.value = i.item;
                    itemUnit.value = i.unit;
                    searchInput.value = i.code;
                    availableQty.value = i.quantity_available ?? i.quantity ?? 0;
                    suggestions.classList.add('hidden');
                });
                suggestions.appendChild(li);
            });
            suggestions.classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            suggestions.classList.add('hidden');
        });
});
</script>
@endsection
