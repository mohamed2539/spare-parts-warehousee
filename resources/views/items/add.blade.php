@extends('layouts.app')

@section('content')
@include('layouts.header')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-gray-700">➕ إضافة كمية</h2>

    <!-- البحث -->
    <input type="text" id="item-search" 
        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 mb-4"
        placeholder="ابحث عن صنف...">

    <ul id="suggestions" class="divide-y divide-gray-200 bg-white rounded-lg shadow mb-4"></ul>

    <!-- فورم الإضافة -->
    <form id="add-form" class="hidden bg-white p-6 rounded-lg shadow space-y-4">
        <input type="hidden" id="item-id" name="item_id">

        <div>
            <label class="text-gray-600">اسم الصنف</label>
            <input type="text" id="item-name" class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
        </div>

        <div>
            <label class="text-gray-600">الكمية الحالية</label>
            <input type="text" id="item-qty" class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
        </div>

        <div>
            <label class="text-gray-600">أضف كمية</label>
            <input type="number" id="quantity" name="quantity" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <button type="submit" 
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow">
            إضافة
        </button>
    </form>

    <!-- مكان التنبيه -->
    <div id="alert-box" class="hidden mt-4 p-3 rounded-lg text-white"></div>
</div>

<script>
const searchBox = document.getElementById('item-search');
const suggestions = document.getElementById('suggestions');
const addForm = document.getElementById('add-form');
const itemId = document.getElementById('item-id');
const itemName = document.getElementById('item-name');
const itemQty = document.getElementById('item-qty');
const quantity = document.getElementById('quantity');
const alertBox = document.getElementById('alert-box');

searchBox.addEventListener('input', function() {
    const query = this.value;
    if (!query) {
        suggestions.innerHTML = '';
        return;
    }

    fetch(`{{ route('items.searchApi') }}?query=${query}`)
        .then(res => res.json())
        .then(items => {
            suggestions.innerHTML = '';
            items.forEach(i => {
                const li = document.createElement('li');
                li.className = "px-4 py-3 hover:bg-blue-50 cursor-pointer";
                li.innerHTML = `<div class="font-semibold">${i.code} - ${i.item}</div>`;
                li.onclick = () => {
                    itemId.value = i.id;
                    itemName.value = i.item;
                    itemQty.value = i.quantity;
                    addForm.classList.remove('hidden');
                    suggestions.innerHTML = '';
                };
                suggestions.appendChild(li);
            });
        });
});

addForm.addEventListener('submit', function(e) {
    e.preventDefault();

    fetch(`{{ route('items.addStockAjax') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            item_id: itemId.value,
            quantity: quantity.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // ✅ تحديث الكمية مباشرة
            let currentQty = parseFloat(itemQty.value);
            let addedQty = parseFloat(quantity.value);
            itemQty.value = currentQty + addedQty;

            quantity.value = '';

            // ✅ إظهار رسالة نجاح
            alertBox.textContent = data.message;
            alertBox.className = "mt-4 p-3 rounded-lg text-white bg-green-600";
            alertBox.classList.remove('hidden');

            setTimeout(() => {
                alertBox.classList.add('hidden');
            }, 2500);
        }
    });
});
</script>
@endsection
