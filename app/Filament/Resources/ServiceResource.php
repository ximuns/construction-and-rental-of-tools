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
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return 'Услуги';
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
                                Forms\Components\TextInput::make('calculator_config.price')
                                    ->label('Базовая цена')
                                    ->helperText('В формуле указана как price')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\Repeater::make('calculator_config.inputs')
                                    ->label('Элементы калькулятора')
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): string => $state['label'] ?? 'Новый элемент')
                                    ->schema([
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

                                        Forms\Components\TextInput::make('label')
                                            ->label('Заголовок')
                                            ->required()
                                            ->columnSpan(1)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                                if ($state) {
                                                    $key = Str::slug($state, '_');
                                                    $inputs = $get('../../') ?? [];
                                                    $counter = 1;
                                                    $originalKey = $key;

                                                    while (collect($inputs)->contains('key', $key)) {
                                                        $key = $originalKey . '_' . $counter;
                                                        $counter++;
                                                    }

                                                    $set('key', $key);
                                                }
                                            }),

                                        Forms\Components\TextInput::make('key')
                                            ->label('Ключ (для формулы)')
                                            ->helperText('Только английские буквы и подчеркивания')
                                            ->visible(fn ($get) => !in_array($get('type'), ['group']))
                                            ->columnSpan(1)
                                            ->rules(['regex:/^[a-z_]+$/'])
                                            ->validationMessages([
                                                'regex' => 'Ключ должен содержать только английские буквы в нижнем регистре и подчеркивания',
                                            ])
                                            ->afterStateHydrated(function ($state, Forms\Set $set) {
                                                if ($state && !preg_match('/^[a-z_]+$/', $state)) {
                                                    $set('key', Str::slug($state, '_'));
                                                }
                                            }),

                                        Forms\Components\Group::make()
                                            ->schema(function ($get) {
                                                $schema = [];

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

                                                if ($get('type') === 'select') {
                                                    $schema[] = Forms\Components\Repeater::make('options')
                                                        ->label('Варианты выбора')
                                                        ->schema([
                                                            Forms\Components\TextInput::make('label')
                                                                ->label('Отображаемый текст')
                                                                ->live()
                                                                ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                                                    if ($state && !$get('value')) {
                                                                        $value = Str::slug($state, '_');
                                                                        $options = $get('../../options') ?? [];
                                                                        $counter = 1;
                                                                        $originalValue = $value;

                                                                        while (collect($options)->contains('value', $value)) {
                                                                            $value = $originalValue . '_' . $counter;
                                                                            $counter++;
                                                                        }

                                                                        $set('value', $value);
                                                                    }
                                                                }),
                                                            Forms\Components\TextInput::make('value')
                                                                ->label('Значение')
                                                                ->rules(['regex:/^[a-z_]+$/']),
                                                            Forms\Components\TextInput::make('multiplier')
                                                                ->label('Множитель цены')
                                                                ->helperText('Пример: 1.02 = +2% к цене')
                                                                ->numeric()
                                                                ->default(1.0)
                                                        ])
                                                        ->columns(3);
                                                }

                                                if ($get('type') === 'checkbox_group') {
                                                    $schema[] = Forms\Components\Repeater::make('inputs')
                                                        ->label('Чекбоксы')
                                                        ->schema([
                                                            Forms\Components\TextInput::make('label')
                                                                ->label('Текст')
                                                                ->live()
                                                                ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                                                    if ($state && !$get('key')) {
                                                                        $key = Str::slug($state, '_');
                                                                        $inputs = $get('../../inputs') ?? [];
                                                                        $counter = 1;
                                                                        $originalKey = $key;

                                                                        while (collect($inputs)->contains('key', $key)) {
                                                                            $key = $originalKey . '_' . $counter;
                                                                            $counter++;
                                                                        }

                                                                        $set('key', $key);
                                                                    }
                                                                }),
                                                            Forms\Components\TextInput::make('key')
                                                                ->label('Ключ')
                                                                ->rules(['regex:/^[a-z_]+$/']),
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
                                    ->columnSpanFull()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $keys = [];
                                        $inputs = $get('../../') ?? [];

                                        foreach ($inputs as $input) {
                                            if (isset($input['key'])) {
                                                if (in_array($input['key'], $keys)) {
                                                    throw ValidationException::withMessages([
                                                        'calculator_config.inputs' => "Ключ '{$input['key']}' уже используется. Ключи должны быть уникальными.",
                                                    ]);
                                                }
                                                $keys[] = $input['key'];
                                            }
                                        }
                                    }),

                                Forms\Components\Textarea::make('calculator_config.formula')
                                    ->label('Формула расчета')
                                    ->required()
                                    ->columnSpanFull()
                                    ->hint(function ($get) {
                                        $variables = ['price'];
                                        $inputs = $get('calculator_config.inputs') ?? [];

                                        foreach ($inputs as $input) {
                                            if (isset($input['key']) && in_array($input['type'], ['number', 'range'])) {
                                                $variables[] = $input['key'];
                                            }

                                            if (isset($input['key']) && in_array($input['type'], ['select', 'checkbox_group'])) {
                                                $variables[] = $input['key'] . '_multiplier';
                                            }
                                        }

                                        return "Доступные переменные:\n" . implode("\n", array_map(fn($v) => "• {$v}", $variables));
                                    })
                                    ->rules([
                                        function ($get) {
                                            return function (string $attribute, $value, $fail) use ($get) {
                                                $allowedVars = ['price'];
                                                $inputs = $get('calculator_config.inputs') ?? [];

                                                foreach ($inputs as $input) {
                                                    if (isset($input['key']) && in_array($input['type'], ['number', 'range'])) {
                                                        $allowedVars[] = $input['key'];
                                                    }

                                                    if (isset($input['key']) && in_array($input['type'], ['select', 'checkbox_group'])) {
                                                        $allowedVars[] = $input['key'] . '_multiplier';
                                                    }
                                                }

                                                if (preg_match('/[а-яА-ЯёЁ]/u', $value)) {
                                                    $fail("Формула содержит русские буквы. Используйте только английские буквы в переменных.");
                                                    return;
                                                }

                                                preg_match_all('/[a-zA-Z_]+(?:_multiplier)?/', $value, $matches);
                                                $usedVars = array_unique($matches[0]);

                                                foreach ($usedVars as $var) {
                                                    if (!in_array($var, $allowedVars)) {
                                                        $fail("Переменная '{$var}' не существует. Доступные переменные: " . implode(', ', $allowedVars));
                                                        return;
                                                    }
                                                }

                                                try {
                                                    $testValues = array_fill_keys($allowedVars, 1);
                                                    eval('return ' . $value . ';');
                                                } catch (\Throwable $e) {
                                                    $fail("Ошибка в синтаксисе формулы: " . $e->getMessage());
                                                }
                                            };
                                        },
                                    ]),

                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Select::make('formula_helper')
                                            ->label('Добавить переменную')
                                            ->options(function ($get) {
                                                $options = ['price' => 'Базовая цена (price)'];
                                                $inputs = $get('calculator_config.inputs') ?? [];

                                                foreach ($inputs as $input) {
                                                    if (isset($input['key'])) {
                                                        if (in_array($input['type'], ['number', 'range'])) {
                                                            $options[$input['key']] = $input['label'] . ' (' . $input['key'] . ')';
                                                        }

                                                        if (in_array($input['type'], ['select', 'checkbox_group'])) {
                                                            $options[$input['key'].'_multiplier'] = $input['label'] . ' множитель (' . $input['key'].'_multiplier)';
                                                        }
                                                    }
                                                }

                                                return $options;
                                            })
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                                if ($state) {
                                                    $currentFormula = $get('calculator_config.formula') ?? '';
                                                    $set('calculator_config.formula', trim($currentFormula . ' ' . $state));
                                                    $set('formula_helper', null);
                                                }
                                            })
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('formula_operator')
                                            ->label('Добавить оператор')
                                            ->options([
                                                '+' => '+ (сложение)',
                                                '-' => '- (вычитание)',
                                                '*' => '* (умножение)',
                                                '/' => '/ (деление)',
                                                '(' => '( (открыть скобку)',
                                                ')' => ') (закрыть скобку)',
                                            ])
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                                if ($state) {
                                                    $currentFormula = $get('calculator_config.formula') ?? '';
                                                    $set('calculator_config.formula', trim($currentFormula . ' ' . $state));
                                                    $set('formula_operator', null);
                                                }
                                            })
                                    ])
                            ])
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название услуги')
                    ->sortable()
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('titleCalculator')
                    ->label('Тип в калькуляторе')
                    ->sortable()
                    ->searchable()
                    ->limit(25),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Статус активности')
                    ->options([
                        true => 'Активные',
                        false => 'Неактивные',
                    ]),

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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Активировать')
                        ->icon('heroicon-o-eye')
                        ->action(function (Collection $records) {
                            $records->each->update(['is_active' => true]);
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Деактивировать')
                        ->icon('heroicon-o-eye-slash')
                        ->action(function (Collection $records) {
                            $records->each->update(['is_active' => false]);
                        }),
                ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Нет услуг')
            ->emptyStateDescription('Создайте первую услугу')
            ->emptyStateIcon('heroicon-o-briefcase');
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
