<?php

namespace App\Filament\Resources\CategoryRentResource\Pages;

use App\Filament\Resources\CategoryRentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryRents extends ListRecords
{
    protected static string $resource = CategoryRentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
