<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $title }}</title>

    <!-- Add your custom CSS styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0 auto;
            font-color: #8EB359;
        }

        .header h4 {
            margin: 0 auto;
        }

        .info {
            margin-bottom: 10px;
            margin-top:10px
        }

        .info p {
        	margin: 0 auto;
            vertical-align: inherit;
            font-size: 14px;
            font-weight: 400;
        }

        .invoice {
            border-collapse: collapse;
            width: 100%;
        }

        .invoice th, .invoice td {
            border: 1px solid #ddd;
            padding: 5px;
            /* text-align: left; */
        }

        .price {
            text-align:right;
        }

        tbody {
        	vertical-align: inherit;
            font-size: 14px;
            font-weight: 400;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        /* Add more styles as needed */
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2 style="color: #8EB359;">{{ $title }}</h2>
        <h4>Via : {{ $penjualan->device }} | Nomor : {{$penjualan->nota}}</h4>
        <!-- Add your business information or logo here -->
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150px">
                    <p><strong>Nama Pemesan:</strong> </p>
                </td>
                <td width="150px">
                    <p>{{ $penjualan->nama_buyer}}</p>
                </td>
                <td width="auto">
                    <p><strong>Alamat:</strong> </p>
                </td>
            </tr>
            <tr>
                <td width="180px">
                    <p><strong>Nomor Telp:</strong></p>
                </td>
                <td>
                    <p>{{ $penjualan->no_telp}}</p>
                </td>
                <td rowspan="3">
                    <p>{{ $penjualan->alamat_kirim}}</p>
                </td>
            </tr>
            <tr>
                <td width="180px">
                    <p><strong>Tanggal Pesanan:</strong> </p>
                </td>
                <td>
                    <p>{{ $penjualan->tgl_transaksi }}</p>
                </td>
            </tr>
            <tr>
                <td width="180px">
                    <p><strong>Status Bayar:</strong></p>
                </td>
                <td>
                    <p>{{ $penjualan->status_bayar }} {{($penjualan->status_bayar == "LUNAS")? "(".$penjualan->tgl_lunas.")": ""}}</p>
                </td>
            </tr>
        </table>

    </div>

    <table class="invoice">
        <thead style="bgcolor:#8EB359;">
            <tr>
                <th>Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example data, replace with actual sales data -->
            @foreach($penjualan->details as $item)
                <tr>
                    <td>{{$item->product->nama_produk}}</td>
                    <td class="price">@currency($item->harga_jual)</td>
                    <td style="text-align:center;">{{$item->jumlah}}</td>
                    <td class="price">@currency($item->subtotal)</td>
                </tr>
            @endforeach
            <!-- Add more rows as needed -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Admin Fee:</td>
                <td class="price">@currency(env('ADMIN_FEE'))</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Ongkos Kirim:</td>
                <td class="price">@currency($penjualan->ongkos_kirim)</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Total:</td>
                <td class="price">@currency($penjualan->bayar)</td>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
