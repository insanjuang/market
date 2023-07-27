<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Models\Transaction\Purchase;
use App\Models\Transaction\Payment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Transaction\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Alert;

class PaymentController extends Controller
{

    public function list($tipe, $id)
    {

        $payment = Payment::Select('pelunasan.*','supplier.nama as supplier_name')
                    ->leftJoin('supplier','supplier.id_supplier','=','pembelian.id_supplier')
                    ->orderBy('pembelian.tgl_transaksi', 'desc')->get();

        // return view('pages.transaction.purchase.index');
        // return response()->json($purchase);
        // ddd($purchase);
        return datatables()
            ->of($purchase)
            ->addIndexColumn()

            ->addColumn('purchase_type', function ($purchase){
                $type = "";
                switch ($purchase->tipe_pembelian) {
                    case 1:
                        $type = "Cash";
                        break;
                    case 2:
                        $type = "Kontra Bon";
                        break;
                    case 4:
                        $type = "Konsinyasi";
                        break;
                    default:
                        $type = "Cash";
                        break;
                }
                return $type;
            })
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
                    default:
                        $badges = '<span class="badges bg-lightyellow">Ordered</span>';
                        break;
                }
                return $badges;
            })
            ->addColumn('paid_status', function ($purchase){
                $badges = "";
                switch ($purchase->status_bayar) {
                    case 0:
                        $badges = '<span class="badges bg-lightred">UnPaid</span>';
                        break;
                    case 1:
                        $badges = '<span class="badges bg-lightyellow">Partial</span>';
                        break;
                    case 2:
                        $badges = '<span class="badges bg-lightgreen">Paid</span>';
                        break;
                    default:
                        $badges = '<span class="badges bg-lightred">UnPaid</span>';
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
                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showpayment" onclick=""><img src="'. asset('/assets/img/icons/dollar-square.svg').'" class="me-2" alt="img">Show Payments</a>
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
            ->rawColumns(['purchase_type','order_status','paid_status','action'])
            ->make(true);
    }
}
