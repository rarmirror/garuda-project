<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelFacilityResource\Pages;
use App\Models\HotelFacility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HotelFacilityResource extends Resource
{
    protected static ?string $model = HotelFacility::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Fasilitas Hotel';
    protected static ?string $navigationGroup = 'Hotel Management';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Fasilitas')
                ->required()
                ->placeholder('Cth: Swimming Pool'),
            Forms\Components\TextInput::make('icon')
                ->label('Icon (emoji)')
                ->placeholder('Cth: 🏊'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')->label('Icon'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Fasilitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Dipakai di')
                    ->counts('rooms')
                    ->suffix(' kamar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHotelFacilities::route('/'),
            'create' => Pages\CreateHotelFacility::route('/create'),
            'edit'   => Pages\EditHotelFacility::route('/{record}/edit'),
        ];
    }
}