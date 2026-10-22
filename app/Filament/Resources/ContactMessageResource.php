<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Pesan Kontak';

    protected static ?string $modelLabel = 'Pesan Kontak';

    protected static ?string $pluralModelLabel = 'Pesan Kontak';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $baru = static::getModel()::where('status', 'baru')->count();

        return $baru > 0 ? (string) $baru : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }

    public static function statusColor(string $state): string
    {
        return match ($state) {
            'baru'    => 'warning',
            'dibaca'  => 'info',
            'selesai' => 'success',
            default   => 'gray',
        };
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Pengirim')
                ->schema([
                    TextEntry::make('name')->label('Nama'),
                    TextEntry::make('email')->label('Email')->copyable(),
                    TextEntry::make('phone')->label('Telepon')->placeholder('-'),
                    TextEntry::make('created_at')->label('Diterima')->dateTime('d M Y, H:i'),
                ])
                ->columns(2),

            Section::make('Pesan')
                ->schema([
                    TextEntry::make('subject')
                        ->label('Subjek')
                        ->badge()
                        ->color(fn (string $state): string => $state === 'Keluhan' ? 'danger' : 'gray'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => ContactMessageResource::statusColor($state))
                        ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    TextEntry::make('message')
                        ->label('Isi pesan')
                        ->formatStateUsing(fn (string $state): string => nl2br(e($state)))
                        ->html()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Keluhan' ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => ContactMessageResource::statusColor($state))
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'baru'    => 'Baru',
                        'dibaca'  => 'Dibaca',
                        'selesai' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('subject')
                    ->label('Subjek')
                    ->options(array_combine(ContactMessage::SUBJECTS, ContactMessage::SUBJECTS)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('selesai')
                    ->label('Tandai selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->status !== 'selesai')
                    ->action(fn (ContactMessage $record) => $record->update(['status' => 'selesai'])),
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
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}