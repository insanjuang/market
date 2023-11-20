<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products\Product;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasMany(Product::class, 'id_subkategori', 'id_kategori');
    }

    public function allProduct()
    {
        return $this->hasMany(Product::class, 'id_kategori', 'id_kategori');
    }

    public function newProduct()
    {
        return $this->product()->latest()->limit(8);
    }

    public function cheapProduct()
    {
        return $this->product()->orderBy('harga_jual')->limit(8);
    }
}
