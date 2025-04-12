<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvantagesPageResource\Pages;
use App\Filament\Resources\AdvantagesPageResource\RelationManagers;
use App\Models\AdvantagesPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvantagesPageResource extends Resource
{
    protected static ?string $model = AdvantagesPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Наш подход к работе')
                    ->schema([
                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/svg+xml'])
                            ->required()
                            ->label('Иконка'),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Заголовок'),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(255)
                            ->label('Описание'),
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
            'index' => Pages\ListAdvantagesPages::route('/'),
            'create' => Pages\CreateAdvantagesPage::route('/create'),
            'edit' => Pages\EditAdvantagesPage::route('/{record}/edit'),
        ];
    }
}
