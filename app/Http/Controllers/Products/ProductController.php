<?php

namespace App\Http\Controllers\Products;

use App\Models\Products\Category;
use App\Models\Entities\Supplier;
use Illuminate\Http\Request;
use App\Models\Products\Product;
use App\Models\Products\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $imgUploadsPath;

    public function __construct()
    {
        $this->imgUploadsPath = 'uploads/image/product/list';
    }

    public function index()
    {
        return view('pages.products.product.index');
    }

    public function data()
    {
        $produk = Product::Select('produk.*','kategori.nama_kategori','kategori.kode','supplier.nama as nama_supplier')->with('images')
                ->leftJoin('kategori','kategori.id_kategori','=','produk.id_kategori')
                ->leftJoin('supplier','supplier.id_supplier','=','produk.id_supplier')
                ->orderBy('produk.id_kategori', 'desc')->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('produkimgname', function ($produk) {
                if (count($produk->images) > 0) {
                    return '<a href="#" class="product-img">
                                <img src="'.asset('storage/'.$produk->images[0]->url).'" alt="product">
                            </a>
                            <a href="#">'.$produk->nama_produk.'</a>';
                } else {
                    return  '<a href="#" class="product-img">
                                <img src="'.asset('assets/img/product/product1.jpg').'" alt="product">
                            </a>
                            <a href="#">'.$produk->nama_produk.'</a>';
                }
            })
            ->addColumn('status', function ($produk) {
                $checked = ($produk->is_active == 1)? 'checked': '';
                return '<div class="status-toggle d-flex justify-content-between align-items-center">
                            <input type="checkbox" id="check-'.$produk->id_produk.'" onclick="updateStatus(`'.route('product.updateStatus', $produk->id_produk).'`)" class="check" '.$checked.'>
                            <label for="check-'.$produk->id_produk.'" class="checktoggle">status</label>
                        </div>';
            })
            ->addColumn('action', function ($produk) {
                return '
                        <a class="me-3" href="'.route('product.edit', $produk->id_produk).'">
                            <img src="'.asset('/assets/img/icons/edit.svg').'" alt="img">
                        </a>
                        <a class="me-3" onclick="deleteData(`'.route('product.destroy', $produk->id_produk).'`)">
                            <img src="'. asset('/assets/img/icons/delete.svg').'" alt="img">
                        </a>
                    ';
            })
            ->rawColumns(['produkimgname','status','action'])
            ->make(true);
    }

    public function dataJson(Request $request)
    {
        $search = $request->search;
        $supplierID = $request->string('id_supplier');
        $productID = $request->string('id_produk');

        if (isset($supplierID) && $supplierID != "") {
            $product = Product::Select('id_produk','kode_produk','nama_produk','image','harga_beli')->orderBy('id_produk','asc')
            -When($supplierID, function (Builder $query, string $supplierID) {
                $query->Where('id_supplier', $supplierID);
            })
            ->get();
        } else if (isset($productID) && $productID != "") {
            $product = Product::Select('id_produk','kode_produk','nama_produk','image','harga_beli')->orderBy('id_produk','asc')
            ->When($productID, function (Builder $query, string $productID) {
                $query->Where('id_produk', $productID);
            })
            ->whereNull('deleted_at')
            ->get();
        }

        return response()->json($product);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplier = Supplier::Select('id_supplier','nama')->get();
        $kategori = Category::Select('id_kategori','nama_kategori','kode')->where('parent_code','')->get();
        return view('pages.products.product.form', compact('kategori','supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required',
            'id_kategori' => 'required',
            'kode_produk' => 'unique:produk',
            'nama_produk' => 'required',
            'harga_beli' => 'required',
            'harga_agen' => 'required',
            'harga_reseller' => 'required',
            'harga_jual' => 'required',
            'image' => 'required|max:1024'
        ]);

        $kategori = Category::Select('id_kategori')->where('kode',$request->id_kategori)->first();

        DB::beginTransaction();
        try {
            $produk = New Product;
            $produk->id_kategori = $kategori->id_kategori;
            $produk->id_subkategori = $request->id_subkategori;
            $produk->id_supplier = $request->id_supplier;
            $produk->kode_produk = $request->kode_produk;
            $produk->nama_produk = $request->nama_produk;
            $produk->description = $request->description;
            $produk->harga_beli = $request->harga_beli;
            $produk->harga_agen = $request->harga_agen;
            $produk->harga_reseller = $request->harga_reseller;
            $produk->harga_jual = $request->harga_jual;
            $produk->stok = 0;
            $produk->diskon_rp = $request->diskon_rp;
            $produk->diskon_persen = $request->diskon_persen;
            $produk->is_active = ($request->is_active !== null) ? 1 : 0;
            $produk->min_order = $request->min_order;
            $produk->preorder = ($request->preorder !== null) ? 1 : 0;
            $produk->berat = $request->berat;
            $produk->save();


            $x = 1;
            foreach ($request->file('image') as $imagefile) {
                $profileImage = date('YmdHis') ."-".$request->kode_produk."-".$x."." . $imagefile->getClientOriginalExtension();
                $path = $imagefile->storeAs($this->imgUploadsPath, $profileImage);
                $image = new Image;
                $image->url = $path;
                $image->id_produk = $produk->id_produk;
                $image->save();
                $x++;
            }

            DB::commit();
            Alert::success('Success', 'Product created successfully');
            return redirect()->route('product.index');
        }catch(\Exception $e){
            // ddd($e);
            DB::rollback();

            Alert::warning('Warning', 'Something Went Wrong!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produk = Product::findOrFail($id);
        $supplier = Supplier::Select('id_supplier','nama')->get();
        $kategori = Category::Select('id_kategori','nama_kategori','kode')->where('parent_code','')->get();
        return view('pages.products.product.form',compact('produk','supplier','kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'id_supplier' => 'required',
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'harga_beli' => 'required',
            'harga_agen' => 'required',
            'harga_reseller' => 'required',
            'harga_jual' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|file|max:1024',
        ]);

        $input = $request->except(['oldImage']);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $key => $value) {
                    Storage::delete($product->images[$key]);
                    $profileImage = date('YmdHis') ."-".$input['kode_produk']."-".$key."." . $image->getClientOriginalExtension();
                    $path = $image->storeAs($this->imgUploadsPath, $profileImage);
                    $product->images[$key]->url = $path;
                    $product->images[$key]->save();
                }
            }

            $product->update($input);
            DB::commit();
            Alert::success('Success', 'Product updated successfully');
            return redirect()->route('product.index');
        }catch(\Exception $e){
            // ddd($e);
            DB::rollback();

            Alert::warning('Warning', 'Something Went Wrong!');
            return redirect()->back();
        }
    }

    public function updateStatus($id)
    {
        $product = Product::find($id);
        if ($product->is_active == 1){
            $product->is_active = 0;
        } else {
            $product->is_active = 1;
        }
        $product->save();

        return response(null, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $product = Product::find($id);
            $product->delete();
        }

        return response(null, 204);
    }

}
