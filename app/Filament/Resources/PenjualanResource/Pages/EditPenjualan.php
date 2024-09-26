<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(), // Untuk menghapus penjualan
        ];
    }
}
