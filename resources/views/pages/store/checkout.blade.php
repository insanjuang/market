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
                <form action="{{ route('store.postCheckout') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-9">
                            <h2 class="checkout-title">Lengkapi Pesanan</h2><!-- End .checkout-title -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Nama Depan *</label>
                                    <input type="text" class="form-control" name="nama_depan" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label>Nama Belakang *</label>
                                    <input type="text" class="form-control" name="nama_belakang" required>
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
                                    <input type="tel" class="form-control" name="telepon" required>
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
                            <textarea class="form-control" cols="30" rows="4" name="catatan"
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
                                            <td>@currency( $item->quantity * $item->price ) </td>
                                        </tr>
                                        @endforeach
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>@currency( Cart::getSubTotal() )</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr>
                                            <td>Biaya Layanan:</td>
                                            <td>@currency(env('ADMIN_FEE'))</td>
                                        </tr>
                                        <tr>
                                            <td>Ongkos Krim:</td>
                                            <td class="shipping_price">
                                                Rp. 0
                                            </td>
                                            <input type="hidden" class="hidden" id="shipping_price" name="shipping_price" readonly/>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td class="total_amount">
                                                Rp.0
                                            </td>
                                            <input type="hidden" class="hidden" id="total_amount" name="total_amount" readonly/>

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

                                <div id="shipping-time">
                                    <div class="grid-wrapper grid-col-auto">
                                        <label for="radio-card-1" class="radio-card">
                                            <input type="radio" name="shipping_option" id="radio-card-1" value="1" checked />
                                            <div class="card-content-wrapper">
                                                <span class="check-icon"></span>
                                                <div class="card-content">
                                                    <img src="https://image.freepik.com/free-vector/group-friends-giving-high-five_23-2148363170.jpg" alt="" />
                                                    <h4>Kirim Besok</h4>
                                                    <h5>Saya ingin pesanannya datang besok.</h5>
                                                </div>
                                            </div>
                                        </label>
                                        <!-- /.radio-card -->

                                        <label for="radio-card-2" class="radio-card">
                                            <input type="radio" name="shipping_option" id="radio-card-2" value="2" />
                                            <div class="card-content-wrapper">
                                                <span class="check-icon"></span>
                                                <div class="card-content">
                                                    <img src="https://image.freepik.com/free-vector/people-putting-puzzle-pieces-together_52683-28610.jpg" alt="" />
                                                    <h4>Kirim Lusa</h4>
                                                    <h5>Saya ngga buru-buru, kirim lusa aja.</h5>
                                                </div>
                                            </div>
                                        </label>
                                        <!-- /.radio-card -->
                                    </div>
                                    <!-- /.grid-wrapper -->
                                </div>

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

@section('scripts')
<script type="text/javascript">
    let autocomplete;
let addressField;
const center = {lng: 107.6527148, lat: -6.9556164};
const defaultBounds = {
    north: center.lat + 0.1,
    south: center.lat - 0.1,
    east: center.lng + 0.1,
    west: center.lng - 0.1,
};

window.initAutocomplete = initAutocomplete;

function initAutocomplete() {
    addressField = document.querySelector("#maps-address");
    // Create the autocomplete object, restricting the search predictions to
    // addresses in the US and Canada.
    autocomplete = new google.maps.places.Autocomplete(addressField, {
        componentRestrictions: { country: ["id"] },
            bounds: defaultBounds,
            fields: ["address_components", "geometry"],
            types: ["geocode"],
        });
        // addressField.focus();
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();

        // Extract address components (e.g., street, city, state)
        const addressComponents = place.address_components;

        const latitude = place.geometry.location.lat();
        const longitude = place.geometry.location.lng();
        // Use these components to populate your form fields
        // For example:
        document.getElementById('city').value = getAddressComponent(addressComponents, "administrative_area_level_2");
        document.getElementById('state').value = getAddressComponent(addressComponents, "administrative_area_level_1");
        document.getElementById('postal_code').value = getAddressComponent(addressComponents, 'postal_code');
        document.getElementById('longlat').value = latitude+","+longitude;
        // ...

        // Display the formatted address
        getShippingPrice(latitude+","+longitude);
    };

    function getShippingPrice(destination) {
        $.ajax({
            url: "{{ route('store.getShippingPrice') }}",
            type: "POST",
            data: {
                '_method': "POST",
                '_token': $('[name=csrf-token]').attr('content'),
                'destination': destination,
            },
            success: function(result) {
                console.log(result);
                $('.shipping_price').text("Rp. "+result.shipping_price);
                $('#shipping_price').val(result.shipping_price);
                $('.total_amount').text("Rp. "+result.total_price);
                $('#total_amount').val(result.total_price);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr);
            }
        });

    }

    function getAddressComponent(components, type) {
        let lng_name;
        for (let i = 0; i < components.length; i++) {
            const component = components[i];
            const componentType = component.types[0];

            if (componentType == type) {
                lng_name = component.long_name;
                break;
            }
            // Add more conditions for other address components as needed
        }
        return lng_name;

    }
</script>
@endsection
