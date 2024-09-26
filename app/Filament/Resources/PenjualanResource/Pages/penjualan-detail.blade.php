<!-- resources/views/filament/resources/penjualan-detail.blade.php -->

<div class="p-4">
    <h3 class="text-lg font-semibold">Detail Penjualan</h3>
    
    <div class="mt-4">
        <p><strong>ID Penjualan:</strong> {{ $penjualan->id }}</p>
        <p><strong>Nama Pelanggan:</strong> {{ $penjualan->pelanggan->NamaPelanggan }}</p>
        <p><strong>Nama Produk:</strong> {{ $penjualan->produk->NamaProduk }}</p>
        <p><strong>Jumlah:</strong> {{ $penjualan->quantity }}</p>
        <p><strong>Harga Satuan:</strong> {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</p>
        <p><strong>Total Harga:</strong> {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>
        <p><strong>Tanggal Penjualan:</strong> {{ $penjualan->tanggal_penjualan->format('d-m-Y') }}</p>
    </div>
</div>
