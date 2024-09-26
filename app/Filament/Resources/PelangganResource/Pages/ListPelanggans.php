<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use App\Filament\Resources\PelangganResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelanggans extends ListRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
