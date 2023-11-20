$(document).ready(function () {
    'use strict';

    let table;

    $(function () {
        table = $('#salesTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            "bFilter": true,
			"sDom": 'fBtlpi',
			'pagingType': 'numbers',
			"ordering": true,
			"language": {
				search: ' ',
				sLengthMenu: '_MENU_',
				searchPlaceholder: "Search...",
				info: "_START_ - _END_ of _TOTAL_ items",
			 },
			initComplete: (settings, json)=>{
				$('.dataTables_filter').appendTo('#tableSearch');
				$('.dataTables_filter').appendTo('.search-input');
			},
            ajax: {
                url: base_url+"/transaction/sales/data",
                data: {
                    '_method': "GET",
                    "_token": $('[name=csrf-token]').attr('content'),
                },
                // complete: function (response) {
                //     console.log(JSON.stringify(response));
                // },
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    className: 'sorting_1',
                    searchable: false,
                    sortable: false,
                    render: function (data, type, row) {
                        return '<label class="checkboxs">' +
                                    '<input type="checkbox", value="'+data+'">' +
                                    '<span class="checkmarks"></span>' +
                                '</label>';
                    }
                },
                {data: 'tgl_transaksi'},
                {data: 'nota'},
                {data: 'nama_buyer'},
                {
                    data: 'bayar',
                    render: function (data,type,row) {
                        return "Rp "+ parseFloat(data).toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {data: 'paid_status'},
                {data: 'tgl_kirim'},
                {data: 'order_status'},
                {data: 'device'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

    });

});

function reloadTable()
{
    $('#salesTable').DataTable().ajax.reload();
}

function processOrder(id) {
    Swal.fire({
        title: "Process this order?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8EB359",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, process this order!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url+"/transaction/sales/update-status/"+id, // Ganti dengan URL endpoint AJAX Anda
                type: 'POST',
                data: {
                    '_method': "POST",
                    '_token': $('[name=csrf-token]').attr('content'),
                    'status_order': 1,
                },
                success: function() {
                    Swal.fire({
                        title: "Processed!",
                        text: "Process the order and make delivery immediately.",
                        icon: "success"
                    });
                    window.location.href = base_url+"/transaction/sales";
                },
                error: function(error) {
                // Menangani kesalahan AJAX jika ada
                console.error('Terjadi kesalahan AJAX:', error);
                }
            });
        }
    });
}

function shippingOrder(id, status) {
    if (status == 1) {
        Swal.fire({
            title: "Ready to shipping this order?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8EB359",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url+"/transaction/sales/update-status/"+id, // Ganti dengan URL endpoint AJAX Anda
                    type: 'POST',
                    data: {
                        '_method': "POST",
                        '_token': $('[name=csrf-token]').attr('content'),
                        'status_order': 2,
                    },
                    success: function() {
                        Swal.fire({
                            title: "Shippped!",
                            text: "Order has been delivered.",
                            icon: "success"
                        });
                        reloadTable();
                    },
                    error: function(error) {
                    // Menangani kesalahan AJAX jika ada
                    console.error('Terjadi kesalahan AJAX:', error);
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: "Warning!",
            text: "this order is not ready Or has been processed.",
            icon: "warning"
        });
    }
}


function completeOrder(id, status) {
    if (status == 2) {
        Swal.fire({
            title: "Complete this order?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#8EB359",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url+"/transaction/sales/update-status/"+id, // Ganti dengan URL endpoint AJAX Anda
                    type: 'POST',
                    data: {
                        '_method': "POST",
                        '_token': $('[name=csrf-token]').attr('content'),
                        'status_order': 3,
                    },
                    success: function() {
                        Swal.fire({
                            title: "Completed!",
                            text: "Order has been completed.",
                            icon: "success"
                        });
                        reloadTable();
                    },
                    error: function(error) {
                    // Menangani kesalahan AJAX jika ada
                    console.error('Terjadi kesalahan AJAX:', error);
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: "Warning!",
            text: "this order has not been shipped or processed.",
            icon: "warning"
        });
    }
}

function cancelOrder(id) {

    Swal.fire({
        title: "Cancel this order?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8EB359",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url+"/transaction/sales/update-status/"+id, // Ganti dengan URL endpoint AJAX Anda
                type: 'POST',
                data: {
                    '_method': "POST",
                    '_token': $('[name=csrf-token]').attr('content'),
                    'status_order': 4,
                },
                success: function() {
                    Swal.fire({
                        title: "canceled!",
                        text: "Order has been canceled.",
                        icon: "success"
                    });
                    reloadTable();
                    location.reload(true);
                },
                error: function(error) {
                // Menangani kesalahan AJAX jika ada
                console.error('Terjadi kesalahan AJAX:', error);
                }
            });
        }
    });

}
