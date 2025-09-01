@extends('layouts.app')

@section('title','وارد من شيت إكسل')

@section('content')
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">وارد من شيت إكسل</h1>

    {{-- خطوة 1: رفع الملف --}}
    <form id="uploadForm" class="bg-white p-4 rounded shadow space-y-3">
      <div>
        <label class="block mb-1 font-medium">اختَر ملف إكسل (xlsx/xls/csv)</label>
        <input type="file" name="file" accept=".xlsx,.xls,.csv" class="block w-full border rounded p-2 bg-gray-50" required>
      </div>
      <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2">رفع الملف</button>
      <p id="uploadMsg" class="text-sm mt-2"></p>
    </form>

    {{-- خطوة 2: بدء الاستيراد --}}
    <div id="startBox" class="mt-6 hidden bg-white p-4 rounded shadow">
      <p class="mb-3">تم رفع الملف بنجاح. اضغط “بدء الاستيراد”.</p>
      <button id="startBtn" class="bg-green-600 text-white rounded px-4 py-2">بدء الاستيراد</button>
    </div>

    {{-- خطوة 3: التقدّم --}}
    <div id="progressBox" class="mt-6 hidden bg-white p-4 rounded shadow">
      <div class="flex items-center gap-3 mb-3">
        <div class="animate-spin rounded-full h-5 w-5 border-2 border-gray-300 border-t-gray-700" id="spinner"></div>
        <span id="statusText" class="font-medium">جارٍ التحضير...</span>
      </div>

      <div class="w-full bg-gray-200 rounded h-3 overflow-hidden">
        <div id="bar" class="bg-blue-600 h-3 transition-all" style="width: 0%"></div>
      </div>
      <div class="flex justify-between text-sm mt-2">
        <span>المعالج: <b id="processed">0</b></span>
        <span>الإجمالي: <b id="total">—</b></span>
        <span>الفاشل: <b id="failed">0</b></span>
      </div>

      <div id="doneBox" class="mt-4 hidden">
        <a id="errorLink" href="#" class="text-red-700 underline hidden">تنزيل سطور الأخطاء</a>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  let currentBatchId = null;
  let pollTimer = null;

  // رفع الملف AJAX
  document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);

    const res = await fetch(`{{ route('inbound.import.upload') }}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: fd
    });

    const data = await res.json();
    const msg = document.getElementById('uploadMsg');

    if (res.ok) {
      msg.className = 'text-green-700';
      msg.textContent = data.message;
      currentBatchId = data.batch_id;
      document.getElementById('startBox').classList.remove('hidden');
    } else {
      msg.className = 'text-red-700';
      msg.textContent = data.message || 'فشل رفع الملف';
    }
  });

  // بدء الاستيراد AJAX
  document.getElementById('startBtn').addEventListener('click', async () => {
    if (!currentBatchId) return;
    document.getElementById('progressBox').classList.remove('hidden');

    const res = await fetch(`/inbound/import/${currentBatchId}/start`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() }
    });

    if (res.ok) {
      startPolling();
    } else {
      alert('تعذر بدء الاستيراد');
    }
  });

  // متابعة التقدّم
  async function poll() {
    if (!currentBatchId) return;
    const res = await fetch(`/inbound/import/${currentBatchId}/progress`);
    const data = await res.json();

    document.getElementById('processed').textContent = data.processed ?? 0;
    document.getElementById('failed').textContent = data.failed ?? 0;
    document.getElementById('total').textContent = data.total ?? '—';

    const statusText = document.getElementById('statusText');
    const spinner = document.getElementById('spinner');
    const bar = document.getElementById('bar');

    if (data.status === 'running') {
      statusText.textContent = 'جارٍ الاستيراد...';
      spinner.classList.remove('hidden');
      if (data.total) {
        const pct = Math.min(100, Math.round((data.processed / data.total) * 100));
        bar.style.width = pct + '%';
      } else {
        bar.style.width = '10%';
      }
    }

    if (data.status === 'done' || data.status === 'failed') {
      clearInterval(pollTimer);
      spinner.classList.add('hidden');
      statusText.textContent = (data.status === 'done') ? 'اكتمل الاستيراد' : 'فشل الاستيراد';
      bar.style.width = '100%';

      const link = document.getElementById('errorLink');
      if (data.error_file) {
        link.href = data.error_file;
        link.classList.remove('hidden');
      }
      document.getElementById('doneBox').classList.remove('hidden');
    }
  }

  function startPolling() {
    if (pollTimer) clearInterval(pollTimer);
    poll();
    pollTimer = setInterval(poll, 1500);
  }
</script>
@endsection
