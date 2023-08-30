@extends('layout.storelayout')
@section('content')
    <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        <div class="container">
            <h1 class="page-title">Lanjutkan Pesanan<span>Checkout</span></h1>
        </div><!-- End .container -->
    </div><!-- End .page-header -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol> --}}
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="checkout">
            <div class="container">
                <form action="{{ route('store.postCheckout') }}">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-9">
                            <h2 class="checkout-title">Lengkapi Pesanan</h2><!-- End .checkout-title -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Nama Depan *</label>
                                    <input type="text" class="form-control" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Nama Belakang *</label>
                                    <input type="text" class="form-control" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->
{{--
                            <label>Country *</label>
                            <input type="text" class="form-control" required> --}}

                            <label>Street address *</label>
                                <input type="text" class="form-control" id="maps-address" name="address" placeholder="Nama Jalan, Nomor Rumah ..." autocomplete="shipping address-line1" required>
                                <input type="hidden" class="hidden" id="longlat" name="longlat">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Kota / Kab *</label>
                                    <input type="text" class="form-control" id="city" name="city" autocomplete="shipping address-level2" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Provinsi *</label>
                                    <input type="text" class="form-control" id="state" name="state" autocomplete="shipping address-level1" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Kode Pos *</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" autocomplete="shipping postal-code" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Telepon / HP *</label>
                                    <input type="tel" class="form-control" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            {{-- <label>Alamat *</label>
                            <input type="email" class="form-control" required> --}}

                            {{-- <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkout-create-acc">
                                <label class="custom-control-label" for="checkout-create-acc">Create an account?</label>
                            </div><!-- End .custom-checkbox --> --}}

                            {{-- <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkout-diff-address">
                                <label class="custom-control-label" for="checkout-diff-address">Ship to a different
                                    address?</label>
                            </div><!-- End .custom-checkbox --> --}}

                            <label>Beri Catatan (opsional)</label>
                            <textarea class="form-control" cols="30" rows="4"
                                placeholder="Catatan atau keterangan khusus pesanan."></textarea>
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-3">
                            <div class="summary">
                                <h3 class="summary-title">Pesanan Kamu</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach (Cart::getContent() as $item)
                                        <tr>
                                            <td><a href="#">{{ $item->name }}</a></td>
                                            <td>Rp. {{ $item->quantity * $item->price }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>Rp. {{ Cart::getSubTotal() }}</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr>
                                            <td>Biaya Layanan:</td>
                                            <td>Rp. 1000</td>
                                        </tr>
                                        <tr>
                                            <td>Ongkos Krim:</td>
                                            <td>0</td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>Rp. 160.00</td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->

                                <div class="accordion-summary" id="accordion-payment">
                                    <div class="card">
                                        <div class="card-header" id="heading-1">
                                            <h2 class="card-title">
                                                <a role="button" data-toggle="collapse" href="#collapse-1"
                                                    aria-expanded="true" aria-controls="collapse-1">
                                                    Bayar di Tempat (COD)
                                                </a>
                                            </h2>
                                        </div><!-- End .card-header -->
                                        <div id="collapse-1" class="collapse show" aria-labelledby="heading-1"
                                            data-parent="#accordion-payment">
                                            <div class="card-body">
                                                Saat ini kami hanya menerima pembayaran COD. Anda dapat menitipkan pembayaran melalui kurir
                                                kami ketika barang sudah sampai tujuan. Pastikan barang sampai sesuai dengan pesanan Anda.
                                                Jika anda menyetujui dengan pembayaran ini, lanjutkan untuk memesan. Admin kami akan mengkonfirmasi pesanan Anda.
                                            </div><!-- End .card-body -->
                                        </div><!-- End .collapse -->
                                    </div><!-- End .card -->
                                </div><!-- End .accordion -->

                                <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                    <span class="btn-text">Lanjutkan Pesanan</span>
                                    <span class="btn-hover-text">Proses Sekarang</span>
                                </button>
                            </div><!-- End .summary -->
                        </aside><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                </form>
            </div><!-- End .container -->
        </div><!-- End .checkout -->
    </div><!-- End .page-content -->
@endsection

