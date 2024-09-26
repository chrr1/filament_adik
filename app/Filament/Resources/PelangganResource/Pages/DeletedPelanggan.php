<?php 
namespace App\Filament\Resources\PelangganResource\Pages;

use App\Filament\Resources\PelangganResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;

class DeletedPelanggan extends ListRecords
{
    protected static string $resource = PelangganResource::class;

    public function getTableQuery(): Builder
    {
        return parent::getTableQuery()->onlyTrashed();
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('deleted')
                ->label('Deleted')
                ->query(fn (Builder $query) => $query->onlyTrashed())
                ->icon('heroicon-o-trash'),
        ];
    }
}
