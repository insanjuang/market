<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products\Category;
use App\Models\Products\Product;


class StoreController extends Controller
{
    public function index()
    {
        $categories = Category::Where('parent_code','')->orderBy('nama_kategori', 'desc')->limit(5)->get();
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
}
