<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Tables;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource;

class DeletedProduks extends ListRecords
{
    protected static ?string $title = 'Produk Derhapus';
    protected static ?string $breadcrumb = "Deleted";
    protected static string $resource = ProdukResource::class;

    public function getTableQuery(): ?Builder
    {
        return static::$resource::getModel()::onlyTrashed();
    }
    protected function getActions(): array
    {
        return [
            Action::make('Back')->color('success')->icon('heroicon-o-arrow-left')
            ->url(fn()=>ProdukResource::getUrl('index')),
        ];
    }
    // protected function getTableFilters(): array
    // {
    //     return [
    //         SelectFilter::make('Status')
    //             ->options([
    //                 'all' => 'All',
    //                 'trashed' => 'Trashed',
    //                 'active' => 'Active',
    //             ])
    //             ->default('trashed')
    //             ->query(function (Builder $query, $state) {
    //                 if ($state === 'trashed') {
    //                     return $query->onlyTrashed();
    //                 } elseif ($state === 'active') {
    //                     return $query->whereNull('deleted_at');
    //                 } elseif ($state === 'all') {
    //                     return $query;
    //                 }
    //                 return $query;
    //             }),
    //     ];
    // }

    // protected function getTableColumns(): array
    // {
    //     return [
    //         Tables\Columns\TextColumn::make('id')->label('ID'),
    //         Tables\Columns\TextColumn::make('NamaProduk')->label('Nama Produk'),
    //         Tables\Columns\TextColumn::make('Harga')->label('Harga'),
    //         Tables\Columns\TextColumn::make('Stok')->label('Stok'),
    //         Tables\Columns\TextColumn::make('created_by')->label('Dibuat Oleh'),
    //         Tables\Columns\TextColumn::make('updated_by')->label('Diperbarui Oleh'),
    //         Tables\Columns\TextColumn::make('deleted_by')->label('Dihapus Oleh'),
    //         Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dibuat'),
    //         Tables\Columns\TextColumn::make('updated_at')->label('Tanggal Diperbarui'),
    //         Tables\Columns\TextColumn::make('deleted_at')->label('Tanggal Dihapus'),
    //     ];
    // }
}
