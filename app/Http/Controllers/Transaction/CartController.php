<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

    }

    public function index()
    {
        $userId = auth()->user()->id;
        $cartItems = \Cart::Session($userId)->getContent();
        $cartArray = $cartItems->toArray();
        $totalItem = count($cartItems);
        $subTotalPrice = \Cart::Session($userId)->getSubTotal();
        $totalPrice = \Cart::Session($userId)->getTotal();
        $taxPrice = env('ADMIN_FEE');

        return response()->json([
            'data'=>$cartArray,
            'totalItem'=>$totalItem,
            'subTotal'=>"Rp ".number_format($subTotalPrice),
            'total'=>"Rp ".number_format($totalPrice+$taxPrice),
            'tax'=>"Rp ".number_format($taxPrice)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk'
        ]);

        $product = Product::with('images')->find($request->id_produk);
        // dd($product);

        $userId = auth()->user()->id;
        Cart::session($userId)->add([
            'id' => $product->id_produk,
            'name' => $product->nama_produk,
            'price' => $product->harga_jual,
            'quantity' => 1,
            'attributes' => array(
                'image' => (count($product->images) == 0) ? asset('assets/img/product/product1.jpg') : asset('storage/'.$product->images[0]->url),
            ),
            'associatedModel' => $product,
        ]);
        // session()->flash('success', 'Product is Added to Cart Successfully !');

        return response(null, 200);
    }

    public function create()
    {

    }


    public function clear()
    {
        $userId = auth()->user()->id;
        \Cart::Session($userId)->clear();

        return response()->json(['data'=>null]);
    }


    public function show($id)
    {

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'qty' => 'required'

        ]);

        $userId = auth()->user()->id;
        Cart::session($userId)->update($id,[
            'quantity' => $request->qty,
        ]);

        return response(null,200);
    }

    public function destroy($id)
    {
        $userId = auth()->user()->id;
        \Cart::Session($userId)->remove($id);

        return response(null,200);
    }
}
