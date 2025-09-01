<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InboundBatch;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InboundRowsImport;

class InboundController extends Controller
{
    public function importForm()
    {
        return view('inbound.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $batch = InboundBatch::create([
            'file_name' => $request->file('file')->getClientOriginalName(),
            'status' => 'processing',
        ]);

        Excel::import(new InboundRowsImport($batch), $request->file('file'));

        $batch->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح');
    }
}
