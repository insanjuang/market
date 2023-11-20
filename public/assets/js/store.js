
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