<?php $page="sales-details";?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        @component('components.pageheader')
			@slot('title') Sale Details @endslot
			@slot('title_1') View sale details @endslot
		@endcomponent
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split">
                    <h2>Sale Detail : {{ $penjualan->nota }}</h2>
                    <ul>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/edit.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="{{route('sales.genInvoice',$penjualan->nota)}}" target="_blank"><img src="{{ URL::asset('/assets/img/icons/pdf.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><img src="{{ URL::asset('/assets/img/icons/excel.svg')}}" alt="img"></a>
                        </li>
                        <li>
                            <a href="{{route('sales.printInvoice',$penjualan->nota)}}" target="_blank"><img src="{{ URL::asset('/assets/img/icons/printer.svg')}}" alt="img"></a>
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
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">Customer Info</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{ $penjualan->buyer_name }}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{ $penjualan->no_telp }}</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> {{ $penjualan->alamat_kirim }}</font></font><br>
                                        </td>
                                        <td style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">

                                        </td>
                                        <td style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">Invoice Info</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Order Date </font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">  Payment Status</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"> Status</font></font><br>
                                        </td>
                                        <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px">
                                            <font style="vertical-align: inherit;margin-bottom:25px;"><font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">&nbsp;</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">{{ $penjualan->tgl_transaksi }} </font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;"> {{ $penjualan->status_bayar }} ({{ $penjualan->tgl_lunas }})</font></font><br>
                                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">  {{ ($penjualan->status_order == 0)? 'Ordered' : (($penjualan->status_order == 1)? 'Processed' : (($penjualan->status_order == 2)? 'Shipped' : (($penjualan->status_order == 3)? 'Completed' : 'Canceled'))) }}</font></font><br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                                Price
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Subtotal
                            </td>
                        </tr>

                        @foreach ($penjualan->details as $item)
                        <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                            <td style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
                                <img src="{{ $item->product->image }}" alt="img" class="me-2" style="width:40px;height:40px;">
                                {{ $item->product->nama_produk }}
                            </td>
                            <td style="padding: 10px;vertical-align: top; ">
                                {{ $item->jumlah }}
                            </td>
                            <td style="padding: 10px;vertical-align: top; ">
                                @currency($item->harga_jual)
                            </td>
                            <td style="padding: 10px;vertical-align: top; ">
                                @currency($item->subtotal)
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Admin Fee</h4>
                                        <h5>@currency(env('ADMIN_FEE'))</h5>
                                    </li>
                                    <li>
                                        <h4>Discount</h4>
                                        <h5>@currency(0)</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 ">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Shipping</h4>
                                        <h5>@currency($penjualan->ongkos_kirim)</h5>
                                    </li>
                                    <li class="total">
                                        <h4>Grand Total</h4>
                                        <h5>@currency($penjualan->bayar)</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if ($penjualan->status_order == 0 && $penjualan->status_order != 3)
                            <a href="javascript:void(0);" class="btn btn-submit me-2" onclick="processOrder({{ $penjualan->id_penjualan }})">Process</a>
                        @elseif ($penjualan->status_order != 4 && $penjualan->status_order != 0 && $penjualan->status_order != 3)
                            <a href="javascript:void(0);" class="btn btn-submit btn-danger me-2" onclick="cancelOrder({{ $penjualan->id_penjualan }})">Cancel Order</a>
                        @endif
                        <a href="{{ route('sales.index') }}" class="btn btn-cancel">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
