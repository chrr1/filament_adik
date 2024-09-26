<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;

class DeletedProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    public function getTableQuery(): ?Builder
    {
        return static::$resource::getModel()::onlyTrashed();
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    'all' => 'All',
                    'trashed' => 'Trashed',
                    'active' => 'Active',
                ])
                ->default('trashed')
                ->query(function (Builder $query, $state) {
                    if ($state === 'trashed') {
                        return $query->onlyTrashed();
                    } elseif ($state === 'active') {
                        return $query->whereNull('deleted_at');
                    } elseif ($state === 'all') {
                        return $query;
                    }
                    return $query;
                }),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('ID'),
            Tables\Columns\TextColumn::make('NamaProduk')->label('Nama Produk'),
            Tables\Columns\TextColumn::make('Harga')->label('Harga'),
            Tables\Columns\TextColumn::make('Stok')->label('Stok'),
            Tables\Columns\TextColumn::make('created_by')->label('Dibuat Oleh'),
            Tables\Columns\TextColumn::make('updated_by')->label('Diperbarui Oleh'),
            Tables\Columns\TextColumn::make('deleted_by')->label('Dihapus Oleh'),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat'),
            Tables\Columns\TextColumn::make('updated_at')->label('Tanggal Diperbarui'),
            Tables\Columns\TextColumn::make('deleted_at')->label('Tanggal Dihapus'),
        ];
    }
}
