@extends('layouts.app')

@section('title', 'استيراد وارد من شيت')

@section('content')
  <div class="max-w-3xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">رفع شيت الوارد</h1>

    @if($errors->any())
      <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('imports.upload') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
      @csrf
      <div class="mb-4">
        <label class="block mb-2">اختر ملف Excel (xls, xlsx, csv)</label>
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
      </div>

      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">رفع واستيراد</button>
    </form>
  </div>
@endsection


@section('scripts')
<!-- إضافة Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
  const uploadBtn = document.getElementById('upload-btn');
  const form = document.getElementById('import-form');
  const progressEl = document.getElementById('progress');
  const itemsTable = document.getElementById('items-table');

  function renderTable(rows) {
    if (!rows.length) {
      itemsTable.innerHTML = '<div class="p-4 text-gray-600">لا توجد سجلات</div>';
      return;
    }
    let html = `<table class="min-w-full table-auto text-sm"><thead>
      <tr class="bg-gray-50">
        <th class="px-3 py-2">القسم</th>
        <th class="px-3 py-2">التاريخ</th>
        <th class="px-3 py-2">الكود</th>
        <th class="px-3 py-2">الصنف</th>
        <th class="px-3 py-2">الوحدة</th>
        <th class="px-3 py-2">الكمية</th>
        <th class="px-3 py-2">المورد</th>
      </tr></thead><tbody>`;

    rows.forEach(r => {
      html += `<tr class="border-t">
        <td class="px-3 py-2">${r.department ?? ''}</td>
        <td class="px-3 py-2">${r.date ?? ''}</td>
        <td class="px-3 py-2">${r.code ?? ''}</td>
        <td class="px-3 py-2">${r.item ?? ''}</td>
        <td class="px-3 py-2">${r.unit ?? ''}</td>
        <td class="px-3 py-2">${r.quantity ?? ''}</td>
        <td class="px-3 py-2">${r.supplier ?? ''}</td>
      </tr>`;
    });

    html += `</tbody></table>`;
    itemsTable.innerHTML = html;
  }

  async function loadList(){
    try {
      const res = await axios.get("{{ route('imports.listPage') }}");
      renderTable(res.data);
    } catch (e) {
      itemsTable.innerHTML = '<div class="p-4 text-red-600">خطأ في جلب السجلات</div>';
    }
  }

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const input = form.querySelector('input[name="file"]');
    if (!input.files.length) { alert('اختر ملف'); return; }

    const fd = new FormData();
    fd.append('file', input.files[0]);

    axios.post("{{ route('imports.upload') }}", fd, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      onUploadProgress: function(progressEvent) {
        if (progressEvent.total) {
          const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          progressEl.innerText = 'تحميل: ' + percent + '%';
        }
      }
    }).then(res => {
      alert(res.data.message || 'تم');
      progressEl.innerText = '';
      loadList();
    }).catch(err => {
      const msg = err.response?.data?.message || 'حصل خطأ في الرفع';
      alert(msg);
      progressEl.innerText = '';
    });
  });

  // تحميل السجلات عند فتح الصفحة
  loadList();
</script>
@endsection