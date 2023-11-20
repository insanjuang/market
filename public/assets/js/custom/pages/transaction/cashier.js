$(document).ready(function () {
    'use strict';

    refreshCart();

    $('.productset').on('click', function() {
        // Memanggil AJAX
        if ($(this).hasClass('active')) {
            removeItem($(this).attr('id'))
        } else {
            console.log('active');
            $.ajax({
                url: base_url+"/transaction/cart", // Ganti dengan URL endpoint AJAX Anda
                type: 'POST',
                data: {
                    '_method': "POST",
                    '_token': $('[name=csrf-token]').attr('content'),
                    'id_produk': $(this).attr('id'),
                },
                success: function(data) {
                    refreshCart();
                },
                error: function(error) {
                // Menangani kesalahan AJAX jika ada
                console.error('Terjadi kesalahan AJAX:', error);
                }
            });

        }
    });

});

function clearCart() {
    Swal.fire({
        title: "Remove all item?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8EB359",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove all item!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url+"/transaction/cart/clear", // Ganti dengan URL endpoint AJAX Anda
                type: 'GET',
                data: {
                    '_method': "GET",
                    '_token': $('[name=csrf-token]').attr('content'),
                },
                success: function() {
                    refreshCart();
                },
                error: function(error) {
                // Menangani kesalahan AJAX jika ada
                console.error('Terjadi kesalahan AJAX:', error);
                }
            });
        }
    });
}

function refreshCart() {
    $.ajax({
        url: base_url+"/transaction/cart", // Ganti dengan URL endpoint AJAX Anda
        type: 'GET',
        data: {
            '_method': "GET",
            '_token': $('[name=csrf-token]').attr('content'),
        },
        success: function(response) {
            renderProducts(response.data);
            $("#totalitem-header").text("Total items : "+response.totalItem);
            $("#subtotal").text(response.subTotal);
            $("#admin-fee").text(response.tax);
            $("#total").text(response.total);
            $("#total-checkout").text(response.total);

        },
        error: function(error) {
          // Menangani kesalahan AJAX jika ada
          console.error('Terjadi kesalahan AJAX:', error);
        }
    });
}

function updateQtyVal(id, num) {
    var qty = parseInt($("#qty-"+id).val());
    qty += parseInt(num);
    $("#qty-"+id).val(qty);
    updateQty(id, num);
}

function updateQty(id, num) {
    var formData = {
        _method: "PATCH",
        _token: $('[name=csrf-token]').attr('content'),
        id_produk:  id,
        qty: num
    }

    $.ajax({
        url: base_url+"/transaction/cart/"+id, // Ganti dengan URL endpoint AJAX Anda
        type: 'PATCH',
        data: formData,
        success: function() {
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
        url: base_url+"/transaction/cart/"+id, // Ganti dengan URL endpoint AJAX Anda
        type: 'DELETE',
        data: {
            '_method': "DELETE",
            '_token': $('[name=csrf-token]').attr('content'),
            'id_produk': id,
        },
        success: function(data) {
            refreshCart();
        },
        error: function(error) {
          // Menangani kesalahan AJAX jika ada
          console.error('Terjadi kesalahan AJAX:', error);
        }
    });
}

 // Fungsi untuk merender daftar produk
    function renderProducts(products) {
        var productListContainer = $('#product-list');

        // Kosongkan kontainer sebelum menambahkan produk baru
        productListContainer.empty();

        // Loop melalui array produk dan tambahkan ke daftar
        Object.entries(products).forEach(function([key, product]) {
            var productHtml = `
                <ul class="product-lists">
                    <li>
                        <div class="productimg">
                            <div class="productimgs">
                                <img src="${product.attributes.image}" alt="img">
                            </div>
                            <div class="productcontet">
                                <h4>${product.name}
                                <a href="javascript:void(0);" class="ms-2" data-bs-toggle="modal" data-bs-target="#edit"><img src="${baseImgPath}/img/icons/edit-5.svg" alt="img"></a>
                                </h4>
                                <div class="productlinkset">
                                    <h5>${product.price}</h5>
                                </div>
                                <div class="increment-decrement">
                                    <div class="input-groups">
                                        <input type="button" value="-"  class="button-minus dec button" onclick="updateQtyVal(${key},-1)">
                                        <input type="text" name="child" value="${product.quantity}" class="quantity-field" id="qty-${key}">
                                        <input type="button" value="+"  class="button-plus inc button" onclick="updateQtyVal(${key},1)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>${product.price * product.quantity}</li>
                    <li><a class="confirm-text" href="javascript:void(0);" onclick="removeItem(${key})"><img src="${baseImgPath}/img/icons/delete-2.svg" alt="img"></a></li>
                </ul>
            `;

            // Tambahkan HTML produk ke kontainer
            productListContainer.append(productHtml);
        });
    }

function placeOrder() {
    Swal.fire({
        title: "Place this Order?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8EB359",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, order it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url+"/transaction/cashier", // Ganti dengan URL endpoint AJAX Anda
                type: 'POST',
                data: {
                    '_method': "POST",
                    '_token': $('[name=csrf-token]').attr('content'),
                },
                success: function(data) {
                    Swal.fire({
                        title: "Ordered!",
                        text: "Your order has been saved.",
                        icon: "success"
                    });
                    refreshCart();
                },
                error: function(error) {
                    // Menangani kesalahan AJAX jika ada
                    console.error('Terjadi kesalahan AJAX:', error);
                }
            });
        }
    });
}
