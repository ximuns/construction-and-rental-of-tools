<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryRentResource\Pages;
use App\Filament\Resources\CategoryRentResource\RelationManagers;
use App\Models\CategoryRent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryRentResource extends Resource
{
    protected static ?string $model = CategoryRent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Категории инструмента';
    }

    protected static ?string $navigationGroup = 'Аренда инструментов';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Категория инструмента')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label('Название категории')
                            ->maxLength(255)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название категории')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListCategoryRents::route('/'),
            'create' => Pages\CreateCategoryRent::route('/create'),
            'edit' => Pages\EditCategoryRent::route('/{record}/edit'),
        ];
    }
}
