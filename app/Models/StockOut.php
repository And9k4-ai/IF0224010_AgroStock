<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'product_id',
        'jumlah',
        'tanggal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
