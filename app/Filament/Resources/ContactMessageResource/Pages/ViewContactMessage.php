<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Pesan yang dibuka admin otomatis berubah dari "baru" menjadi "dibaca".
        if ($this->record->status === 'baru') {
            $this->record->update(['status' => 'dibaca']);
        }
    }
}