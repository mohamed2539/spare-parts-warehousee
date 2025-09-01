<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        // فلاتر بسيطة
        $q = $request->query('q');
        $status = $request->query('status');

        $requests = PurchaseRequest::query()
            ->when($q, fn($qr) => $qr->where(function($w) use ($q) {
                $w->where('code','like',"%$q%")
                  ->orWhere('item','like',"%$q%")
                  ->orWhere('supplier','like',"%$q%");
            }))
            ->when($status, fn($qr) => $qr->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json($requests);
        }

        return view('purchase_requests.index', compact('requests','q','status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['nullable','date'],
            'code' => ['nullable','string','max:255'],
            'item' => ['nullable','string','max:255'],
            'unit' => ['nullable','string','max:255'],
            'requested_qty' => ['required','numeric','min:0.01'],
            'supplier' => ['nullable','string','max:255'],
            'request_department' => ['nullable','string','max:255'],
            'requester_name' => ['nullable','string','max:255'],
            'reason' => ['nullable','string'],
            'notes' => ['nullable','string'],
        ]);
        $data['created_by'] = auth()->id();

        $pr = PurchaseRequest::create($data);

        if ($request->ajax()) {
            return response()->json(['success'=>true, 'message'=>'تم إنشاء طلب الشراء', 'row'=>$pr]);
        }

        return back()->with('success','تم إنشاء طلب الشراء');
    }

    public function updateStatus(Request $request, PurchaseRequest $purchaseRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,ordered,received,cancelled'
        ]);
        $purchaseRequest->update($validated);

        return response()->json(['success'=>true,'message'=>'تم تحديث الحالة']);
    }
}
