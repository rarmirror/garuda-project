<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelRoomResource\Pages;
use App\Models\HotelRoom;
use App\Models\HotelFacility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HotelRoomResource extends Resource
{
    protected static ?string $model = HotelRoom::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Kamar Hotel';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Kamar')
                ->schema([
                    Forms\Components\Select::make('hotel_id')
                        ->label('Hotel')
                        ->relationship('hotel', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Kamar')
                        ->required()
                        ->placeholder('Cth: Deluxe Room with Sea View'),

                    Forms\Components\Select::make('type')
                        ->label('Tipe Kamar')
                        ->options([
                            'standard'     => 'Standard',
                            'deluxe'       => 'Deluxe',
                            'suite'        => 'Suite',
                            'family'       => 'Family',
                            'presidential' => 'Presidential',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('price_per_night')
                        ->label('Harga per Malam')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\TextInput::make('capacity')
                        ->label('Kapasitas Tamu')
                        ->numeric()
                        ->default(2)
                        ->required(),

                    Forms\Components\TextInput::make('total_rooms')
                        ->label('Jumlah Kamar')
                        ->numeric()
                        ->required(),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Tersedia')
                        ->default(true),

                    Forms\Components\Select::make('facilities')
                        ->label('Fasilitas')
                        ->relationship('facilities', 'name')
                        ->multiple()
                        ->preload(),
                ])->columns(2),

            Forms\Components\Section::make('Detail & Gambar')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Kamar')
                        ->image()
                        ->directory('hotel-rooms')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Foto'),
                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Hotel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'gray'    => 'standard',
                        'info'    => 'deluxe',
                        'warning' => 'suite',
                        'success' => 'family',
                        'danger'  => 'presidential',
                    ]),
                Tables\Columns\TextColumn::make('price_per_night')
                    ->label('Harga/Malam')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas'),
                Tables\Columns\TextColumn::make('total_rooms')
                    ->label('Jumlah Kamar'),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hotel')
                    ->relationship('hotel', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'standard'     => 'Standard',
                        'deluxe'       => 'Deluxe',
                        'suite'        => 'Suite',
                        'family'       => 'Family',
                        'presidential' => 'Presidential',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHotelRooms::route('/'),
            'create' => Pages\CreateHotelRoom::route('/create'),
            'edit'   => Pages\EditHotelRoom::route('/{record}/edit'),
        ];
    }
}