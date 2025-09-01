<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use Illuminate\Http\Request;

class OutgoingController extends Controller
{
    public function index()
    {
        $outgoings = Outgoing::latest()->paginate(15);
        return view('outgoings.index', compact('outgoings'));
    }

    public function create()
    {
        return view('outgoings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'code' => 'nullable|string|max:255',
            'item' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'voucher' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'receiver' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Outgoing::create($request->all());

        return redirect()->route('outgoings.index')->with('success', 'تم إضافة عملية الصرف بنجاح');
    }

    public function destroy($id)
    {
        Outgoing::findOrFail($id)->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function destroySelected(Request $request)
    {
        Outgoing::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'تم حذف السجلات المختارة');
    }

    public function destroyAll()
    {
        Outgoing::truncate();
        return back()->with('success', 'تم حذف جميع السجلات');
    }
}
