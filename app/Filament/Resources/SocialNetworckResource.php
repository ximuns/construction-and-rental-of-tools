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
use Illuminate\Validation\ValidationException;

class SocialNetworckResource extends Resource
{
    protected static ?string $model = SocialNetworck::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function getNavigationLabel(): string
    {
        return 'Социальные сети';
    }

    protected static ?string $navigationGroup = 'Как вас найти';

    public static function canCreate(): bool
    {
        return SocialNetworck::count() === 0;
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Проверка на наличие записи
        if (SocialNetworck::exists()) {
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
                Forms\Components\Section::make('Социальные сети')
                    ->schema([
                        Forms\Components\Repeater::make('social_networks')
                            ->label('Социальные сети')
                            ->schema([
                                Forms\Components\TextInput::make('link')
                                    ->label('Ссылка')
                                    ->required(),
                                Forms\Components\FileUpload::make('icon')
                                    ->label('Иконка')
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
                Tables\Columns\TextColumn::make('custom')
                    ->label('Настройка социальных сетей')
                    ->getStateUsing(fn ($record) => 'Настройка социальных сетей')
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
            'index' => Pages\ListSocialNetworcks::route('/'),
            'create' => Pages\CreateSocialNetworck::route('/create'),
            'edit' => Pages\EditSocialNetworck::route('/{record}/edit'),
        ];
    }
}
