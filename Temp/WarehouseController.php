<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    // عرض قائمة المخازن
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('warehouses'));
    }

    // صفحة إضافة مخزن جديد
    public function create()
    {
        return view('warehouses.create');
    }

    // حفظ مخزن جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('warehouses.index')->with('success', 'تمت إضافة المخزن بنجاح');
    }
}
