<?php

namespace App\Http\Controllers;

use App\Models\InboundBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessInboundBatch;

class InboundImportController extends Controller
{
    // صفحة رفع الإكسل
    public function create()
    {
        return view('inbound/import');
    }

    // حفظ الملف وإنشاء Batch (لا معالجة هنا)
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:20480', // 20MB
        ]);

        $path = $request->file('file')->store('imports');

        $batch = InboundBatch::create([
            'original_filename' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'تم رفع الملف. يمكنك بدء الاستيراد الآن.',
            'batch_id' => $batch->id,
        ]);
    }

    // بدء المعالجة (تشغيل Job)
    public function start(InboundBatch $batch)
    {
        if ($batch->status !== 'pending') {
            return response()->json(['message' => 'لا يمكن البدء. الحالة الحالية: '.$batch->status], 422);
        }

        $batch->update([
            'status' => 'running',
            'started_at' => now(),
            'processed_rows' => 0,
            'failed_rows' => 0,
            'total_rows' => null, // هيتحدّد داخل الـ Job
        ]);

        ProcessInboundBatch::dispatch($batch->id);

        return response()->json(['message' => 'تم بدء الاستيراد.']);
    }

    // متابعة التقدّم
    public function progress(InboundBatch $batch)
    {
        return response()->json([
            'status' => $batch->status,
            'total' => $batch->total_rows,
            'processed' => $batch->processed_rows,
            'failed' => $batch->failed_rows,
            'started_at' => optional($batch->started_at)->toDateTimeString(),
            'finished_at' => optional($batch->finished_at)->toDateTimeString(),
            'error_file' => $batch->error_file_path ? route('inbound.import.errors', $batch->id) : null,
        ]);
    }

    // تنزيل ملف الأخطاء (لو موجود)
    public function downloadErrors(InboundBatch $batch)
    {
        if (!$batch->error_file_path || !Storage::exists($batch->error_file_path)) {
            abort(404);
        }
        return Storage::download($batch->error_file_path);
    }
}
