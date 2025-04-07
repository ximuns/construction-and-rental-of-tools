<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentResource\Pages;
use App\Filament\Resources\RentResource\RelationManagers;
use App\Models\Rent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentResource extends Resource
{
    protected static ?string $model = Rent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Аренда')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Категория')
                            ->relationship('category', 'title'),
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->label('Название инструмента')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->maxLength(255)
                            ->numeric()
                            ->label('Цена аренды в день'),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->label('Изображение инструмента')
                            ->required(),
                        Forms\Components\Toggle::make('is_access')
                            ->label('Доступен для аренды')
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
            'index' => Pages\ListRents::route('/'),
            'create' => Pages\CreateRent::route('/create'),
            'edit' => Pages\EditRent::route('/{record}/edit'),
        ];
    }
}
