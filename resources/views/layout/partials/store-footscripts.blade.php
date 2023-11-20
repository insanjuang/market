{{-- FormData Cart --}}
<form id="form-data">
    <input type="hidden" id="token" name="_token" />
    <input type="hidden" name="_method" value="POST" />
    <input type="hidden" id="product-id" name="id_produk" />
    <input type="hidden" id="quantity" name="qty" />
</form>

<!-- Plugins JS File -->
<!-- jQuery -->
<script src="{{ URL::asset('/assets/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ URL::asset('/assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ URL::asset('/assets/plugins/jquery-hoverintent/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/countup/jquery.waypoints.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/superfish/superfish.min.js') }}"></script>
<!-- Owl JS -->
<script src="{{ URL::asset('/assets/plugins/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/nouislider/nouislider.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/magnific-popup/jquery.magnific-popup.js') }}"></script>

<script src="{{ URL::asset('/assets/plugins/bootstrap-input-spinner/bootstrap-input-spinner.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/jquery-elevateZoom/jquery.elevatezoom.js') }}"></script>
<!-- Main JS File -->
<script src="{{ URL::asset('/assets/js/store-main.js') }}"></script>

@if (Route::is(['store.product']))
    <script src="{{ URL::asset('/assets/plugins/wnumb/wNumb.js') }}"></script>
@endif
@if (Route::is(['store.checkout']))
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqBoLvcExO_q_AvmG6dzjAj4yyyL2Yc_8&libraries=places&callback=initAutocomplete" defer></script>
@endif

@if (Route::is(['store']))
    <script src="{{ URL::asset('/assets/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/plugins/countup/jquery.countdown.min.js') }}"></script>
@endif
<script type="text/javascript">
    let base_url = "{{ url('/') }}";
    let baseImgPath = "{{ URL::asset('/assets') }}";
    let token = $('[name=csrf-token]').attr('content');
    // console.log(baseImgPath);
</script>
<script src="{{ URL::asset('/assets/js/custom/pages/store.js') }}"></script>
