<?php $page="purchase-details";?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        @component('components.pageheader')
			@slot('title') Purchase Details @endslot
			@slot('title_1') View purchase details @endslot
		@endcomponent
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split">
                    <h2>Purchase Detail : {{ $purchase->kode_nota }}</h2>
                    <ul>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/edit.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/pdf.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/excel.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/printer.svg')}}" alt="img"></a>
                        </li>
                    </ul>
                </div>
                <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                        <tbody><tr class="top">
                            <td colspan="6" style="padding: 5px;vertical-align: top;">
                                <table style="width: 100%;line-height: inherit;text-align: left;">
                                    <tbody><tr>
                                        <td style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">Supplier Info</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{$purchase->supplier_name}}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{$purchase->email}}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{$purchase->supplier_phone}}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{$purchase->supplier_address}}</font></font><br>
                                        </td>
                                        <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px">
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">Purchase Order Info</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Order Date </font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Purchase Type </font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Purchase Status</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Payment Status</font></font><br>
                                        </td>
                                        <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px">
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">&nbsp;</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{$purchase->tgl_transaksi}} </font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;"> {{$purchase->tipe_pembelian}}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">  {{$purchase->status == 0 ? "Ordered" : ($purchase->status == 1 ? "Received" : "Canceled")}}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">  {{$purchase->status_bayar}}</font></font><br>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        <tr class="heading " style="background: #F3F2F7;">
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Product Name
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                QTY
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Product Price(Rp.)
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Discount(Rp.)
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Paid Price(Rp.)
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Subtotal(Rp.)
                            </td>
                        </tr>
                        @foreach ($purchaseDetail as $detail)
                            <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                                <td style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
                                    <img src="{{ URL::asset('/storage/uploads/image/product/list/'.$detail->image)}}" alt="img" class="me-2" style="width:40px;height:40px;">
                                    {{$detail->nama_produk}}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{$detail->jumlah}}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{$detail->harga_beli}}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{$detail->diskon_produk}}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{$detail->harga_beli - $detail->diskon_produk}}
                                </td>
                                <td style="padding: 10px;vertical-align: top; ">
                                    {{$detail->subtotal}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody></table>

                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Total Order</h4>
                                        <h5>Rp. {{$purchase->total_harga}}</h5>
                                    </li>
                                    <li>
                                        <h4>Discount	</h4>
                                        <h5>Rp. {{$purchase->diskon}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Shipping</h4>
                                        <h5>Rp. {{$purchase->ongkos_kirim}}</h5>
                                    </li>
                                    <li class="total">
                                        <h4>Grand Total</h4>
                                        <h5>Rp. {{$purchase->bayar}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <a href="{{route('purchase.index')}}" class="btn btn-cancel">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
