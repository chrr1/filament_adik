<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(), 
            
        ];
    }
}
