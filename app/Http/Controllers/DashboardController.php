<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'barang'   => Product::count(),
            'kategori' => Category::count(),
            'masuk'    => StockIn::sum('jumlah'),
            'keluar'   => StockOut::sum('jumlah'),
        ]);
    }
}