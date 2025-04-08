<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvantageResource\Pages;
use App\Filament\Resources\AdvantageResource\RelationManagers;
use App\Models\Advantage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvantageResource extends Resource
{
    protected static ?string $model = Advantage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return 'Преимущества';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Преимущества')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255)
                            ->label('Название преимущества')
                            ->required(),
                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/svg+xml'])
                            ->label('Иконка преимущества')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание преимущества')
                            ->maxLength(255)
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
            'index' => Pages\ListAdvantages::route('/'),
            'create' => Pages\CreateAdvantage::route('/create'),
            'edit' => Pages\EditAdvantage::route('/{record}/edit'),
        ];
    }
}
