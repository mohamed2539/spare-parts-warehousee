<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IncomingItem;
use Illuminate\Http\Request;




class ItemController extends Controller
{
    public function index() {
        return view('items.index');
    }



        // صفحة البحث
        public function searchPage()
        {
            return view('items.search');
        }

    public function searchApi(Request $request)
    {
        $query = $request->query('query');

        $items = IncomingItem::where('code', 'like', "%{$query}%")
            ->orWhere('item', 'like', "%{$query}%")
            ->orWhere('supplier', 'like', "%{$query}%")
            ->take(10)
            ->get();

        return response()->json($items);
    }

    // public function searchPage(Request $request) {
    //     $query = $request->query('query');

    //     $items = IncomingItem::where('code', 'like', "%{$query}%")
    //         ->orWhere('item', 'like', "%{$query}%")
    //         ->orWhere('supplier', 'like', "%{$query}%")
    //         ->take(10)
    //         ->get();

    //     return response()->json($items);
    // }


    public function addStockForm() {
        return view('items.add');
    }

    
    public function addStockAjax(Request $request) {
        $request->validate([
            'item_id' => 'required|exists:incoming_items,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);
    
        $item = IncomingItem::find($request->item_id);
        $item->quantity += $request->quantity;
        $item->save();
    
        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الكمية بنجاح ✅',
            'new_quantity' => $item->quantity
        ]);
    }

    // public function addStockAjax(Request $request) {
    //     $request->validate([
    //         'item_id' => 'required|exists:incoming_items,id',
    //         'quantity' => 'required|numeric|min:0.01'
    //     ]);
    
    //     $item = IncomingItem::find($request->item_id);
    //     $item->quantity += $request->quantity;
    //     $item->save();
    
    //     return redirect()->back()->with('success', 'تمت إضافة الكمية بنجاح');
    // }




}

