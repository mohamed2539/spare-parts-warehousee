<?php

namespace App\Exports;

use App\Models\IncomingItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class IncomingReportExport implements FromView
{
    public function __construct(private Request $request) {}

    public function view(): View
    {
        $q = $this->request->query('q');
        $from = $this->request->query('from');
        $to = $this->request->query('to');
        $department = $this->request->query('department');
        $supplier = $this->request->query('supplier');

        $rows = IncomingItem::query()
            ->when($q, fn($qr)=>$qr->where(function($w) use ($q){
                $w->where('code','like',"%$q%")->orWhere('item','like',"%$q%");
            }))
            ->when($from, fn($qr)=>$qr->whereDate('date','>=',$from))
            ->when($to, fn($qr)=>$qr->whereDate('date','<=',$to))
            ->when($department, fn($qr)=>$qr->where('department',$department))
            ->when($supplier, fn($qr)=>$qr->where('supplier','like',"%$supplier%"))
            ->orderBy('date')
            ->get();

        return view('exports.incoming', compact('rows'));
    }
}
