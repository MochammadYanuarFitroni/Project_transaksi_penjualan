<!DOCTYPE html>
<html>
<head>
    <title>Faktur {{ $faktur->no_faktur }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
            margin: 20px 0;
        }
        .details {
            margin: 20px 0;
            border-collapse: collapse;
            width: 100%;
        }
        .details th, .details td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT. XYZ Indonesia</h1>
        <h3>Faktur {{ $faktur->no_faktur }}</h3>
    </div>
    <hr>
    <p>Customer: {{ $faktur->customer->nama_customer }}</p>
    <p>Jenis Transaksi: {{ $faktur->jenisTransaksi->nama_tjen }}</p>
    <p>Tanggal: {{ $faktur->tgl_faktur }}</p>

    <table class="details">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Diskon</th>
                <th>Bruto</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faktur->detailPenjualan as $item)
            <tr>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>Rp {{ round($item->harga) }}</td>
                <td>{{ round($item->qty)  }}</td>
                <td>{{ round($item->diskon) }}%</td>
                <td>Rp {{ round($item->bruto) }}</td>
                <td>Rp {{ round($item->jumlah) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total Bruto: Rp {{ round($faktur->total_bruto) }}</p>
    <p>Total Diskon: Rp {{ round($faktur->total_diskon) }}</p>
    <p>Total Jumlah: Rp {{ round($faktur->total_jumlah) }}</p>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda!</p>
    </div>
</body>
</html>
