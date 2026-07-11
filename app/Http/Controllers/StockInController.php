<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('nama_barang')->get();

        return view('stock_in.index', compact('products'));
    }

    public function list()
    {
        return response()->json(

            StockIn::with('product')
                ->latest()
                ->get()

        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'product_id' => 'required|exists:products,id',
                'jumlah'     => 'required|integer|min:1',
                'tanggal'    => 'required|date',
            ],
            [
                'product_id.required' => 'Barang harus dipilih.',
                'jumlah.required'     => 'Jumlah wajib diisi.',
                'jumlah.min'          => 'Jumlah minimal 1.',
                'tanggal.required'    => 'Tanggal wajib diisi.',
            ]
        );

        DB::transaction(function () use ($request) {

            StockIn::create([
                'product_id' => $request->product_id,
                'jumlah'     => $request->jumlah,
                'tanggal'    => $request->tanggal,
            ]);

            $product = Product::findOrFail($request->product_id);

            $product->increment('stok', $request->jumlah);

        });

        return response()->json([
            'status'  => true,
            'message' => 'Barang masuk berhasil disimpan.'
        ]);
    }
    
}