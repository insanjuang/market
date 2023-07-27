<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}
