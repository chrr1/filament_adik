<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
    <style>
        /* Styling PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Daftar Produk</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $produk)
            <tr>
                <td>{{ $produk['id'] }}</td>
                <td>{{ mb_convert_encoding($produk['NamaProduk'], 'UTF-8', 'auto') }}</td>
                <td>{{ $produk['Harga'] }}</td>
                <td>{{ $produk['Stok'] }}</td>
                <td>{{ \Carbon\Carbon::parse($produk['created_at'])->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
