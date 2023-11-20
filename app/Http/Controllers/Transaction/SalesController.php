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
use PDF;

class SalesController extends Controller
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
        return view('pages.transaction.sales.index');
    }

    public function data()
    {
        $stores = Stores::orderBy('tgl_transaksi', 'desc')->get();

        // return response()->json($stores);
        // ddd($stores);
        return datatables()
            ->of($stores)
            ->addIndexColumn()
            ->addColumn('order_status', function ($stores) {
                $badges = '';
                switch ($stores->status_order) {
                    case 0:
                        $badges = '<span class="badges bg-lightgrey">Ordered</span>';
                        break;
                    case 1:
                        $badges = '<span class="badges bg-lightyellow">Processed</span>';
                        break;
                    case 2:
                        $badges = '<span class="badges bg-lightyellow">Shipped</span>';
                        break;
                    case 3:
                        $badges = '<span class="badges bg-lightgreen">Completed</span>';
                        break;
                    case 4:
                        $badges = '<span class="badges bg-lightred">Canceled</span>';
                        break;
                    default:
                        $badges = '<span class="badges bg-lightgrey">Ordered</span>';
                        break;
                }
                return $badges;
            })
            ->addColumn('paid_status', function ($stores) {
                $badges = '';
                switch ($stores->status_bayar) {
                    case 'BELUM LUNAS':
                        $badges = 'bg-lightred';
                        break;
                    case 'LUNAS':
                        $badges = 'bg-lightgreen';
                        break;
                }

                return '<span class="badges ' . $badges . '">' . $stores->status_bayar . '</span>';
            })
            ->addColumn('action', function ($stores) {
                $button = '
                        <a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu"  >
                            <li>
                                <a href="' .
                                    route('sales.show', $stores->nota) .
                                '" class="dropdown-item"><img src="' .
                    asset('/assets/img/icons/eye1.svg') .
                    '" class="me-2" alt="img">Order Detail</a>
                            </li>';

                if (($stores->status_order == 0) && ($stores->status_order != 3)) {
                    $button .= '<li>
                                <a href="#" class="dropdown-item" onclick="cancelOrder('.$stores->id_penjualan.')"><img src="' .
                    asset('/assets/img/icons/return1.svg') .
                    '" class="me-2" alt="img">Cancel Order</a>
                            </li>';
                } else if ($stores->status_order != 0 && $stores->status_order != 4 && $stores->status_order != 3) {
                        $button .= '<li>
                                <a href="#" class="dropdown-item" onclick="shippingOrder('.$stores->id_penjualan.','.$stores->status_order.')"><img src="' .
                    asset('/assets/img/icons/product.svg') .
                    '" class="me-2" alt="img">Create Shipping</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" onclick="completeOrder('.$stores->id_penjualan.','.$stores->status_order.')"><img src="' .
                    asset('/assets/img/icons/transcation.svg') .
                    '" class="me-2" alt="img">Complete Order</a>
                            </li>';
                }
                    $button .='</ul>';
                    return $button;
            })
            ->rawColumns(['order_status', 'paid_status', 'action'])
            ->make(true);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($nota)
    {
        $penjualan = Stores::where('nota', $nota)
                ->with('details')
                ->first();
        return view('pages.transaction.sales.details',compact('penjualan'));
    }

    public function edit($id)
    {

    }

    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status_order' => 'required'
        ]);



        $penjualan = Stores::where('id_penjualan','=',$id)->first();
        $penjualan->status_order = $request->status_order;
        switch ($request->status_order) {
            case 2:
                $penjualan->status_kirim = 1;
                break;
            case 3:
                $penjualan->diterima=$penjualan->bayar;
                $penjualan->tgl_lunas=date('Y-m-d');
                $penjualan->status_bayar="LUNAS";
        }
        $penjualan->save();

        return response(null,200);
    }

    public function destroy($id)
    {

    }

    public function generateInvoice($id)
    {
        $penjualan = Stores::where('nota','=',$id)
                ->with('details')
                ->first();
  
        $data = [
            'title' => 'Nota Penjualan',
            'date' => date('m/d/Y'),
            'penjualan' => $penjualan
        ]; 
            
        $pdf = PDF::loadView('pages.transaction.sales.invoice', $data);
        $pdf->setPaper('a5', 'landscape');
        $pdf->setOptions(['margin-top' => 5]);
     
        return $pdf->download('Nota-'.$penjualan->nota.'.pdf');
    }

    public function printInvoice($id)
    {
        $penjualan = Stores::where('nota','=',$id)
                ->with('details')
                ->first();
  
        $data = [
            'title' => 'Nota Penjualan',
            'date' => date('m/d/Y'),
            'penjualan' => $penjualan
        ]; 
            
        $pdf = PDF::loadView('pages.transaction.sales.invoice', $data);
        $pdf->setPaper('a5', 'landscape');
        $pdf->setOptions(['margin-top' => 5]);
     
        return $pdf->stream();
    }
}
