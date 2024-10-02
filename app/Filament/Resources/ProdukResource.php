<?php

namespace App\Filament\Resources;

use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\Produk;
use Filament\Forms;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

use Filament\Tables;
use Illuminate\Support\Facades\Route;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;

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
        if (auth()->user()->user_group_id) {
            $filter = Filter::make('created_at')
                ->form([
                    Select::make('branch_id')->label('Branch')
                        ->options([
                            1 => 'Branch 1',
                            2 => 'Branch 2',
                            3 => 'Branch 3',
                        ]),
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->columns(3)
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['branch_id'],
                            fn (Builder $query, $id): Builder => $query->where('branch_id', $id),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                });
        } else {
            $filter = Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->columns(2)
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                });
        }

        return $table
            ->columns([
                TextColumn::make('NamaProduk')->label('Nama Produk'),
                TextColumn::make('Harga')->label('Harga'),
                TextColumn::make('Stok')->label('Stok'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\EditAction::make()->visible(fn ($record) => !$record->trashed()),
                Tables\Actions\DeleteAction::make()->visible(fn ($record) => !$record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->color('warning')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->trashed()),
                Tables\Actions\Action::make('forceDelete')
                    ->label('Hapus Permanen')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->forceDelete();
                    })
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                $filter
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns(1)
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->action(function (array $data) {
                        $query = Produk::query();

                        if (!empty($data['created_from'])) {
                            $query->whereDate('created_at', '>=', $data['created_from']);
                        }

                        if (!empty($data['created_until'])) {
                            $query->whereDate('created_at', '<=', $data['created_until']);
                        }

                        $produks = $query->get();
                        return Excel::download(new ProdukExport($produks), 'produk_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
                    }),
                    Tables\Actions\Action::make('exportPDF')
                    ->label('Export PDF')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->action(function (array $data) {
                        $query = Produk::query();
                
                        if (!empty($data['created_from'])) {
                            $query->whereDate('created_at', '>=', $data['created_from']);
                        }
                
                        if (!empty($data['created_until'])) {
                            $query->whereDate('created_at', '<=', $data['created_until']);
                        }
                
                        $produks = $query->get();
                
                        // Buat instance TCPDF
                        // $pdf = new TCPDF(); // Pastikan ini menggunakan namespace yang benar
                        // // $pdf->SetCreator(PDF_CREATOR);
                        // $pdf->SetAuthor(auth()->user()->name);
                        // $pdf->SetTitle('Daftar Produk');
                        // $pdf->SetHeaderData('', '', 'Daftar Produk', 'Dicetak oleh: ' . auth()->user()->name);
                        // $pdf->setHeaderFont(['helvetica', '', 10]);
                        // $pdf->setFooterFont(['helvetica', '', 8]);
                        // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                        // $pdf->SetMargins(10, 10, 10);
                        // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                        // $pdf->AddPage('L', 'A4');
                
                        // // Tambahkan konten ke PDF
                        // $html = '<h1 style="text-align: center;">Daftar Produk</h1>';
                        // $html .= '<table border="1" width="100%" style="border-collapse: collapse; text-align: left;">';
                        // $html .= '<tr>
                        //             <th>No</th>
                        //             <th>Nama Produk</th>
                        //             <th>Harga</th>
                        //             <th>Stok</th>
                        //             <th>Tanggal Dibuat</th>
                        //           </tr>';
                
                        // $no = 1;
                        // foreach ($produks as $produk) {
                        //     $html .= '<tr>
                        //                 <td style="text-align: center;">' . $no++ . '</td>
                        //                 <td>' . $produk->NamaProduk . '</td>
                        //                 <td>' . number_format($produk->Harga, 2) . '</td>
                        //                 <td>' . $produk->Stok . '</td>
                        //                 <td>' . $produk->created_at->format('d-m-Y H:i:s') . '</td>
                        //               </tr>';
                        // }
                
                        // $html .= '</table>';
                        // $pdf->writeHTML($html, true, false, true, false, '');
                
                        // // Mengirim PDF sebagai response
                        // return response()->stream(
                        //     function () use ($pdf) {
                        //         echo $pdf->output('Daftar_Produk_' . date('Y-m-d_H-i-s') . '.pdf', 'D');
                        //     },
                        //     200,
                        //     [
                        //         'Content-Type' => 'application/pdf',
                        //         'Content-Disposition' => 'attachment; filename="Daftar_Produk_' . date('Y-m-d_H-i-s') . '.pdf"',
                        //     ]
                        // );
                        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                        $pdf::setHeaderCallback(function ($pdf) {
                            $pdf->SetFont('helvetica', '', 8);
                            $header = "
                            <div></div>
                                <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                                    <tr>
                                        <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('assets/logo.png') . "\" width=\"120\"></td>
                                        <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                                        <td width=\"12%\"><div style=\"text-align: left;\">" . $pdf->getAliasNumPage() . " / " . $pdf->getAliasNbPages() . "</div></td>
                                    </tr>
                                    <tr>
                                        <td width=\"10%\"><div style=\"text-align: left;\">Dicetak</div></td>
                                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                                        <td width=\"12%\"><div style=\"text-align: left;\">" . ucfirst(auth()->user()->name) . "</div></td>
                                    </tr>
                                    <tr>
                                        <td width=\"10%\"><div style=\"text-align: left;\">Tgl. Cetak</div></td>
                                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                                        <td width=\"12%\"><div style=\"text-align: left;\">" . date('d-m-Y H:i') . "</div></td>
                                    </tr>
                                </table>
                                <hr>
                            ";
                
                            $pdf->writeHTML($header, true, false, false, false, '');
                        });
                        $pdf::SetPrintFooter(false);
                
                        $pdf::SetMargins(5, 20, 5, true); // put space of 10 on top
                
                        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);
                
                        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                            require_once(dirname(__FILE__) . '/lang/eng.php');
                            $pdf::setLanguageArray($l);
                        }
                
                        $pdf::SetFont('helvetica', 'B', 20);
                
                        $pdf::AddPage();
                
                        $pdf::SetFont('helvetica', '', 8);
                
                        $tbl = "
                        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
                            <tr>
                                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">Daftar Produk</div></td>
                            </tr>
                        </table>
                        ";
                        $pdf::writeHTML($tbl, true, false, false, false, '');
                
                        $no = 1;
                        $tblStock1 = "
                        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
                            <tr>
                                <td width=\"5%\"><div style=\"text-align: center; font-weight: bold\">No</div></td>
                                <td width=\"20%\"><div style=\"text-align: center; font-weight: bold\">Kategori</div></td>
                                <td width=\"26%\"><div style=\"text-align: center; font-weight: bold\">Kode Barang</div></td>
                                <td width=\"26%\"><div style=\"text-align: center; font-weight: bold\">Nama Barang</div></td>
                                <td width=\"8%\"><div style=\"text-align: center; font-weight: bold\">Satuan</div></td>
                                <td width=\"15%\"><div style=\"text-align: center; font-weight: bold\">Barcode</div></td>
                            </tr>
                
                             ";
                
                        $no = 1;
                        $tblStock2 = " ";
                
                        foreach ($produks as $val) {
                          
                                $tblStock2 .= "
                                <tr>
                                    <td><div style=\"text-align: center;\">" . $no++ . ".</div></td>
                                    <td>kategori</td>
                                    <td>code</td>
                                    <td>{$val->NamaProduk}</td>
                                    <td>unit</td>
                                    <td>barcode</td>
                                </tr>";
                            
                        }
                
                        $tblStock3 = "</table>";
                
                        $pdf::writeHTML($tblStock1 . $tblStock2 . $tblStock3, true, false, false, false, '');
                
                        $filename = 'Daftar_barang' . date('Y-m-d H:i:s') . '.pdf';
                        $pdf::Output($filename, 'I');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'deleted' => Pages\DeletedProduks::route('/deleted'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
            'view' => Pages\ViewProduk::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->user_group_id == 1) {
            return $query;
        } else {
            return $query->where('branch_id', auth()->user()->branch_id);
        }
    }
}


