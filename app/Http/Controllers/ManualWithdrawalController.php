<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualWithdrawal;
use App\Models\IncomingItem;
use Illuminate\Support\Facades\DB;

class ManualWithdrawalController extends Controller
{
    // عرض صفحة الصرف اليدوي
    public function create()
    {
        // نجيب كل الأصناف مع الكمية المتاحة
        $items = IncomingItem::all()->map(function($item){
            $totalIn = DB::table('incoming_items')->where('code', $item->code)->sum('quantity');
            $totalOut = DB::table('manual_withdrawals')->where('code', $item->code)->sum('quantity');
            $item->available_quantity = $totalIn - $totalOut;
            return $item;
        });
    
        return view('withdrawals.create', compact('items'));
    }

    // حفظ الصرف
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'code' => 'required|string',
            'item' => 'required|string',
            'unit' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'voucher' => 'nullable|string',
            'reason' => 'nullable|string',
            'receiver' => 'nullable|string',
            'request_department' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // حساب الرصيد المتاح
        $totalIn = DB::table('incoming_items')->where('code', $request->code)->sum('quantity');
        $totalOut = DB::table('manual_withdrawals')->where('code', $request->code)->sum('quantity');
        $available = $totalIn - $totalOut;

        if ($request->quantity > $available) {
            return redirect()->back()->with('error', '❌ لا يمكن صرف كمية أكبر من الرصيد المتاح (' . $available . ')');
        }

        // جلب الحد الأدنى من incoming_items
        $minQty = DB::table('incoming_items')->where('code', $request->code)->value('min_quantity');

        // تسجيل عملية الصرف
        ManualWithdrawal::create([
            'date' => $request->date,
            'code' => $request->code,
            'item' => $request->item,
            'unit' => $request->unit,
            'quantity' => $request->quantity,
            'voucher' => $request->voucher,
            'reason' => $request->reason,
            'receiver' => $request->receiver,
            'request_department' => $request->request_department,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        // بعد الصرف: تحقق من الرصيد الجديد
        $newBalance = $available - $request->quantity;
        if ($newBalance <= $minQty) {
            return redirect()->back()->with('warning', '⚠️ تنبيه: الرصيد الحالي (' . $newBalance . ') أقل من الحد الأدنى (' . $minQty . ') لهذا الصنف');
        }

        return redirect()->back()->with('success', '✅ تم تسجيل الصرف بنجاح');
    }

    // البحث الديناميكي (AJAX)
    public function searchItem(Request $request)
    {
        $query = $request->get('query', '');
        $items = IncomingItem::where('code', 'like', "%$query%")
            ->orWhere('item', 'like', "%$query%")
            ->limit(10)
            ->get(['code','item','unit','quantity','min_quantity']);

        return response()->json($items);
    }

    // صفحة عرض كل عمليات الصرف مع Pagination
    public function index()
    {
        $withdrawals = ManualWithdrawal::with('user')->latest()->paginate(15);
        return view('withdrawals.index', compact('withdrawals'));
    }

    // حذف صرف واحد
    public function destroy($id)
    {
        ManualWithdrawal::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    // حذف متعدد
    public function destroyMultiple(Request $request) {
        $ids = $request->ids ?? [];
        ManualWithdrawal::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'تم حذف العمليات المحددة');
    }

    public function bulkDelete(Request $request)
    {
        ManualWithdrawal::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
    }
}
