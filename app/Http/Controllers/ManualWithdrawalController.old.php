<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualWithdrawal;
use App\Models\IncomingItem; // لجلب الكمية المتاحة

class ManualWithdrawalController extends Controller
{
    // عرض صفحة الصرف اليدوي
    public function create()
    {
        // نجيب كل الأصناف المتاحة مع كمية متبقية (كمية الوارد - مجموع الصرف)
        $items = IncomingItem::all()->map(function($item){
            $totalWithdrawn = $item->withdrawals()->sum('quantity'); // افترضنا علاقة بين IncomingItem و ManualWithdrawal
            $item->available_quantity = $item->quantity - $totalWithdrawn;
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
            'quantity' => 'required|numeric|min:0.01',
            'voucher' => 'nullable|string',
            'reason' => 'nullable|string',
            'receiver' => 'nullable|string',
            'request_department' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ManualWithdrawal::create($request->all());

        return redirect()->back()->with('success', 'تم تسجيل الصرف بنجاح');
    }

    // البحث الديناميكي (AJAX)
    public function searchItem(Request $request)
    {
        $query = $request->get('query', '');
        $items = IncomingItem::where('code', 'like', "%$query%")
            ->orWhere('item', 'like', "%$query%")
            ->limit(10)
            ->get(['code','item','unit','quantity']);

        return response()->json($items);
    }

    // صفحة عرض كل عمليات الصرف مع Pagination
    public function index()
    {
        $withdrawals = ManualWithdrawal::latest()->paginate(15);
        return view('withdrawals.index', compact('withdrawals'));
    }

    // حذف صرف واحد
    public function destroy($id)
    {
        ManualWithdrawal::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }

    public function destroyMultiple(Request $request) {
        $ids = $request->ids ?? [];
        ManualWithdrawal::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'تم حذف العمليات المحددة');
    }


    // حذف متعدد
    public function bulkDelete(Request $request)
    {
        ManualWithdrawal::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
    }
}
