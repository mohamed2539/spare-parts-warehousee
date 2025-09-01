<?php

namespace App\Http\Controllers;

use App\Models\IncomingItem;
use App\Models\ManualWithdrawal;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncomingReportExport;
use App\Exports\WithdrawalsReportExport;

class ReportController extends Controller
{
    public function incoming(Request $request)
    {
        $q = $request->query('q');
        $from = $request->query('from');
        $to = $request->query('to');
        $department = $request->query('department');
        $supplier = $request->query('supplier');

        $rows = IncomingItem::query()
            ->when($q, fn($qr)=>$qr->where(function($w) use ($q){
                $w->where('code','like',"%$q%")
                  ->orWhere('item','like',"%$q%");
            }))
            ->when($from, fn($qr)=>$qr->whereDate('date','>=',$from))
            ->when($to, fn($qr)=>$qr->whereDate('date','<=',$to))
            ->when($department, fn($qr)=>$qr->where('department',$department))
            ->when($supplier, fn($qr)=>$qr->where('supplier','like',"%$supplier%"))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) return response()->json($rows);

        return view('reports.incoming', compact('rows'));
    }

    public function exportIncoming(Request $request)
    {
        return Excel::download(new IncomingReportExport($request), 'incoming_report.xlsx');
    }

    public function withdrawals(Request $request)
    {
        $q = $request->query('q');
        $from = $request->query('from');
        $to = $request->query('to');
        $department = $request->query('department'); // الطلب من قسم
        $receiver = $request->query('receiver');
        $supplier = $request->query('supplier'); // لو بتسجله في جدول الصرف

        $rows = ManualWithdrawal::query()
            ->when($q, fn($qr)=>$qr->where(function($w) use ($q){
                $w->where('code','like',"%$q%")
                  ->orWhere('item','like',"%$q%");
            }))
            ->when($from, fn($qr)=>$qr->whereDate('date','>=',$from))
            ->when($to, fn($qr)=>$qr->whereDate('date','<=',$to))
            ->when($department, fn($qr)=>$qr->where('request_department',$department))
            ->when($receiver, fn($qr)=>$qr->where('receiver','like',"%$receiver%"))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax()) return response()->json($rows);

        return view('reports.withdrawals', compact('rows'));
    }

    public function exportWithdrawals(Request $request)
    {
        return Excel::download(new WithdrawalsReportExport($request), 'withdrawals_report.xlsx');
    }
}
