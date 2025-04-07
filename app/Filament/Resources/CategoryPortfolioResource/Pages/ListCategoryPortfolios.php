<?php

namespace App\Filament\Resources\CategoryPortfolioResource\Pages;

use App\Filament\Resources\CategoryPortfolioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryPortfolios extends ListRecords
{
    protected static string $resource = CategoryPortfolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
