<?php

namespace App\Exports;

use App\Models\ManualWithdrawal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class WithdrawalsReportExport implements FromView
{
    public function __construct(private Request $request) {}

    public function view(): View
    {
        $q = $this->request->query('q');
        $from = $this->request->query('from');
        $to = $this->request->query('to');
        $department = $this->request->query('department');
        $receiver = $this->request->query('receiver');

        $rows = ManualWithdrawal::query()
            ->when($q, fn($qr)=>$qr->where(function($w) use ($q){
                $w->where('code','like',"%$q%")->orWhere('item','like',"%$q%");
            }))
            ->when($from, fn($qr)=>$qr->whereDate('date','>=',$from))
            ->when($to, fn($qr)=>$qr->whereDate('date','<=',$to))
            ->when($department, fn($qr)=>$qr->where('request_department',$department))
            ->when($receiver, fn($qr)=>$qr->where('receiver','like',"%$receiver%"))
            ->orderBy('date')
            ->get();

        return view('exports.withdrawals', compact('rows'));
    }
}
