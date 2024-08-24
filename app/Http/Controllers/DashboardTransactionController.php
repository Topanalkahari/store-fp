<?php

namespace App\Http\Controllers;

use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Transaction;
use App\Product;

class DashboardTransactionController extends Controller
{
    public function index()
    {
        $sellTransactions = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->whereHas('product', function($product){
                                $product->where('users_id', Auth::user()->id);
                            })->get();
        $buyTransactions = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->whereHas('transaction', function($transaction){
                                $transaction->where('users_id', Auth::user()->id);
                            })->get();
        
        return view('pages.dashboard-transactions',[
            'sellTransactions' => $sellTransactions,
            'buyTransactions' => $buyTransactions
        ]);
    }

    public function details(Request $request, $id)
    {
        $transaction = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->findOrFail($id);
        return view('pages.dashboard-transactions-details',[
            'transaction' => $transaction
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $item = TransactionDetail::findOrFail($id);

        $transaction = Transaction::findOrFail($id);

        // Update status transaksi
        // $transaction->update($data);

        // Jika status transaksi diubah menjadi SUCCESS
        if ($transaction->transaction_status == 'SUCCESS' && $transaction->transaction_status != 'SUCCESS') {
            $details = $transaction->details;
            
            // Mengurangi stok produk
            foreach ($details as $detail){
                $product = Product::find($detail->products_id);;
                if ($product) {
                    $product->stock -= 1; // Sesuaikan dengan jumlah produk yang dibeli
                    $product->save();
                }
            }
            
        }

        $transaction->update($data);

        return redirect()->route('dashboard-transaction-details', $id);
    }
}
