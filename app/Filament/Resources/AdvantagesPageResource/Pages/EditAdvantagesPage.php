<?php

namespace App\Filament\Resources\AdvantagesPageResource\Pages;

use App\Filament\Resources\AdvantagesPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvantagesPage extends EditRecord
{
    protected static string $resource = AdvantagesPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
