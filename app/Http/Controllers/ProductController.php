<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        $readonly = auth()->user()->role == 'user';

        $categories = Category::orderBy('nama')->get();

        return view('product.index', compact(
            'categories',
            'readonly'
        ));
    }

        /**
     * Mengambil data produk untuk AJAX dan Live Search
     */
    public function list(Request $request){
    $query = Product::with('category');

    $search = $request->search;

    if ($request->filled('search')) {

        $query->where(function ($q) use ($search) {

            $q->where('kode','like',"%{$search}%")
              ->orWhere('nama_barang','like',"%{$search}%")
              ->orWhere('satuan','like',"%{$search}%")
              ->orWhereHas('category',function($category) use($search){

                    $category->where('nama','like',"%{$search}%");

              });

        });

    }

    return response()->json(

        $query->orderByDesc('id')->get()

    );
    }

    /**
     * Simpan data
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'kode' => 'required|unique:products,kode',
                'nama_barang' => 'required|max:100',
                'stok' => 'required|integer|min:0',
                'satuan' => 'required|max:30',
                'harga' => 'required|numeric|min:0'
            ],
            [
                'category_id.required' => 'Kategori harus dipilih.',
                'category_id.exists' => 'Kategori tidak ditemukan.',
                'kode.required' => 'Kode barang wajib diisi.',
                'kode.unique' => 'Kode barang sudah digunakan.',
                'nama_barang.required' => 'Nama barang wajib diisi.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Stok harus berupa angka.',
                'harga.required' => 'Harga wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.'
            ]
        );

        Product::create([
            'category_id' => $request->category_id,
            'kode' => $request->kode,
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
            'harga' => $request->harga
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditambahkan'
        ]);
    }

    /**
     * Ambil satu data
     */
    public function edit($id)
    {
    $product = Product::findOrFail($id);

    return response()->json($product);
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kode' => 'required|unique:products,kode,' . $id,
            'nama_barang' => 'required|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|max:30',
            'harga' => 'required|numeric|min:0'
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'kode' => $request->kode,
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
            'harga' => $request->harga
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah'
        ]);
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        Product::destroy($id);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}