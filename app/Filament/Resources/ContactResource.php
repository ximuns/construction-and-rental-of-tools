<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public static function getNavigationLabel(): string
    {
        return 'Контакты';
    }

    protected static ?string $navigationGroup = 'Как вас найти';

    public static function canCreate(): bool
    {
        return Contact::count() === 0;
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Проверка на наличие записи
        if (Contact::exists()) {
            throw ValidationException::withMessages([
                'global' => 'Настройки сайта уже существуют. Вы можете редактировать существующую запись.',
            ]);
        }
        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Контакты')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                            ->label('Адрес')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->maxLength(255)
                            ->label('Телефон')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->maxLength(255)
                            ->label('Почта')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('custom')
                    ->label('Настройка контактов')
                    ->getStateUsing(fn ($record) => 'Настройка контактов')
                    ->sortable(false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ])
            ->paginated(false);
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
