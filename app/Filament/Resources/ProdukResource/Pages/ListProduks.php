<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProdukResource;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Restore Data')->color('success')->icon('heroicon-o-arrow-up-tray')
            ->url(fn()=>ProdukResource::getUrl('deleted')),
        ];
    }
}
