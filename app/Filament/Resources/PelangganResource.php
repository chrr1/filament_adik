<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pelanggan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\PelangganResource\Pages;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pelanggan';
    

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('NamaPelanggan')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('Alamat')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('NomorTelepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->maxLength(15),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                TextColumn::make('NamaPelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('NomorTelepon')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->label('Dibuat Oleh')
                    ->getStateUsing(function (Pelanggan $record) {
                        return $record->created_by ? $record->created_by->name : '-';
                    }),
                TextColumn::make('updated_by')
                    ->label('Diperbarui Oleh')
                    ->getStateUsing(function (Pelanggan $record) {
                        return $record->updated_by ? $record->updated_by->name : '-';
                    }),
                TextColumn::make('deleted_by')
                    ->label('Dihapus Oleh')
                    ->getStateUsing(function (Pelanggan $record) {
                        return $record->deleted_by ? $record->deleted_by->name : '-';
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime(),
                TextColumn::make('deleted_at')
                    ->label('Tanggal Dihapus')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
