<?php

namespace App\Filament\Resources;

use Dompdf\Dompdf;
use Dompdf\Options;
use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Resources\Form;
use App\Exports\ProdukExport;
use Filament\Resources\Table;
use Filament\Resources\Resource;
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
        // dd(auth()->user());
        // *check if user is admin
        if(auth()->user()->user_group_id){
            //*if user is admin set filter to
            $filter=Filter::make('created_at')
            ->form([
                // * ditambah form select branch jika admin
                // * pastikan ada field branc_id di tabel
                Select::make('branch_id')->label('Branch')
                ->options([
                    1 => 'Branch 1',
                    2 => 'Branch 2',
                    3 => 'Branch 3',
                ]),
                // * Gunakan Fungsi dibawah untuk menggunakan model branch (jika sudah jadi) jangan lupa model di import
                // Select::make('Branch')
                // ->label('Author')
                // ->options(Branch::all()->pluck('branch_name', 'id'))
                // ->searchable()
                DatePicker::make('created_from'),
                DatePicker::make('created_until'),
            ])
            // * set berapa banyak kolom ditampilkan
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
        }else{
            //*if user is NOT admin set filter to
            $filter=Filter::make('created_at')
            ->form([
                DatePicker::make('created_from'),
                DatePicker::make('created_until'),
            ])
            // * set berapa banyak kolom ditampilkan
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                // Tables\Filters\Filter::make('deleted')
                //     ->label('Lihat Data yang Dihapus')
                //     ->query(fn ($query) => $query->onlyTrashed()),
                // * set filter
                    $filter
                ],FiltersLayout::AboveContent)->filtersFormColumns(1)
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
            'deleted' => Pages\DeletedProduks::route('/deleted'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
            'view' => Pages\ViewProduk::route('/{record}'),
        ];
    }
    // * query for auto select branch
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
