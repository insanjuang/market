<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products\Images;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function images()
    {
        return $this->hasMany(Images::Class, 'id_produk');
    }
}
