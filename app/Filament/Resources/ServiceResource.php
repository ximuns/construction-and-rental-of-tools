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
use Illuminate\Contracts\Validation\Rule;
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

// app/Models/Service.php
    public function generateFormula(): string
    {
        if (empty($this->calculator_config['inputs'])) {
            return 'price';
        }

        $formula = 'price';

        foreach ($this->calculator_config['inputs'] as $input) {
            if (in_array($input['type'], ['range', 'number'])) {
                $formula .= " * {$input['key']}";
            }
            elseif ($input['type'] === 'select') {
                $formula .= " * {$input['key']}_multiplier";
            }
            elseif ($input['type'] === 'checkbox_group') {
                $formula .= " * {$input['key']}_multiplier";
            }
        }

        return $formula;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('ServiceConfig')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Настройки услуги')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(255)
                                    ->label('Название услуги')
                                    ->required(),
                                Forms\Components\TextInput::make('titleCalculator')
                                    ->maxLength(255)
                                    ->label('Тип проекта в калькуляторе')
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
                            ]),
                        Forms\Components\Tabs\Tab::make('Калькулятор')
                            ->schema([
                                // Базовая цена
                                Forms\Components\TextInput::make('price')
                                    ->label('Базовая цена')
                                    ->helperText('В формуле указана как price')
                                    ->numeric()
                                    ->required(),

                                // Динамические инпуты
                                Forms\Components\Repeater::make('calculator_config.inputs')
                                    ->label('Элементы калькулятора')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): string => $state['label'] ?? 'Новый элемент')
                                    ->schema([
                                        // Выбор типа элемента
                                        Forms\Components\Select::make('type')
                                            ->label('Тип элемента')
                                            ->options([
                                                'number' => 'Числовое поле',
                                                'range' => 'Ползунок',
                                                'select' => 'Выпадающий список',
                                                'checkbox_group' => 'Группа чекбоксов',
                                            ])
                                            ->required()
                                            ->live()
                                            ->columnSpan(1),

                                        // Общие поля
                                        Forms\Components\TextInput::make('label')
                                            ->label('Заголовок')
                                            ->required()
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('key')
                                            ->label('Ключ (для формулы)')
                                            ->helperText('Например: quantity')
                                            ->visible(fn ($get) => !in_array($get('type'), ['group']))
                                            ->columnSpan(1),

                                        // Настройки для разных типов
                                        Forms\Components\Group::make()
                                            ->schema(function ($get) {
                                                $schema = [];

                                                // Для числовых полей и ползунков
                                                if (in_array($get('type'), ['number', 'range'])) {
                                                    $schema[] = Forms\Components\Grid::make()
                                                        ->schema([
                                                            Forms\Components\TextInput::make('min')
                                                                ->label('Мин. значение')
                                                                ->numeric(),
                                                            Forms\Components\TextInput::make('max')
                                                                ->label('Макс. значение')
                                                                ->numeric(),
                                                        ])
                                                        ->columns(2);
                                                }

                                                // Для выпадающего списка
                                                if ($get('type') === 'select') {
                                                    $schema[] = Forms\Components\Repeater::make('options')
                                                        ->label('Варианты выбора')
                                                        ->schema([
                                                            Forms\Components\TextInput::make('label')
                                                                ->label('Отображаемый текст'),
                                                            Forms\Components\TextInput::make('value')
                                                                ->label('Значение'),
                                                            Forms\Components\TextInput::make('multiplier')
                                                                ->label('Множитель цены')
                                                                ->helperText('Пример: 1.02 = 2%')
                                                                ->numeric()
                                                                ->default(1.0)
                                                        ])
                                                        ->columns(3);
                                                }

                                                // Для группы чекбоксов
                                                if ($get('type') === 'checkbox_group') {
                                                    $schema[] = Forms\Components\Repeater::make('inputs')
                                                        ->label('Чекбоксы')
                                                        ->schema([
                                                            Forms\Components\TextInput::make('label')
                                                                ->label('Текст'),
                                                            Forms\Components\TextInput::make('key')
                                                                ->label('Ключ'),
                                                            Forms\Components\TextInput::make('multiplier')
                                                                ->label('Множитель')
                                                                ->numeric()
                                                                ->default(1.0)
                                                        ])
                                                        ->columns(3);
                                                }

                                                return $schema;
                                            })
                                            ->columnSpanFull()
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),

                                // Ручной ввод формулы
                                Forms\Components\Textarea::make('calculator_config.formula')
                                    ->label('Формула расчета')
                                    ->required()
                                    ->hint('
                                        Доступные переменные:
                                        price - базовая цена
                                        ключ - значение поля
                                        ключ_multiplier - для select/checkbox
                                    ')
                                    ->placeholder('price * quantity * (checkbox_multiplier + select_multiplier)')
                                    ->rule(function () {
                                        return new class implements Rule {
                                            public function passes($attribute, $value) {
                                                try {
                                                    $testValues = ['width' => 100, 'height' => 100, 'price' => 1];
                                                    eval('return '.$value.';');
                                                    return true;
                                                } catch (\Throwable $e) {
                                                    return false;
                                                }
                                            }
                                            public function message() {
                                                return 'Ошибка в формуле! Проверьте переменные и синтаксис.';
                                            }
                                        };
                                    })
                                    ->columnSpanFull()
                            ])
                    ])->columnSpanFull(),
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
