<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialNetworckResource\Pages;
use App\Filament\Resources\SocialNetworckResource\RelationManagers;
use App\Models\SocialNetworck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialNetworckResource extends Resource
{
    protected static ?string $model = SocialNetworck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Социальные сети')
                    ->schema([
                        Forms\Components\Repeater::make('social_networks')
                            ->label('Социальные сети')
                            ->schema([
                                Forms\Components\TextInput::make('link')
                                    ->label('ссылка')
                                    ->required(),
                                Forms\Components\FileUpload::make('icon')
                                    ->label('иконка')
                                    ->acceptedFileTypes(['image/png', 'image/svg+xml'])
                                    ->image()
                                    ->required(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSocialNetworcks::route('/'),
            'create' => Pages\CreateSocialNetworck::route('/create'),
            'edit' => Pages\EditSocialNetworck::route('/{record}/edit'),
        ];
    }
}
