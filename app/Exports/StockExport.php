<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class StockExport implements FromView
{
    public function __construct(private Request $request) {}

    public function view(): View
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
            ->select('code','item','unit',
                DB::raw('SUM(quantity) as out_qty'))
            ->groupBy('code','item','unit');

        $q = $this->request->query('q');
        $department = $this->request->query('department');
        $supplier = $this->request->query('supplier');

        $rows = DB::query()->fromSub($incomingAgg,'i')
            ->leftJoinSub($outAgg, 'w', function($j){
                $j->on('w.code','=','i.code')
                  ->on('w.item','=','i.item')
                  ->on('w.unit','=','i.unit');
            })
            ->leftJoin('departments as d', 'i.department_id', '=', 'd.id')
            ->select(
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
            ->when($q, fn($qr)=>$qr->where(function($w) use ($q){
                $w->where('i.code','like',"%$q%")
                  ->orWhere('i.item','like',"%$q%")
                  ->orWhere('i.unit','like',"%$q%");
            }))
            ->when($department, fn($qr)=>$qr->where('i.department_id',$department))
            ->when($supplier, fn($qr)=>$qr->where('i.supplier','like',"%$supplier%"))
            ->orderBy('i.item')
            ->get();

        return view('exports.stock', ['rows' => $rows]);
    }
}
