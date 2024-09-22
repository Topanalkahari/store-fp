<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\OffStoreSale;
use Illuminate\Support\Facades\DB;

class OffStoreController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pages.admin.off-store.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->products_id);
            
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }

            $total_price = $product->price * $request->quantity;

            $product->stock -= $request->quantity;
            $product->save();

            OffStoreSale::create([
                'products_id' => $request->products_id,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
            ]);

            DB::commit();
            return back()->with('success', 'Offline sales are successfully recorded.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}