<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransaksiResource\Pages;
use App\Models\Transaksi;
use App\Models\Layanan;
use App\Models\Pasien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Data Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        // Tambah atau pilih pasien
                        Select::make('pasien_id')
                            ->label('Nama Pasien')
                            ->relationship('pasien', 'nama')
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('nama')->required(),
                                TextInput::make('telepon')->label('Telepon'),
                            ])
                            ->required(),

                        Select::make('layanan_id')
                            ->label('Layanan')
                            ->relationship('layanan', 'nama')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $layanan = Layanan::find($state);
                                if ($layanan) {
                                    $set('harga_satuan', $layanan->harga);
                                    $set('durasi_satuan', $layanan->durasi);
                                    $set('total_harga', $layanan->harga);
                                    $set('durasi', $layanan->durasi);
                                }
                            }),

                        TextInput::make('jumlah')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $harga = $get('harga_satuan') ?? 0;
                                $durasi = $get('durasi_satuan') ?? 0;
                                $set('total_harga', $state * $harga);
                                $set('durasi', $state * $durasi);
                            }),

                        TextInput::make('harga_satuan')
                            ->disabled()
                            ->prefix('Rp ')
                            ->label('Harga Satuan'),

                        TextInput::make('durasi_satuan')
                            ->disabled()
                            ->suffix(' menit')
                            ->label('Durasi per Sesi'),

                        TextInput::make('durasi')
                            ->disabled()
                            ->suffix(' menit')
                            ->label('Total Durasi'),

                        TextInput::make('total_harga')
                            ->disabled()
                            ->prefix('Rp ')
                            ->label('Total Harga'),

                        DateTimePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pasien.nama')->label('Pasien'),
                Tables\Columns\TextColumn::make('layanan.nama')->label('Layanan'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('durasi')->suffix(' menit'),
                Tables\Columns\TextColumn::make('total_harga')->money('IDR'),
                Tables\Columns\TextColumn::make('tanggal')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
