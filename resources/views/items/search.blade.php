@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-gray-700">🔍 بحث عن صنف</h2>

    <input type="text" id="search-input" 
        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 mb-4"
        placeholder="ابحث بالكود أو الاسم أو المورد...">

    <ul id="results" class="divide-y divide-gray-200 bg-white rounded-lg shadow"></ul>
</div>

<script>
const searchInput = document.getElementById('search-input');
const results = document.getElementById('results');

searchInput.addEventListener('input', function() {
    const query = this.value;
    if (!query) {
        results.innerHTML = '';
        return;
    }

    fetch(`{{ route('items.searchApi') }}?query=${query}`)
        .then(res => res.json())
        .then(items => {
            results.innerHTML = '';
            if (items.length === 0) {
                results.innerHTML = `<li class="px-4 py-2 text-gray-500">لا يوجد نتائج</li>`;
                return;
            }
            items.forEach(i => {
                const li = document.createElement('li');
                li.className = "px-4 py-3 hover:bg-blue-50 cursor-pointer";
                li.innerHTML = `
                    <div class="font-semibold">${i.code} - ${i.item}</div>
                    <div class="text-sm text-gray-500">المورد: ${i.supplier} | الكمية: ${i.quantity}</div>
                `;
                results.appendChild(li);
            });
        });
});
</script>
@endsection
