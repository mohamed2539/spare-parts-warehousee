<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockExport;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DepartmentController;


class StockController extends Controller
{
    private function stockQuery(Request $request)
{
    // تجميع الوارد
    $incomingAgg = DB::table('incoming_items')
        ->select(
            'code',
            'item',
            'unit',
            'department_id',
            'supplier',
            DB::raw('SUM(quantity) as in_qty')
        )
        ->groupBy('code','item','unit','department_id','supplier');

    // تجميع الصرف
    $outAgg = DB::table('manual_withdrawals')
        ->select('code','item','unit', DB::raw('SUM(quantity) as out_qty'))
        ->groupBy('code','item','unit');

    $q          = trim((string)$request->query('q', ''));
    $department = trim((string)$request->query('department', ''));
    $supplier   = trim((string)$request->query('supplier', ''));

    $base = DB::query()->fromSub($incomingAgg, 'i')
        ->leftJoinSub($outAgg, 'w', function($j){
            $j->on('w.code','=','i.code')
              ->on('w.item','=','i.item')
              ->on('w.unit','=','i.unit');
        })
        ->leftJoin('departments as d', 'i.department_id', '=', 'd.id')
        ->select(
            // هنا بنولّد id افتراضي مميز لكل صف
            DB::raw("CONCAT(i.code,'-',i.item,'-',i.unit,'-',i.department_id,'-',i.supplier) as id"),
            'i.code',
            'i.item',
            'i.unit',
            'i.department_id',
            'd.name as department',
            'i.supplier',
            DB::raw('i.in_qty as total_in'),
            DB::raw('COALESCE(w.out_qty,0) as total_out'),
            DB::raw('(i.in_qty - COALESCE(w.out_qty,0)) as balance')
        )
        // بحث ذكي
        ->when($q !== '', function($qr) use ($q){
            if (ctype_digit($q)) {
                $qr->where('i.code', $q);
            } else {
                $qr->where(function($w) use ($q){
                    $w->where('i.code','like',"%{$q}%")
                      ->orWhere('i.item','like',"%{$q}%")
                      ->orWhere('i.unit','like',"%{$q}%");
                });
            }
        })
        // فلترة القسم: اسم أو رقم
        ->when($department !== '', function($qr) use ($department){
            if (ctype_digit($department)) {
                $qr->where('i.department_id', $department);
            } else {
                $qr->where('d.name', 'like', "%{$department}%");
            }
        })
        // فلترة المورد
        ->when($supplier !== '', fn($qr)=> $qr->where('i.supplier','like',"%{$supplier}%"))
        ->orderBy('i.item');

    return $base;
}

    public function index(Request $request)
    {
        // دايمًا رجّع JSON لو ajax=1 أو X-Requested-With
        $rows = $this->stockQuery($request)->paginate(20);

        if ($request->ajax() || $request->query('ajax')) {
            return response()->json($rows);
        }

        return view('stock.index', compact('rows'));
    }

    public function export(Request $request)
    {
        return Excel::download(new StockExport($request), 'current_stock.xlsx');
    }





    public function bulkDelete(Request $request)
    {
        $codes = $request->input('selected', []);
        $user = Auth::user();
    
        if(!empty($codes)){
            $records = DB::table('incoming_items')->whereIn('code', $codes)->get();
    
            // حذف البيانات
            DB::table('incoming_items')->whereIn('code', $codes)->delete();
    
            // تسجيل كل عملية حذف
            foreach($records as $r){
                DB::table('deletion_logs')->insert([
                    'model' => 'incoming_items',
                    'record_id' => $r->id ?? null,
                    'details' => json_encode($r),
                    'deleted_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        return response()->json(['message'=>'تم حذف العناصر المحددة']);
    }
    
    public function clearAll()
    {
        $userId = auth()->id(); // تسجيل المستخدم اللي عامل العملية
    
        try {
            DB::beginTransaction();
    
            // مسح البيانات فعليًا بدون مشاكل FK
            DB::table('manual_withdrawals')->delete();
            DB::table('incoming_items')->delete();
    
            // إضافة سجل في جدول الـ deletion_logs
            DB::table('deletion_logs')->insert([
                'model' => 'Stock',
                'record_id' => 0, // بدل null
                'details' => 'تصفير المخزون بالكامل (حذف جميع الأصناف الواردة والصرفية)',
                'deleted_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            DB::commit();
    
            return response()->json(['message' => 'تم تصفير المخزون بالكامل']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء التصفير', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    // // مسح الكل
    // public function clear()
    // {
    //     DB::table('inbound_rows')->truncate();
    //     return response()->json(['message' => 'تم تصفية الرصيد بالكامل']);
    // }

    // // مسح المحدد
    // public function deleteSelected(Request $request)
    // {
    //     $ids = $request->input('ids', []);
    //     if (!empty($ids)) {
    //         DB::table('inbound_rows')->whereIn('id', $ids)->delete();
    //         return response()->json(['message' => 'تم مسح البنود المحددة']);
    //     }
    //     return response()->json(['message' => 'لم يتم اختيار أي بند'], 422);
    // }








}
