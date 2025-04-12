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

    public static function getNavigationLabel(): string
    {
        return 'Наш подход к работе';
    }

    protected static ?string $navigationGroup = 'Преимущества';

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
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->sortable()
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Создано с'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Создано до'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Нет элементов')
            ->emptyStateDescription('Добавьте первый элемент, нажав на кнопку ниже')
            ->emptyStateIcon('heroicon-o-light-bulb');
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
