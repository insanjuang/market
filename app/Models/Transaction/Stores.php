<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction\StoresDetail;


class Stores extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(StoresDetail::class, 'id_penjualan', 'id_penjualan');
    }
}
