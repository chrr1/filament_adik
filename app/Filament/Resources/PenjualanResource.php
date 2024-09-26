<?php

namespace App\Filament\Resources;

use App\Exports\PenjualanExport;
use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('pelanggan_id')
                    ->label('Pelanggan')
                    ->options(Pelanggan::all()->pluck('NamaPelanggan', 'id'))
                    ->required(),

                Select::make('produk_id')
                    ->label('Produk')
                    ->options(Produk::all()->pluck('NamaProduk', 'id'))
                    ->required(),

                TextInput::make('quantity')
                    ->numeric()
                    ->label('Jumlah')
                    ->required(),

                TextInput::make('harga_satuan')
                    ->numeric()
                    ->label('Harga Satuan')
                    ->required(),

                TextInput::make('total_harga')
                    ->numeric()
                    ->label('Total Harga')
                    ->required(),

                DatePicker::make('tanggal_penjualan')
                    ->label('Tanggal Penjualan')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('pelanggan.NamaPelanggan')->label('Nama Pelanggan'),
                TextColumn::make('produk.NamaProduk')->label('Nama Produk'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('harga_satuan')->label('Harga Satuan')->money('IDR', true),
                TextColumn::make('total_harga')->label('Total Harga')->money('IDR', true),
                TextColumn::make('tanggal_penjualan')->label('Tanggal Penjualan')->date(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->action(function () {
                        return Excel::download(new PenjualanExport, 'penjualan.xlsx');
                    }),
                Tables\Actions\Action::make('exportPDF')
                    ->label('Export PDF')
                    ->action(function () {
                        return static::exportPDF();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->filters([ /* Tambahkan filter jika diperlukan */ ]);
    }

    public static function exportPDF()
    {
        $penjualans = Penjualan::with(['pelanggan', 'produk'])->get();
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Membuat HTML untuk PDF
        $html = '<h1 style="text-align: center;">Daftar Penjualan</h1>';
        $html .= '<table border="1" width="100%" style="border-collapse: collapse; text-align: left;">';
        $html .= '<tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Tanggal Penjualan</th>
                  </tr>';

        $no = 1;
        foreach ($penjualans as $penjualan) {
            $html .= '<tr>
                        <td style="text-align: center;">' . $no++ . '</td>
                        <td>' . $penjualan->pelanggan->NamaPelanggan . '</td>
                        <td>' . $penjualan->produk->NamaProduk . '</td>
                        <td>' . $penjualan->quantity . '</td>
                        <td>' . number_format($penjualan->harga_satuan, 2) . '</td>
                        <td>' . number_format($penjualan->total_harga, 2) . '</td>
                        <td>' . $penjualan->tanggal_penjualan->format('d-m-Y H:i:s') . '</td>
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
                'Content-Disposition' => 'attachment; filename="Daftar_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf"',
            ]
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
            'view' => Pages\ViewPenjualan::route('/{record}'),
        ];
    }

    protected static function afterSave(Penjualan $penjualan): void
    {
        // Mengisi kolom created_by dan updated_by
        $penjualan->created_by = auth()->id(); // Mengisi dengan ID pengguna yang sedang login
        $penjualan->updated_by = auth()->id(); // Mengisi dengan ID pengguna yang sedang login
        $penjualan->saveQuietly(); // Menghindari event save
    }

    protected static function afterUpdate(Penjualan $penjualan): void
    {
        // Mengisi kolom updated_by
        $penjualan->updated_by = auth()->id(); // Mengisi dengan ID pengguna yang sedang login
        $penjualan->saveQuietly(); // Menghindari event save
    }
}
