<?php

namespace App\Filament\Resources\CategoryPortfolioResource\Pages;

use App\Filament\Resources\CategoryPortfolioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryPortfolio extends EditRecord
{
    protected static string $resource = CategoryPortfolioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
