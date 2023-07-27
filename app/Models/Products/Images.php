<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\Product;

class Images extends Model
{
    use HasFactory;
    protected $table = 'produk_image';
    protected $primaryKey = 'id';
    protected $fillable = [
    'url', 'id_produk'
    ];

    public function product()
    {
    return $this->belongsTo('Product', 'id_produk');
    }
}
