<?php

namespace App\Filament\Resources\SocialNetworckResource\Pages;

use App\Filament\Resources\SocialNetworckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialNetworcks extends ListRecords
{
    protected static string $resource = SocialNetworckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
