<?php 
namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nama'),
                TextInput::make('full_name')
                    ->label('Nama Lengkap'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                TextInput::make('branch_id') 
                ->label('Branch ID') 
                ->required() 
                ->numeric() 
                ->minValue(1), 

                // Pengaturan password berdasarkan kondisi "create" atau "edit"
                Forms\Components\Section::make('Pengaturan Password')
                    ->schema(function ($record) {
                        if (!$record) {  // Kondisi saat create user
                            return [
                                TextInput::make('password')
                                    ->password()
                                    ->label('Password')
                                    ->required()
                                    ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                            ];
                        }

                        // Kondisi saat edit user
                        return [
                            TextInput::make('current_password')
                                ->password()
                                ->label('Password Lama')
                                ->required(),
                            TextInput::make('new_password')
                                ->password()
                                ->label('Password Baru')
                                ->required(fn ($get) => !empty($get('current_password')))
                                ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                            TextInput::make('confirm_new_password')
                                ->password()
                                ->label('Konfirmasi Password Baru')
                                ->required(fn ($get) => !empty($get('new_password')))
                                ->same('new_password'),
                        ];
                    })
                    ->afterStateUpdated(function (array $state, callable $set) {
                        if (isset($state['current_password']) && !empty($state['current_password'])) {
                            if (!Hash::check($state['current_password'], auth()->user()->password)) {
                                Notification::make()
                                    ->title('Gagal')
                                    ->body('Password lama tidak sesuai.')
                                    ->danger()
                                    ->send();
                                $set('current_password', '');  // Reset password lama jika tidak cocok
                            } else {
                                if (auth()->user()->user_status == 1) {
                                    $user = auth()->user();
                                    $user->password = bcrypt($state['new_password']);
                                    $user->save();

                                    Notification::make()
                                        ->title('Berhasil')
                                        ->body('Password berhasil diperbarui.')
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Gagal')
                                        ->body('Anda tidak memiliki izin untuk mengubah password.')
                                        ->danger()
                                        ->send();
                                }
                            }
                        }
                    }),

                FileUpload::make('avatar')
                    ->label('Foto Profil')
                    ->directory('profile-photos')
                    ->image()
                    ->maxSize(1024) // Mengatur ukuran maksimal file
                    ->acceptedFileTypes(['image/jpeg', 'image/png']),
                
                Select::make('user_status')
                    ->label('Status Pengguna')
                    ->options([
                        1 => 'Petugas',
                        0 => 'User Biasa',
                    ])
                    ->required()
                    ->default(0),

                Select::make('user_group_id')
                    ->label('Grup Pengguna')
                    ->options([
                        1 => 'Admin',
                        2 => 'User Biasa',
                    ])
                    ->required()
                    ->default(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
    ->label('Foto Profil')
    ->circular()
    ->width(50)
    ->height(50)
    ->getStateUsing(function ($record) {
        // Cek apakah avatar ada
        if ($record->avatar) {
            return $record->avatar; // Mengambil avatar jika ada
        }

        // Jika tidak ada avatar, generate avatar default dengan huruf pertama nama
        $initial = strtoupper(substr($record->name, 0, 1));
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Warna acak untuk latar belakang

        // Menggunakan URL avatar dari ui-avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($initial) . '&background=' . substr($color, 1) . '&color=ffffff&size=128';
    }),

                TextColumn::make('name')->label('Nama'),
                TextColumn::make('full_name')->label('Nama Lengkap'),
                TextColumn::make('email')->label('Email'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penghapusan')
                    ->modalSubheading('Apakah Anda yakin ingin menghapus pengguna ini?')
                    ->modalButton('Hapus'),

                Tables\Actions\Action::make('resetPassword')
                    ->icon('heroicon-o-lock-closed')
                    ->label('Reset Password')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Reset Password')
                    ->modalSubheading('Apakah Anda yakin ingin mereset password pengguna ini ke "12345678"?')
                    ->modalButton('Reset Password')
                    ->action(function (User $record) {
                        $record->password = bcrypt('12345678');
                        $record->save();

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Password telah di-reset menjadi "12345678".')
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('filter_user_status')
                    ->query(function ($query) {
                        $user = auth()->user();

                        if ($user->user_status == 2) {
                            return $query->where('id', $user->id);
                        }

                        return $query;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->user_status == 1) {
            return $query;
        } else {
            return $query->where('id', auth()->id());
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
