<?php $page="pos";?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper ms-0">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 col-sm-12 tabs_wrapper" >
                <div class="page-header ">
                    <div class="page-title">
                        <h4>CASHIER</h4>
                        <h6>Manage your order</h6>
                    </div>
                </div>
                <ul class=" tabs owl-carousel owl-theme owl-product  border-0 " >
                    @foreach ($categories as $key => $cat)
                    <li class="{{($key==0)?'active':''}}" id="{{ $cat->kode }}">
                        <div class="product-details " >
                            <img src="{{($cat->image == "") ? URL::asset('assets/img/product/product1.jpg') : URL::asset('/storage/uploads/image/product/category/'.$cat->image)}}" alt="img">
                            <h6>{{ $cat->nama_kategori }}</h6>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <div class="tabs_container" >
                    @foreach ($categoryWithProduct as $key => $item)

                    <div  class="tab_content {{($key==0)?'active':''}}" data-tab="{{ $item->kode }}">
                        <div class="row ">
                            @foreach ($item->allProduct as $product)
                            <div class="col-lg-3 col-sm-6 d-flex ">
                                <div class="productset flex-fill " id="{{ $product->id_produk }}">
                                    <div class="productsetimg">
                                        <img src="{{(count($product->images) > 0) ? URL::asset('storage/'.$product->images[0]->url) : URL::asset('assets/img/product/product1.jpg')}}" alt="img">
                                        <h6>Qty: 1.00</h6>
                                        <div class="check-product">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="productsetcontent">
                                        <h5>{{ $item->nama_kategori }}</h5>
                                        <h4>{{ $product->nama_produk }}</h4>
                                        <h6>@currency($product->harga_jual)</h6>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 ">
                <div class="order-list">
                    <div class="orderid">
                        <h4>Order List</h4>
                        <h5>Transaction date : {{ date('Y-m-d') }}</h5>
                    </div>
                    <div class="actionproducts">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="deletebg confirm-text"><img src="{{ URL::asset('/assets/img/icons/delete-2.svg')}}" alt="img"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="dropset" >
                                    <img src="{{ URL::asset('/assets/img/icons/ellipise1.svg')}}" alt="img">
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-popper-placement="bottom-end">
                                    <li>
                                        <a href="#" class="dropdown-item">Action</a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item">Another Action</a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item">Something Elses</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card card-order">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal" data-bs-target="#create"><i class="fa fa-plus me-2"></i>Add Customer</a>
                            </div>
                            <div class="col-lg-12">
                                <div class="select-split ">
                                    <div class="select-group w-100">
                                        <select class="select">
                                            <option>Walk-in Customer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="text-end">
                                    <a class="btn btn-scanner-set"><img src="{{ URL::asset('/assets/img/icons/scanner1.svg')}}" alt="img" class="me-2">Scan bardcode</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="split-card">
                    </div>
                    <div class="card-body pt-0">
                        <div class="totalitem">
                            <h4 id="totalitem-header">Total items : 0</h4>
                            <a href="javascript:void(0);" onclick="clearCart()">Clear all</a>
                        </div>
                        <div class="product-table" id="product-list">
                            {{-- @foreach (Cart::Session(auth()->user()->id)->getContent() as $cart)
                            <ul class="product-lists">
                                <li>
                                    <div class="productimg">
                                        <div class="productimgs">
                                            <img src="{{$cart->attributes->image}}" alt="img">
                                        </div>
                                        <div class="productcontet">
                                            <h4>{{$cart->name}}
                                            <a href="javascript:void(0);" class="ms-2" data-bs-toggle="modal" data-bs-target="#edit"><img src="{{ URL::asset('/assets/img/icons/edit-5.svg')}}" alt="img"></a>
                                            </h4>
                                            <div class="productlinkset">
                                                <h5>@currency($cart->price)</h5>
                                            </div>
                                            <div class="increment-decrement">
                                                <div class="input-groups">
                                                    <input type="button" value="-"  class="button-minus dec button qty-btn" id="decBtn-{{ $cart->id }}">
                                                    <input type="text" name="child" value="1" class="quantity-field" id="qty-{{ $cart->id }}">
                                                    <input type="button" value="+"  class="button-plus inc button qty-btn" id="incBtn-{{ $cart->id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>@currency($cart->price * $cart->quantity)</li>
                                <li><a class="confirm-text" href="javascript:void(0);" onclick="removeItem({{ $cart->id }})"><img src="{{ URL::asset('/assets/img/icons/delete-2.svg')}}" alt="img"></a></li>
                            </ul>
                            @endforeach --}}
                        </div>
                    </div>
                    <div class="split-card">
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="setvalue">
                            <ul>
                                <li>
                                    <h5>Subtotal </h5>
                                    <h6 id="subtotal">Rp 0</h6>
                                </li>
                                <li>
                                    <h5>Bea Admin </h5>
                                    <h6 id="admin-fee">@currency(env('ADMIN_FEE'))</h6>
                                </li>
                                <li class="total-value">
                                    <h5>Total  </h5>
                                    <h6 id="total">@currency(env('ADMIN_FEE'))</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-totallabel">
                            <h5>Checkout</h5>
                            <h6 id="total-checkout">@currency(env('ADMIN_FEE'))</h6>
                        </div>
                        <div class="setvaluecash">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" class="paymentmethod" onclick="placeOrder()">
                                        <img src="{{ URL::asset('/assets/img/icons/cash.svg')}}" alt="img" class="me-2">
                                        Cash
                                    </a>
                                </li>
                                 <li>
                                    <a href="javascript:void(0);" class="paymentmethod">
                                        <img src="{{ URL::asset('/assets/img/icons/debitcard.svg')}}" alt="img" class="me-2">
                                        Transfer
                                    </a>
                                </li>
                                {{--
                                <li>
                                    <a href="javascript:void(0);" class="paymentmethod">
                                        <img src="{{ URL::asset('/assets/img/icons/scan.svg')}}" alt="img" class="me-2">
                                        Scan
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
