<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products\Product;

class Supplier extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasMany(Product::class, 'id_supplier', 'id_supplier');
    }
}
