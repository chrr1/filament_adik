<?php

namespace App\Filament\Resources;

use App\Exports\ProdukExport;
use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('NamaProduk')
                    ->required()
                    ->label('Nama Produk'),
                TextInput::make('Harga')
                    ->numeric()
                    ->required()
                    ->label('Harga'),
                TextInput::make('Stok')
                    ->numeric()
                    ->required()
                    ->label('Stok'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('NamaProduk')->label('Nama Produk'),
                TextColumn::make('Harga')->label('Harga'),
                TextColumn::make('Stok')->label('Stok'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                Tables\Filters\Filter::make('deleted')
                    ->label('Lihat Data yang Dihapus')
                    ->query(fn ($query) => $query->onlyTrashed()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->action(function () {
                        return Excel::download(new ProdukExport, 'produk.xlsx');
                    }),
                    Tables\Actions\Action::make('exportPDF')
                    ->label('Export PDF')
                    ->action(function () {
                        // Panggil fungsi exportPDF secara langsung
                        return static::exportPDF();
                    }),
            ]);
    }

    public static function exportPDF()
{
    $produks = Produk::all();
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Membuat HTML untuk PDF
    $html = '<h1 style="text-align: center;">Daftar Produk</h1>';
    $html .= '<table border="1" width="100%" style="border-collapse: collapse; text-align: left;">';
    $html .= '<tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal Dibuat</th>
              </tr>';

    $no = 1;
    foreach ($produks as $produk) {
        $html .= '<tr>
                    <td style="text-align: center;">' . $no++ . '</td>
                    <td>' . $produk->NamaProduk . '</td>
                    <td>' . number_format($produk->Harga, 2) . '</td>
                    <td>' . $produk->Stok . '</td>
                    <td>' . $produk->created_at->format('d-m-Y H:i:s') . '</td>
                  </tr>';
    }

    $html .= '</table>';

    // Load HTML ke Dompdf
    $dompdf->loadHtml($html);

    // Set ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'landscape');

    // Render PDF
    $dompdf->render();

    // Menggunakan response()->stream() untuk mengirim PDF
    return response()->stream(
        function () use ($dompdf) {
            echo $dompdf->output();
        },
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Daftar_Produk_' . date('Y-m-d_H-i-s') . '.pdf"',
        ]
    );
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
            'view' => Pages\ViewProduk::route('/{record}'),
            'deleted' => Pages\DeletedProduks::route('/deleted'),
        ];
    }
}
