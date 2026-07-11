<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('nama_barang')->get();

        return view('stock_out.index', compact('products'));
    }

    public function list()
    {
        return response()->json(

            StockOut::with('product')
                ->latest()
                ->get()

        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'product_id'=>'required|exists:products,id',
                'jumlah'=>'required|integer|min:1',
                'tanggal'=>'required|date'
            ]
        );

        DB::transaction(function() use($request){

            $product = Product::findOrFail($request->product_id);

            if($product->stok < $request->jumlah){

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah'=>'Stok tidak mencukupi.'
                ]);

            }

            StockOut::create([

                'product_id'=>$request->product_id,

                'jumlah'=>$request->jumlah,

                'tanggal'=>$request->tanggal

            ]);

            $product->decrement('stok',$request->jumlah);

        });

        return response()->json([

            'status'=>true,

            'message'=>'Barang keluar berhasil disimpan.'

        ]);

    }
    
}