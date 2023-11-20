<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Transaction\Stores;
use App\Models\Transaction\StoresDetail;
use Illuminate\Support\Facades\DB;
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

    public function getCart()
    {
        $cartItems = \Cart::getContent();
        $cartArray = $cartItems->toArray();
        $totalItem = count($cartItems);
        $totalQuantity = \Cart::getTotalQuantity();
        $subTotalPrice = \Cart::getSubTotal();
        $totalPrice = \Cart::getTotal();
        $taxPrice = env('ADMIN_FEE');

        return response()->json([
            'data'=>$cartArray,
            'totalItem'=>$totalItem,
            'totalQty'=>$totalQuantity,
            'subTotal'=>"Rp ".number_format($subTotalPrice),
            'total'=>"Rp ".number_format($totalPrice),
            'tax'=>"Rp ".number_format($taxPrice)
        ]);
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
        $origin = env('STORE_GEOCODE','-6.935275,107.619703');
        $destination = $request->input('destination');
        $mode = env('DIRECTION_MODE','driving'); // Default to driving mode
        $minimum_radius = env('SHIPPING_MINIMUM_RADIUS');

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => $origin,
            'destinations' => $destination,
            'mode' => $mode,
            'units' => 'metric',
            'key' => env('GOOGLE_API_KEY','AIzaSyBqBoLvcExO_q_AvmG6dzjAj4yyyL2Yc_8'), // Replace with your actual API key
        ]);

        $data = $response->json();

        if ($data['status'] === 'OK') {
            $distanceText = $data['rows'][0]['elements'][0]['distance']['text'];
            $distance = $data['rows'][0]['elements'][0]['distance']['value'];
            $durationText = $data['rows'][0]['elements'][0]['duration']['text'];

            $price = "Gratis Ongkir";
            $distancekm = $distance/1000;
            $restDistance = round($distancekm - $minimum_radius);
            $total_price = Cart::getSubTotal()+1000;
            $shipping_price = 0;
            if ($restDistance > 0) {
                $shipping_price = $restDistance * env('SHIPPING_PRICE',1000);
                $total_price += $shipping_price;
            }

            return response()->json([
                'distance' => $distanceText,
                'duration' => $durationText,
                'product_price' => number_format(Cart::getSubTotal(),0,',','.'),
                'tax_price' => number_format(env('ADMIN_FEE'),0,',','.'),
                'shipping_price' => number_format($shipping_price,0,',','.'),
                'total_price' => number_format($total_price,0,',','.')
            ]);
        } else {
            return response()->json(['error' => 'Failed to calculate distance','data'=> $data], 500);
        }
    }

    public function saveCheckout(Request $request)
    {
        $request->validate([
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'telepon' => 'required',
            'shipping_price' => 'required',
            'total_amount' => 'required',
            'shipping_option' => 'required',

        ]);
        $nota = getInvoiceNumber("web");

        $shipping_date = ($request->input("shipping_option") == 1) ? date("Y-m-d", strtotime("+1 day")) : date("Y-m-d", strtotime("+2 day")) ;

        $input = [
            "device" => "WEB",
            "tgl_transaksi" => date('Y-m-d'),
            "nota" => $nota,
            "nama_buyer" => $request->input("nama_depan")." ".$request->input("nama_belakang") ,
            "no_telp" => $request->input("telepon"),
            "alamat_kirim" => $request->input("address"),
            "kota" => $request->input("city"),
            "kode_pos" => $request->input("postal_code"),
            "longlat" => $request->input("longlat"),
            "tgl_kirim" => $shipping_date,
            "notes" => $request->input("catatan"),
            "total_item" => count(Cart::getContent()),
            "total_harga" => Cart::getTotal(),
            "diskon" => 0,
            "bayar" => str_replace(".","",$request->input("total_amount")),
            "ongkos_kirim" => str_replace(".","",$request->input("shipping_price")),
            "status_order" => 0,
        ];

        DB::beginTransaction();
        try {
            $penjualan = Stores::create($input);
            foreach (Cart::getContent() as $item) {
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
                \Cart::remove($item->id);
            }
            DB::commit();
            return view('pages.store.success-order');
        }catch(\Exception $e){
            DB::rollback();

            return redirect()->back()->withErrors('Gagal melakukan pesanan')->withInput();
            // return response()->json(['error' => $e->getMessage()], 500);

        }
    }
}
