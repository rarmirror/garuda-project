<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Filament\Resources\PromoCodeResource\RelationManagers;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required(),
                Forms\Components\Select::make('discount_type')
                    ->required()
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage'
                    ]),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\DateTimePicker::make('valid_until')
                    ->required(),
                Forms\Components\TextInput::make('max_usage')
                    ->label('Maks. Penggunaan')
                    ->required()
                    ->numeric()
                    ->default(10)
                    ->minValue(1),
                Forms\Components\TextInput::make('usage_count')
                    ->label('Sudah Dipakai')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('discount_type'),
                Tables\Columns\TextColumn::make('discount')
                    ->formatStateUsing (fn(PromoCode $record): string => match ($record->discount_type) {
                    'fixed' => 'Rp ' . number_format($record->discount, 0, ',', '.'),
                    'percentage' => $record->discount. '%',
                    }),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Pemakaian')
                    ->formatStateUsing(fn(PromoCode $record): string => $record->usage_count . ' / ' . $record->max_usage)
                    ->color(fn(PromoCode $record): string => $record->isExpired() ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_expired')
                    ->label('Habis')
                    ->getStateUsing(fn(PromoCode $record): bool => $record->isExpired())
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reset')
                    ->label('Reset')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset penggunaan promo?')
                    ->modalDescription(fn(PromoCode $record): string => "Promo \"{$record->code}\" telah dipakai {$record->usage_count}x. Reset ke 0?")
                    ->action(fn(PromoCode $record) => $record->resetUsage())
                    ->visible(fn(PromoCode $record): bool => $record->usage_count > 0),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
