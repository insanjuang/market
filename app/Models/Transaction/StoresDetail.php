<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\Stores;
use App\Models\Products\Product;



class StoresDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_penjualan_detail';
    protected $guarded = [];

    public function header()
    {
        return $this->belongsTo(Stores::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id_produk', 'id_produk');
    }

}
