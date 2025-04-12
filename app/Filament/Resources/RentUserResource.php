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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;


class RentUserResource extends Resource
{
    protected static ?string $model = RentUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';


    public static function getNavigationLabel(): string
    {
        return 'Арендаторы';
    }

    protected static ?string $navigationGroup = 'Обратная связь';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Арендатор')
                    ->schema([
                        Forms\Components\Select::make('rent_id')
                            ->relationship(
                                name: 'rent',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn (EloquentBuilder $query) => $query->orderBy('title')
                            )
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
                Tables\Columns\TextColumn::make('rent.title')
                    ->label('Объект аренды')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя арендатора')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rent_id')
                    ->relationship('rent', 'title')
                    ->label('Фильтр по объекту аренды')
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('От'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('До'),
                    ])
                    ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentUsers::route('/'),
        ];
    }
}
