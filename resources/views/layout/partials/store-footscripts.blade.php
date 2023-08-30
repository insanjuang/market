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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqBoLvcExO_q_AvmG6dzjAj4yyyL2Yc_8&libraries=places&callback=initAutocomplete" defer></script>

@if (Route::is(['store']))
    <script src="{{ URL::asset('/assets/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/plugins/countup/jquery.countdown.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/store.js') }}"></script>
@endif

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
        addressField.focus();
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
                // Swal.fire("Updated!", "Your data has been updated." );
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
    window.initAutocomplete = initAutocomplete;
    let token = $('[name=csrf-token]').attr('content');

    function addToCart(id) {
        let qty = $('#qty').val();

        if (typeof $('#qty').val() === 'undefined') {
            qty = 1
        }

        $('#form-data #token').val($('[name=csrf-token]').attr('content'));
        $('#form-data #product-id').val(id);
        $('#form-data #quantity').val(qty);
        var formData = $("#form-data").serialize();
        $.ajax({
            url: "{{ route('store.addToCart') }}",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#cart-count').text('{{ Cart::getTotalQuantity() }}');
                $('#cart-total-price').html('Rp. {{ Cart::getTotal() }}');
                // Swal.fire("Updated!", "Your data has been updated." );
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr);
            }
        });
    }

    function removeItem(id, url) {
        $.ajax({
            url: url,
            type: "DELETE",
            data: {
                "_token": $('[name=csrf-token]').attr('content'),
                "_method": "DELETE",
            },
            success: function(result) {
                $('#cart-count').text('{{ Cart::getTotalQuantity() }}');
                $('#product-' + id).remove();
                $('#cart-total-price').html('Rp. {{ Cart::getTotal() }}');
                // Swal.fire("Updated!", "Your data has been updated." );
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr);
            }
        });
    }
</script>
