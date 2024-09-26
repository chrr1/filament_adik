<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\EditAction;
use Filament\Forms\Components\Placeholder;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getFormSchema(): array
    {
        return [
            Placeholder::make('NamaProduk')
                ->label('Nama Produk')
                ->content($this->record->NamaProduk),

            Placeholder::make('Harga')
                ->label('Harga')
                ->content($this->record->Harga),

            Placeholder::make('Stok')
                ->label('Stok')
                ->content($this->record->Stok),

            Placeholder::make('created_at')
                ->label('Tanggal Dibuat')
                ->content($this->record->created_at?->format('d-m-Y H:i:s')),

            Placeholder::make('updated_at')
                ->label('Tanggal Diperbarui')
                ->content($this->record->updated_at?->format('d-m-Y H:i:s')),

            Placeholder::make('deleted_at')
                ->label('Tanggal Dihapus')
                ->content($this->record->deleted_at?->format('d-m-Y H:i:s')),
        ];
    }

    protected function getActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
