<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Cart;


class StoreController extends Controller
{
    public function index()
    {
        $categories = Category::Where('parent_code','')->orderBy('nama_kategori', 'asc')->limit(5)->get();
        $categoryWithNewProduct = Category::with('newProduct')
            ->has('newProduct')
            ->orderByDesc(Product::select('id_subkategori')
                ->whereColumn('produk.id_subkategori', 'kategori.id_kategori')
                ->latest()
                ->take(1)
            )
            ->limit(3)
            ->get();
        $categoryWithCheapProduct = Category::with('cheapProduct')
            ->has('cheapProduct')
            ->orderByDesc(Product::select('id_subkategori')
                ->whereColumn('produk.id_subkategori', 'kategori.id_kategori')
                ->orderBy('harga_jual')
                ->take(1)
            )
            ->limit(3)
            ->get();


        return view('pages.store.index',compact('categories','categoryWithNewProduct','categoryWithCheapProduct'));
    }

    public function product(Request $request)
    {
        if ($request->query('category') == 'all') {
            $kategori = "All";
        } else {
            $kategori = Category::Select('nama_kategori')->where('kode',$request->query('category'))->first();
        }
        $products = Product::Select('produk.*','kategori.nama_kategori','kategori.kode','supplier.nama as nama_supplier')->with('images')
                ->leftJoin('kategori','kategori.id_kategori','=','produk.id_kategori')
                ->leftJoin('supplier','supplier.id_supplier','=','produk.id_supplier')
                ->orderBy('produk.id_kategori', 'desc')->paginate(3);

        return view('pages.store.product-list',compact('kategori','products'));
    }

    public function productDetail($id,Request $request)
    {
        $product = Product::Select('produk.*','kategori.nama_kategori','kategori.kode','supplier.nama as nama_supplier')->with('images')
                ->leftJoin('kategori','kategori.id_kategori','=','produk.id_kategori')
                ->leftJoin('supplier','supplier.id_supplier','=','produk.id_supplier')
                ->where('produk.id_produk',$id)->first();
        return view('pages.store.product-detail', compact('product'));
    }

    public function cartList()
    {
        $cartItems = \Cart::getContent();
        // dd($cartItems);
        return view('pages.store.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'qty' => 'required|numeric|min:1',
        ]);

        $product = Product::with('images')->find($request->id_produk);
        // dd($product);
        Cart::add([
            'id' => $product->id_produk,
            'name' => $product->nama_produk,
            'price' => $product->harga_jual,
            'quantity' => $request->qty,
            'attributes' => array(
                'image' => (count($product->images) == 0) ? asset('assets/img/product/product1.jpg') : asset('storage/'.$product->images[0]->url),
            ),
            'associatedModel' => $product,
        ]);
        // session()->flash('success', 'Product is Added to Cart Successfully !');

        return response(null, 200);
    }

    public function removeCart($id, Request $request)
    {
        \Cart::remove($id);
        // session()->flash('success', 'Item Cart Remove Successfully !');

        return response(null,200);
    }

    // public function clearAllCart()
    // {
    //     \Cart::clear();

    //     // session()->flash('success', 'All Item Cart Clear Successfully !');

    //     return response()->json();
    // }

    public function checkout()
    {
        return view('pages.store.checkout');
    }

    public function getShippingPrice(Request $request)
    {
        $origin = env('STORE_GEOCODE');
        $destination = $request->input('destination');
        $mode = 'driving'; // Default to driving mode
        $minimum_radius = env('SHIPPING_MINIMUM_RADIUS');

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => [$origin],
            'destinations' => [$destination],
            'mode' => $mode,
            'units' => 'metric',
            'key' => env('GOOGLE_API_KEY'), // Replace with your actual API key
        ]);

        $data = $response->json();

        if ($data['status'] === 'OK') {
            $distanceText = $data['rows'][0]['elements'][0]['distance']['text'];
            $distance = $data['rows'][0]['elements'][0]['distance']['value'];
            $durationText = $data['rows'][0]['elements'][0]['duration']['text'];

            $price = 0;
            if (($distance/1000000) > $minimum_radius) {
                $price = (($distance/1000000) - $minimum_radius) * env('SHIPPING_PRICE');
            }

            return response()->json([
                'distance' => $distanceText,
                'duration' => $durationText,
                'price' => $price,
            ]);
        } else {
            return response()->json(['error' => 'Failed to calculate distance','data'=> $data], 500);
        }
    }

    public function saveCheckout(Request $request)
    {
        # code...
    }
}
