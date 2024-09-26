<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $produk = \App\Models\Produk::find($data['produk_id']);
        $data['total_harga'] = $produk->harga * $data['jumlah']; // Hitung total harga
        return $data;
    }
}
