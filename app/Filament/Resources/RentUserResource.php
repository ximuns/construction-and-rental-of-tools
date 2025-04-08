<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentUserResource\Pages;
use App\Filament\Resources\RentUserResource\RelationManagers;
use App\Models\RentUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentUserResource extends Resource
{
    protected static ?string $model = RentUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Арендатор')
                    ->schema([
                        Forms\Components\Select::make('rent_id')
                            ->relationship('rent','title')
                            ->required()
                            ->label('Выбранная аренда'),
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->label('Имя')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->maxLength(255)
                            ->label('Почта')
                            ->required(),
                        Forms\Components\Textarea::make('message')
                            ->label('Сообщение')
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
            'index' => Pages\ListRentUsers::route('/'),
            'create' => Pages\CreateRentUser::route('/create'),
            'edit' => Pages\EditRentUser::route('/{record}/edit'),
        ];
    }
}
