<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Hotels';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Hotel')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Hotel')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, callable $set) =>
                            $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('city')
                        ->label('Kota')
                        ->required(),

                    Forms\Components\TextInput::make('address')
                        ->label('Alamat')
                        ->required(),

                    Forms\Components\TextInput::make('country')
                        ->label('Negara')
                        ->default('Indonesia')
                        ->required(),

                    Forms\Components\Select::make('star_rating')
                        ->label('Bintang')
                        ->options([
                            1 => '⭐ 1 Bintang',
                            2 => '⭐⭐ 2 Bintang',
                            3 => '⭐⭐⭐ 3 Bintang',
                            4 => '⭐⭐⭐⭐ 4 Bintang',
                            5 => '⭐⭐⭐⭐⭐ 5 Bintang',
                        ])
                        ->default(3)
                        ->required(),

                    Forms\Components\TextInput::make('phone')
                        ->label('No. Telepon')
                        ->tel(),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email(),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Tersedia')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Detail & Gambar')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Hotel')
                        ->image()
                        ->directory('hotels')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Hotel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('star_rating')
                    ->label('Bintang')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state)),
                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Tipe Kamar')
                    ->counts('rooms'),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('star_rating')
                    ->label('Bintang')
                    ->options([1=>'1⭐',2=>'2⭐',3=>'3⭐',4=>'4⭐',5=>'5⭐']),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit'   => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}