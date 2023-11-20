<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Transaction\Stores;
use App\Models\Transaction\StoresDetail;
use Illuminate\Support\Facades\DB;
use Cart;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //
    }

    public function index()
    {
        $categories = Category::Where('parent_code','')->orderBy('nama_kategori', 'asc')->get();
        $categoryWithProduct = Category::Where('parent_code','')
            ->with('allProduct')
            ->has('allProduct')
            ->orderBy('nama_kategori', 'asc')
            ->get();
        return view('pages.transaction.cashier.index', compact('categories','categoryWithProduct'));
    }

    public function data()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'nama_depan' => 'required',
        //     'nama_belakang' => 'required',
        //     'address' => 'required',
        //     'city' => 'required',
        //     'state' => 'required',
        //     'postal_code' => 'required',
        //     'telepon' => 'required',
        //     'shipping_price' => 'required',
        //     'total_amount' => 'required',
        //     'shipping_option' => 'required',

        // ]);
        $userId = auth()->user()->id;
        $nota = getInvoiceNumber("cashier");

        $input = [
            "device" => "CASHIER",
            "tgl_transaksi" => date('Y-m-d'),
            "nota" => $nota,
            "nama_buyer" => "Walk-in-customer",
            "no_telp" => "",
            "alamat_kirim" => "",
            "kota" => "Bandung",
            "kode_pos" => 0,
            "longlat" => "",
            "tgl_kirim" => date('Y-m-d'),
            "notes" => "offline order",
            "total_item" => count(Cart::Session($userId)->getContent()),
            "total_harga" => Cart::Session($userId)->getTotal(),
            "diskon" => 0,
            "bayar" => Cart::Session($userId)->getTotal()+env('ADMIN_FEE'),
            "diterima" => Cart::Session($userId)->getTotal()+env('ADMIN_FEE'),
            "status_bayar" => "LUNAS",
            "ongkos_kirim" => 0,
            "status_order" => 3,
        ];

        DB::beginTransaction();
        try {
            $penjualan = Stores::create($input);
            foreach (Cart::Session($userId)->getContent() as $item) {
                $prd = Product::where('id_produk','=',$item->id)->first();
                $details[] = StoresDetail::create([
                    "id_penjualan" => $penjualan->id_penjualan,
                    "id_produk" => $item->id,
                    "harga_jual" => $item->price,
                    "jumlah" => $item->quantity,
                    "subtotal" => $item->quantity * $item->price,
                ]);

                $prd->stok -= $item->quantity;
                $prd->save();
                \Cart::Session($userId)->remove($item->id);
            }
            DB::commit();
            return response(null, 200);
        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }
    public function destroy($id)
    {

    }
}
