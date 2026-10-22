<?php

namespace App\Filament\Resources\HotelFacilityResource\Pages;

use App\Filament\Resources\HotelFacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotelFacility extends EditRecord
{
    protected static string $resource = HotelFacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
