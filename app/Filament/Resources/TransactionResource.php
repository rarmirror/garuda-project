<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\FlightClass;
use App\Models\FlightSeat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Umum')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Transaksi')
                            ->required()
                            ->default('TRX-' . strtoupper(uniqid()))
                            ->readOnly(),

                        Forms\Components\Select::make('flight_id')
                            ->label('Penerbangan')
                            ->relationship('flight', 'flight_number')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('flight_class_id')
                            ->label('Kelas Penerbangan')
                            ->options(function (Get $get) {
                                $flightId = $get('flight_id');
                                if (!$flightId) return [];
                                return FlightClass::where('flight_id', $flightId)
                                    ->pluck('class_type', 'id');
                            })
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('promo_code_id')
                            ->label('Kode Promo')
                            ->relationship('promo', 'code')
                            ->searchable()
                            ->nullable(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'paid'    => 'Paid',
                                'failed'  => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('grandtotal')
                            ->label('Grand Total')
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Pemesan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pemesan')
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('number_of_passengers')
                            ->label('Jumlah Penumpang')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Daftar Penumpang')
                    ->schema([
                        Forms\Components\Repeater::make('passengers')
                            ->relationship('passengers')
                            ->label('')
                            ->schema([
                                Forms\Components\Select::make('flight_seat_id')
                                    ->label('Kursi')
                                    ->options(function (Get $get) {
                                        $flightId      = $get('../../flight_id');
                                        $flightClassId = $get('../../flight_class_id');

                                        if (!$flightId || !$flightClassId) return [];
                                        
                                        $classType = FlightClass::find($flightClassId)?->class_type;

                                        if (!$classType) return [];

                                        return FlightSeat::where('flight_id', $flightId)
                                            ->where('class_type', $classType)
                                            ->where('is_available', true)
                                            ->get()
                                            ->mapWithKeys(fn($seat) => [
                                                $seat->id => "{$seat->name} (Row {$seat->row}, Col {$seat->column})"
                                            ]);
                                    })
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Penumpang')
                                    ->required(),

                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Tanggal Lahir')
                                    ->required(),

                                Forms\Components\TextInput::make('nationality')
                                    ->label('Kewarganegaraan')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Tambah Penumpang')
                            ->reorderable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('flight.flight_number')
                    ->label('Penerbangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Pemesan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),

                Tables\Columns\TextColumn::make('number_of_passengers')
                    ->label('Penumpang')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('promo.code')
                    ->label('Promo')
                    ->default('-'),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('grandtotal')
                    ->label('Grand Total')
                    ->money('IDR'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid'    => 'Paid',
                        'failed'  => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}