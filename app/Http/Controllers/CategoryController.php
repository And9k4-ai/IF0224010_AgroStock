<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $readonly = auth()->user()->role == 'user';

        return view('category.index', compact('readonly'));
    }

    public function list(Request $request)
    {
        $query = Category::query();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return response()->json(
            $query->orderBy('id', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100'
        ]);

        Category::create([
            'nama' => $request->nama
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Data berhasil ditambahkan'
            ]);
    }

    public function edit($id)
    {
        return response()->json(
            Category::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:100'
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'nama' => $request->nama
        ]);

        return response()->json([
            'status' => true,
            'message'=>'Data berhasil diubah'
        ]);
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}