
$(document).ready(function () {
    'use strict';

	if ( $.fn.countdown ) {
    	// Deal of the day countdown
		$('.daily-deal-countdown').each(function () {
			var $this = $(this),
				untilDate = $this.data('until'),
				compact = $this.data('compact');

			$this.countdown({
			    until: untilDate, // this is relative date +10h +5m vs..
			    format: 'HMS',
			    padZeroes: true,
			    labels: ['years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'],
			    labels1: ['year', 'month', 'week', 'day', 'hour', 'minutes', 'second']
			});
		});

		// Pause
		// $('.daily-deal-countdown').countdown('pause');


		// Offer countdown
		$('.offer-countdown').each(function () {
			var $this = $(this),
				untilDate = $this.data('until'),
				compact = $this.data('compact');

			$this.countdown({
			    until: untilDate, // this is relative date +10h +5m vs..
			    format: 'DHMS',
			    padZeroes: true,
			    labels: ['years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'],
			    labels1: ['year', 'month', 'week', 'day', 'hour', 'minutes', 'second']
			});
		});

		// Pause
		// $('.offer-countdown').countdown('pause');
	}

    $('#newProductTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })

    $('#trendingTab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })

    refreshCart();

});

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
            url: base_url+"/store/cart",
            type: "POST",
            data: formData,
            success: function(result) {
                refreshCart();
            },
            error: function(error) {
          // Menangani kesalahan AJAX jika ada
          console.error('Terjadi kesalahan AJAX:', error);
        }
        });
    }

    function removeItem(id) {
        $.ajax({
            url: base_url+"/store/cart/"+id,
            type: "DELETE",
            data: {
                "_token": $('[name=csrf-token]').attr('content'),
                "_method": "DELETE",
            },
            success: function(result) {
                refreshCart();
            },
           error: function(error) {
          // Menangani kesalahan AJAX jika ada
          console.error('Terjadi kesalahan AJAX:', error);
        }
        });
    }

function refreshCart() {
    $.ajax({
        url: base_url+"/store/cart", // Ganti dengan URL endpoint AJAX Anda
        type: 'GET',
        data: {
            '_method': "GET",
            '_token': $('[name=csrf-token]').attr('content'),
        },
        success: function(response) {
            renderProducts(response.data);
            $('#cart-count').text(response.totalQty);
            $('#cart-total-price').html(response.total);

        },
        error: function(error) {
          // Menangani kesalahan AJAX jika ada
          console.error('Terjadi kesalahan AJAX:', error);
        }
    });
}

 // Fungsi untuk merender daftar produk
    function renderProducts(products) {
        var productListContainer = $('#cart-product');

        // Kosongkan kontainer sebelum menambahkan produk baru
        productListContainer.empty();

        // Loop melalui array produk dan tambahkan ke daftar
        Object.entries(products).forEach(function([key, product]) {
            var productHtml = `
                <div class="product" id="product-${key}">
                    <div class="product-cart-details">
                        <h4 class="product-title">
                            <a href="#">${product.name}</a>
                        </h4>
                        <span class="cart-product-info">
                            <span class="cart-product-qty">${product.quantity}</span>
                            x Rp. ${product.price}
                        </span>
                    </div><!-- End .product-cart-details -->
                    <figure class="product-image-container">
                        <a href="#" class="product-image">
                            <img src="${product.attributes.image}" alt="product">
                        </a>
                    </figure>
                    <a href="#" class="btn-remove" title="Remove Product" onclick="removeItem(${key})"><i
                            class="icon-close"></i></a>
                </div><!-- End .product -->
            `;

            // Tambahkan HTML produk ke kontainer
            productListContainer.append(productHtml);
        });
    }