<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return 'Услуги';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Услуги')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->label('Название услуги')
                            ->required(),
                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/svg+xml'])
                            ->label('Иконка услуги')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->label('Изображение услуги')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание услуги')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Отображать на сайте')
                            ->required(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
