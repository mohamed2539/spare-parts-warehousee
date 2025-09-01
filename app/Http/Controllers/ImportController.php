<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IncomingImport;
use App\Models\IncomingItem;
use App\Exports\IncomingItemsExport;

class ImportController extends Controller
{
    public function create()
    {
        $items = \App\Models\IncomingItem::latest()->paginate(10);
        return view('imports.create', compact('items'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:10240'
        ]);
    
        try {
            Excel::import(new IncomingImport, $request->file('file'));
    
            // Redirect بعد رفع البيانات
            return redirect()->route('imports.listPage')->with('success', 'تم استيراد الملف بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }
    }
    
    // صفحة جديدة لعرض البيانات
    public function listPage()
    {
        $items = IncomingItem::latest()->paginate(20); // مثال
        return view('imports.list', compact('items'));
    }



    
public function export()
{
    return Excel::download(new IncomingItemsExport, 'incoming_items.xlsx');
}
}
