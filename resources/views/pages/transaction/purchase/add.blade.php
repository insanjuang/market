<?php $page="addpurchase";?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        @component('components.pageheader')
			@slot('title') Purchase Add @endslot
			@slot('title_1') Add/Update Purchase @endslot
		@endcomponent
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('purchase.store') }}">
                @method('post')
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <select class="form-select select-supplier" id="id_supplier" name="id_supplier" placeholder="Select Supplier" required>
                                <option></option>
                                @foreach($supplier as $spl)
                                    <option value="{{$spl->id_supplier}}">
                                        {{$spl->nama}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Purchase Date </label>
                            <div class="input-groupicon">
                                <input type="text" placeholder="DD-MM-YYYY" class="datetimepicker" name="tgl_transaksi" required>
                                <div class="addonset">
                                    <img src="{{ URL::asset('/assets/img/icons/calendars.svg')}}" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Purchase Type</label>
                            <select class="form-select" id="tipe_pembelian" name="tipe_pembelian" placeholder="Select Type" required>
                                <option></option>
                                <option value="Cash">Cash</option>
                                <option value="Kontra Bon">Kontra Bon</option>
                                <option value="Konsinyasi">Konsinyasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Reference No.</label>
                            <input type="text" class="form-input" name="kode_nota" placeholder="Input Nota (optional)">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Select Product</label>
                            <select class="form-control select-product" id="product" name="product">
                                <option></option>
                            </select>
                            {{-- <div class="input-groupicon">
                                <input type="text" placeholder="Scan/Search Product by code and select...">
                                <div class="addonset">
                                    <img src="{{ URL::asset('/assets/img/icons/scanners.svg')}}" alt="img">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Product Price(Rp)	</th>
                                    <th>Discount(Rp)	</th>
                                    <th class="text-end">Paid Price(Rp)</th>
                                    <th class="text-end">SubTotal(Rp)	</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li>
                                    <h4>Discount</h4>
                                    <input type="number" class="form-control text-end border-0" id="discount" name="diskon_pembelian" min="0">
                                </li>
                                <li>
                                    <h4>Shipping</h4>
                                    <input type="number" class="form-control text-end border-0" id="shipping" name="ongkos_kirim" min="0">
                                </li>
                                <li class="total">
                                    <h4>Grand Total</h4>
                                    <input type="number" class="form-control text-end border-0" id="total" name="total" min="0" required>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Paid Status</label>
                            <select class="form-select select" name="status_bayar" required>
                                <option>Choose Status</option>
                                <option value="Unpaid">UnPaid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('purchase.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">

let baseUrl = "{{ URL::asset('storage/uploads/image/product/list') }}";
console.log(baseUrl);

$(document).ready(function() {
    $('#id_supplier').select2({
        placeholder: 'Select Supplier',
    });

    $('#tipe_pembelian').select2({
        placeholder: 'Select Type',
    });

    $('#id_supplier').on('select2:select', function (e) {
        // console.log($(this).val());
        searchProduct($(this).val());
    });

    $('#product').on('select2:select', function (e) {
        // console.log($(this).val());
        addRow($(this).val());
        $(this).val(null);
    });

    $('#discount').on('change keyup', function() {
        calculateTotal();
    });
    $('#shipping').on('change keyup', function() {
        calculateTotal();
    });
});
function searchProduct(supplier_id) {
    $('#product').select2({
        placeholder: 'Search and Select Product Here',
        ajax: {
            url: '{{ route('product.json') }}',
            dataType: 'json',
            data: { 'id_supplier': supplier_id },
            delay: 100,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        // var $state = $(
                        //     '<span><img src="' + baseUrl + '/' + item.image + '" class="img-responsive" /> ' + item.kode_produk+ " - " + item.nama_produk + '</span>'
                        // );
                        return {
                            // elemet: item.image,
                            text: item.kode_produk+ " - " + item.nama_produk,
                            id: item.id_produk
                        }
                    })
                };
            },
            // templateResult: formatState,
            cache: true
        }
    });
}

function addRow(product_id) {
     $.ajax({
        url: '{{ route('product.json') }}',
        type: "GET",
        data: {
            'id_produk': product_id
        },
        success: function (result) {
            if (result.length > 0) {
                row = result[0]
                rowElement = generateRow(row);
                $('#purchaseTable').append(rowElement);
                calculateRow(row.id_produk);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr);
        }
    });
}

function generateRow(row) {
    let publicImgPath = "{{ URL::asset('/storage/uploads/image') }}";
    console.log(publicImgPath);
    return '<tr>' +
                '<td class="productimgname">' +
                    '<a class="product-img">' +
                        '<img src="'+publicImgPath+'/product/list/'+row.image+'" alt="product">' +
                    '</a>' +
                    '<a href="javascript:void(0);">'+row.nama_produk+'</a>' +
                    '<input type="hidden" class="hidden" name="produk_id[]" value="'+row.id_produk+'">'+
                '</td>' +
                '<td><input type="number" class="form-control qty" width="40%" id="qty-'+row.id_produk+'" name="qty[]" min="1" value="1" onchange="calculateRow('+row.id_produk+')"></td>' +
                '<td class="text-end" id="price-'+row.id_produk+'">'+row.harga_beli+'</td>' +
                '<td><input type="number" class="form-control discount" id="discount-'+row.id_produk+'" name="discount_product[]" min="0" onchange="calculateRow('+row.id_produk+')"></td>' +
                '<td class="text-end paid" id="paid-'+row.id_produk+'">0.00</td>' +
                '<td class="text-end subtotal" id="subtotal-'+row.id_produk+'">0.00</td>' +
                '<td>' +
                    '<a class="delete-set"><img src="{{ URL::asset('/assets/img/icons/delete.svg')}}" alt="svg"></a>' +
                '</td>' +
            '</tr>'
}

function calculateRow(id) {
    let price = $("#price-"+id).html();
    let qty = $("#qty-"+id).val();
    let discount =  $("#discount-"+id).val();
    let paid
    // =  $("#paid-"+id).html();
    let subtotal
    // = $("#subtotal-"+id).html();

    paid = price - discount;
    $("#paid-"+id).html(paid);
    subtotal = qty * paid;
    $("#subtotal-"+id).html(subtotal);
    calculateTotal()
}

function calculateTotal() {
    let discount = $("#discount").val();
    let shipping = $("#shipping").val();

    let sum=0;
    $(".subtotal").each(function(){
        if($(this).html() !== "")
          sum += parseInt($(this).html(), 10);
    });

    sum -= discount
    sum = Number(sum) + Number(shipping);
    $("#total").val(sum);
}

</script>
@endsection
