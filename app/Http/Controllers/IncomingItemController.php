<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomingItem;
use Carbon\Carbon;

class IncomingItemController extends Controller
{
    public function index()
    {
        $items = IncomingItem::latest()->paginate(10);
        return view('incoming.index', compact('items'));
    }

    public function create()
    {
        return view('incoming.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'date'       => 'required|date',
            'code'       => 'required|string|max:255',
            'item'       => 'required|string|max:255',
            'unit'       => 'required|string|max:50',
            'quantity'   => 'required|numeric',
            'supplier'   => 'nullable|string|max:255',
        ]);
    
        IncomingItem::create([
            'department_id' => $request->department_id,
            'date'       => $request->date,
            'code'       => $request->code,
            'item'       => $request->item,
            'unit'       => $request->unit,
            'quantity'   => $request->quantity,
            'supplier'   => $request->supplier,
            'created_by' => auth()->id(), // تخزين معرف المستخدم الذي أضاف البند
        ]);
    
        return redirect()->route('incoming.index')->with('success', 'تم إضافة الوارد بنجاح ✅');
    }
    

    // public function edit($id)
    // {
    //     $item = IncomingItem::findOrFail($id);
    //     return view('incoming.edit', compact('item'));
    // }

    public function edit($id)
    {
        $item = IncomingItem::findOrFail($id);
        
        // إظهار اسم المستخدم الذي أنشأ العنصر (إذا كنت ترغب في ذلك)
        $creator = $item->creator; // Assuming 'creator' is a relation that links to the User model
        
        return view('incoming.edit', compact('item', 'creator'));
    }
        
    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'date'       => 'required|date',
            'code'       => 'required|string|max:255',
            'item'       => 'required|string|max:255',
            'unit'       => 'required|string|max:50',
            'quantity'   => 'required|numeric',
            'supplier'   => 'nullable|string|max:255',
        ]);

        $item = IncomingItem::findOrFail($id);
        $item->update([
            'department' => $request->department,
            'date'       => $request->date,
            'code'       => $request->code,
            'item'       => $request->item,
            'unit'       => $request->unit,
            'quantity'   => $request->quantity,
            'supplier'   => $request->supplier,
            'updated_by' => auth()->id(), // تخزين معرف المستخدم الذي قام بالتعديل
        ]);

        return redirect()->route('incoming.index')->with('success', 'تم تعديل البند بنجاح');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'department_id' => 'required|exists:departments,id',
    //         'date'       => 'required|date',
    //         'code'       => 'required|string|max:255',
    //         'item'       => 'required|string|max:255',
    //         'unit'       => 'required|string|max:50',
    //         'quantity'   => 'required|numeric',
    //         'supplier'   => 'nullable|string|max:255',
    //     ]);
    
    //     $item = IncomingItem::findOrFail($id);
    //     $item->update($request->all());
    
    //     return redirect()->route('incoming.index')->with('success', 'تم تعديل البند بنجاح');
    // }
    
    // public function destroy($id)
    // {
    //     $item = IncomingItem::findOrFail($id);
    //     $item->delete();
    
    //     return redirect()->route('incoming.index')->with('success', 'تم حذف البند بنجاح');
    // }
    


    public function destroy($id)
    {
        $item = IncomingItem::findOrFail($id);
        $item->deleted_by = auth()->id(); // تخزين معرف المستخدم الذي قام بالحذف
        $item->save(); // حفظ التعديل قبل الحذف
    
        $item->delete(); // تنفيذ الحذف الفعلي
    
        return redirect()->route('incoming.index')->with('success', 'تم حذف البند بنجاح');
    }

    // public function destroyMultiple(Request $request)
    // {
    //     $ids = $request->ids;
    //     if ($ids) {
    //         IncomingItem::whereIn('id', $ids)->delete();
    //         return response()->json(['success' => true, 'message' => 'تم حذف البنود المحددة']);
    //     }
    //     return response()->json(['success' => false, 'message' => 'لم يتم اختيار أي بند']);
    // }


    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;
        if ($ids) {
            // إضافة سجل الحذف مع معرف المستخدم الذي قام بالحذف
            $userId = auth()->id(); // الحصول على معرف المستخدم الحالي
    
            // إذا كنت تريد تسجيل الحذف مع معرف المستخدم
            IncomingItem::whereIn('id', $ids)->update(['deleted_by' => $userId]);
    
            // الحذف الفعلي
            IncomingItem::whereIn('id', $ids)->delete();
    
            return response()->json(['success' => true, 'message' => 'تم حذف البنود المحددة']);
        }
        return response()->json(['success' => false, 'message' => 'لم يتم اختيار أي بند']);
    }

}


