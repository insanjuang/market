<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Models\Products\Product;
use App\Models\Transaction\Purchase;
use App\Models\Transaction\PurchaseDetail;
use App\Models\Entities\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Alert;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('pages.transaction.purchase.index');
    }

    public function data()
    {
        $purchase = Purchase::Select('pembelian.*','supplier.nama as supplier_name')
                    ->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
                    ->orderBy('pembelian.tgl_transaksi', 'desc')->get();

        // return view('pages.transaction.purchase.index');
        // return response()->json($purchase);
        // ddd($purchase);
        return datatables()
            ->of($purchase)
            ->addIndexColumn()
            ->addColumn('order_status', function ($purchase){
                $badges = "";
                switch ($purchase->status) {
                    case 0:
                        $badges = '<span class="badges bg-lightyellow">Ordered</span>';
                        break;
                    case 1:
                        $badges = '<span class="badges bg-lightgreen">Received</span>';
                        break;
                    case 2:
                        $badges = '<span class="badges bg-lightred">Canceled</span>';
                        break;
                }
                return $badges;
            })
            ->addColumn('paid_status', function ($purchase){
                $badges = "";
                switch ($purchase->status_bayar) {
                    case "Paid":
                        $badges = '<span class="badges bg-lightgreen">Paid</span>';
                        break;
                    case "Unpaid":
                        $badges = '<span class="badges bg-lightred">Unpaid</span>';
                        break;
                    case "Partial":
                        $badges = '<span class="badges bg-lightyellow">Partial</span>';
                        break;
                }
                return $badges;
            })
            ->addColumn('action', function ($purchase) {
                return '
                        <a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu"  >
                            <li>
                                <a href="'.route('purchase.show',$purchase->id_pembelian).'" class="dropdown-item"><img src="'. asset('/assets/img/icons/eye1.svg').'" class="me-2" alt="img">Purchase Detail</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showpayment" onclick="showPayment('.$purchase->id_pembelian.')"><img src="'. asset('/assets/img/icons/dollar-square.svg').'" class="me-2" alt="img">Show Payments</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createpayment"><img src="'. asset('/assets/img/icons/plus-circle.svg') .'" class="me-2" alt="img">Create Payment</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item"><img src="'. asset('/assets/img/icons/edit.svg') .'" class="me-2" alt="img">Update Status</a>
                            </li>
                        </ul>
                    ';
            })
            ->rawColumns(['order_status','paid_status','action'])
            ->make(true);

    }

    public function addRow(Request $request)
    {
        $productID = $request->string('id_produk');

        if (isset($productID) && $productID != "") {
            $product = DB::table('produk')->orderby('id_produk','asc')->select('id_produk','kode_produk','nama_produk','concat("/storage/uploads/image/product/list/",image) as image','harga_beli')
            ->when($productID, function (Builder $query, string $productID) {
                $query->where('id_produk', $productID);
            })
            ->get();
        }

        // $res ='<tr>
        //                 <td class="productimgname">
        //                     <a class="product-img">
        //                         <img src="'. asset('storage/'.$this->imgUploadsPath.'/'.$product->image) .'" alt="product">
        //                     </a>
        //                     <p id="price-'.$product->id_produk.'">'. $product->nama_produk .'</p>
        //                 </td>
        //                 <td><input type="number" class="form-control qty" id="qty-'.$product->id_produk.'" name="qty" min="1" value="1"></td>
        //                 <td>'. $product->harga_beli.'</td>
        //                 <td><input type="number" class="form-control discount" id="discount-'.$product->id_produk.'" name="discount" min="0"></td>
        //                 <td class="paid" id="paid-'.$product->id_produk.'">0.00</td>
        //                 <td class="subtotal" id="subtotal-'.$product->id_produk.'">0.00</td>
        //                 <td>
        //                     <a class="delete-set"><img src="'. asset('/assets/img/icons/delete.svg') .'" alt="svg"></a>
        //                 </td>
        //             </tr>';

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
        return view('pages.transaction.purchase.add',compact('supplier'));
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
            'tgl_transaksi' => 'required',
            'tipe_pembelian' => 'required',
            'status_bayar' => 'required',

        ]);
        $nota = $request->input("kode_nota");
        if ($nota == "") {
            $nota = $this->invoiceNumber();
        }

        $products = $request->input('produk_id');
        $qty = $request->input('qty');
        $discountProduct = $request->input('discount_product');
        $pay = $request->input("total");
        if ($request->input("status_bayar") != "Paid" || $request->input("tipe_pembelian") != "Cash"){
            $pay = 0;
        }

        $input = [
            "id_supplier" => $request->input("id_supplier"),
            "tgl_transaksi" => date('Y-m-d',strtotime($request->input("tgl_transaksi"))),
            "kode_nota" => $nota,
            "total_item" => count($products),
            "total_harga" => $request->input("total")+$request->input("diskon_pembelian"),
            "diskon" => $request->input("diskon_pembelian"),
            "bayar" => $pay,
            "tipe_pembelian" => $request->input("tipe_pembelian"),
            "status" => 0,
            "status_bayar" => $request->input("status_bayar"),
            "deskripsi" => $request->input("deskripsi"),
            "ongkos_kirim" => $request->input("ongkos_kirim"),
            "id_user" => auth()->user()->id,
        ];

        DB::beginTransaction();
        try {
            $pembelian = Purchase::create($input);
            foreach ($products as $key => $product) {
                $prd = Product::where('id_produk','=',$product)->first();
                $details[] = PurchaseDetail::create([
                    "id_pembelian" => $pembelian->id_pembelian,
                    "id_produk" => $product,
                    "harga_beli" => $prd->harga_beli,
                    "jumlah" => $qty[$key],
                    "diskon_produk" => $discountProduct[$key],
                    "subtotal" => $qty[$key] * ($prd->harga_beli - $discountProduct[$key]),
                ]);

                $prd->stok += $qty[$key];
                $prd->save();

            }
            DB::commit();
            Alert::success('Success', 'Purchase created successfully');
            return redirect()->route('purchase.index');
        }catch(\Exception $e){
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
        $purchase = Purchase::Select('pembelian.*','supplier.nama as supplier_name', 'supplier.alamat as supplier_address', 'supplier.telepon as supplier_phone','supplier.email')
                    ->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
                    ->where('id_pembelian','=',$id)
                    ->first();
        $purchaseDetail = PurchaseDetail::Select('pembelian_detail.*','produk.nama_produk','produk.image')
                    ->leftJoin('produk','produk.id_produk','=','pembelian_detail.id_produk')
                    ->where('id_pembelian','=',$purchase->id_pembelian)
                    ->get();


        return view('pages.transaction.purchase.details',compact(['purchase','purchaseDetail']));;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('pages.transaction.purchase.edit',compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        // $request->validate([
        //     'nama_kategori' => 'required',
        //     'kode' => 'required',
        //     'image' => 'image|mimes:jpeg,png,jpg,svg|file|max:1024',
        // ]);

        // $input = $request->except(['oldImage']);

        // if ($image = $request->file('image')) {
        //     if ($request->input('oldImage')) {
        //         Storage::delete($request->input('oldImage'));
        //     }
        //     $profileImage = date('YmdHis') ."-".$input['kode']. "." . $image->getClientOriginalExtension();
        //     $image->storeAs($this->imgUploadsPath, $profileImage);
        //     $input['image'] = "$profileImage";
        // }else{
        //     unset($input['image']);
        // }

        // $category->update($input);

        Alert::success('Success', 'Purchase updated successfully');
        return redirect()->route('purchase.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $purchase->delete();

        return response(null, 204);
    }

    private function invoiceNumber()
    {
        $date = date("ymd");
        $latest = Purchase::latest()->where('kode_nota', 'like', '%'.$date.'%')->first();

        if (! $latest) {
            return 'ADN-'.$date.'-0001';
        }
        $expNum = explode('-', $latest->kode_nota);

        return 'ADN-'. $date . sprintf('-%04d', $expNum[2]+1);
    }
}
