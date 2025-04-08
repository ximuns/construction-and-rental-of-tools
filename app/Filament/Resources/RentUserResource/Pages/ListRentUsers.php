<?php

namespace App\Filament\Resources\RentUserResource\Pages;

use App\Filament\Resources\RentUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentUsers extends ListRecords
{
    protected static string $resource = RentUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
