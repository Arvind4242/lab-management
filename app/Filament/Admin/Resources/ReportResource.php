<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Filament\Admin\Resources\ReportResource\RelationManagers\ResultsRelationManager;
use App\Models\Report;
use Filament\Tables\Actions\EditAction;
use App\Models\Test;
use App\Models\TestPanel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// use App\Filament\Admin\Resources\Filter;
use Filament\Tables\Filters\Filter;
// use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $pluralModelLabel = 'Reports';
    protected static ?string $modelLabel = 'Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Patient Details')
                    ->schema([
                        Forms\Components\TextInput::make('patient_name')
                            ->required()
                            ->label('Patient Name'),

                        Forms\Components\TextInput::make('patient_id')
                            ->label('Patient ID'),

                        Forms\Components\Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ])
                            ->required()
                            ->label('Gender')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $tests = $get('tests') ?? [];
                                foreach ($tests as $key => &$test) {
                                    if (isset($test['reference_range_male'], $test['reference_range_female'], $test['reference_range_other'])) {
                                        $test['reference_range'] =
                                            $state === 'Female'
                                                ? $test['reference_range_female']
                                                : ($state === 'Other'
                                                    ? $test['reference_range_other']
                                                    : $test['reference_range_male']);
                                    }
                                }
                                $set('tests', $tests);
                            }),

                        Forms\Components\TextInput::make('age')
                            ->numeric()
                            ->label('Age (Years)'),

                        Forms\Components\DatePicker::make('test_date')
                            ->required()
                            ->label('Test Date')
                            ->default(now())
                            ->dehydrated(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Additional Info')
                    ->schema([
                        Forms\Components\TextInput::make('referred_by')
                            ->label('Referred By'),
                        Forms\Components\TextInput::make('client_name')
                            ->label('Client Name'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Panel / Test Selection')
                    ->schema([
                        Forms\Components\Select::make('panel_or_test')
                            ->label('Select Panel or Test')
                            ->options(function () {
                                $panels = TestPanel::pluck('name', 'id')->toArray();
                                $tests = Test::pluck('name', 'id')->toArray();

                                $options = [];
                                foreach ($panels as $id => $name) {
                                    $options['panel_' . $id] = '🧪 Panel: ' . $name;
                                }
                                foreach ($tests as $id => $name) {
                                    $options['test_' . $id] = '🧫 Test: ' . $name;
                                }
                                return $options;
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (!$state) {
                                    $set('tests', []);
                                    $set('test_panel_id', null);
                                    return;
                                }

                                if (Str::startsWith($state, 'panel_')) {
                                    $panelId = Str::replaceFirst('panel_', '', $state);
                                    $set('test_panel_id', $panelId);

                                    $panel = TestPanel::find($panelId);
                                    $testIds = [];

                                    if ($panel) {
                                        $testData = $panel->tests;

                                        if (is_array($testData)) {
                                            $testIds = $testData;
                                        } elseif (is_string($testData)) {
                                            $decoded = json_decode($testData, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $testIds = $decoded;
                                            } else {
                                                $testIds = array_filter(explode(',', $testData));
                                            }
                                        }
                                    }

                                    $tests = Test::whereIn('id', $testIds)->with('unit')->get();
                                } else {
                                    $testId = Str::replaceFirst('test_', '', $state);
                                    $set('test_panel_id', null);
                                    $tests = Test::where('id', $testId)->with('unit')->get();
                                }

                                $gender = $get('gender') ?? 'Male';

                                $set('tests', $tests->map(function ($test) use ($gender) {
                                    return [
                                        'test_id' => $test->id,
                                        'test_name' => $test->name,
                                        'unit' => $test->unit?->name ?? '',
                                        'reference_range_male' => $test->default_result ?? '',
                                        'reference_range_female' => $test->default_result_female ?? '',
                                        'reference_range_other' => $test->default_result_other ?? '',
                                        'reference_range' => $gender === 'Female'
                                            ? ($test->default_result_female ?? '')
                                            : ($gender === 'Other'
                                                ? ($test->default_result_other ?? '')
                                                : ($test->default_result ?? '')),
                                        'value' => null,
                                        'is_out_of_range' => false,
                                    ];
                                })->toArray());
                            }),
                    ]),

                Forms\Components\Section::make('Test Results')
                    ->schema([
                        Forms\Components\Repeater::make('tests')
                            ->label('Test List')
                            ->schema([
                                Forms\Components\TextInput::make('test_name')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('value')
                                    ->label('Result Value')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $gender = $get('../../gender') ?? 'Male';
                                        $range = $gender === 'Female'
                                            ? ($get('reference_range_female') ?? '')
                                            : ($gender === 'Other'
                                                ? ($get('reference_range_other') ?? '')
                                                : ($get('reference_range_male') ?? ''));

                                        $set('is_out_of_range', false);

                                        if (is_numeric($state) && !empty($range) && preg_match('/(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/', $range, $m)) {
                                            $min = (float) $m[1];
                                            $max = (float) $m[2];
                                            $set('is_out_of_range', $state < $min || $state > $max);
                                        }
                                    })
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('unit')
                                    ->disabled()
                                    ->label('Unit')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('reference_range')
                                    ->disabled()
                                    ->label('Reference Range')
                                    ->columnSpan(2),

                                Forms\Components\Toggle::make('is_out_of_range')
                                    ->label('Out of Range')
                                    ->disabled()
                                    ->columnSpan(1),
                            ])
                            ->columns(8)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['test_name'] ?? null)
                            ->default([]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Sl. No.')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('patient_name')
                    ->label('Patient Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('age')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',
                        'Female' => 'success',
                        'Other' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('test_date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label('Created')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('test_date', 'desc')
            ->filters([
    // ✅ Gender filter (safe)
    SelectFilter::make('gender')
        ->label('Gender')
        ->options([
            'Male'   => 'Male',
            'Female' => 'Female',
            'Other'  => 'Other',
        ])
        ->searchable(),

    // ✅ Age group filter (safe)
    SelectFilter::make('age_group')
        ->label('Age Group')
        ->options([
            '0-12'  => 'Child (0-12)',
            '13-25' => 'Teen (13-25)',
            '26-40' => 'Adult (26-40)',
            '41-60' => 'Middle Age (41-60)',
            '60+'   => 'Senior (60+)',
        ])
        ->query(function (Builder $query, array $data): Builder {
            if (blank($data['value'])) {
                return $query;
            }

            return match ($data['value']) {
                '0-12'  => $query->whereBetween('age', [0, 12]),
                '13-25' => $query->whereBetween('age', [13, 25]),
                '26-40' => $query->whereBetween('age', [26, 40]),
                '41-60' => $query->whereBetween('age', [41, 60]),
                '60+'   => $query->where('age', '>', 60),
                default => $query,
            };
        }),

    // ✅ Test date range filter (safe)
    Filter::make('test_date_range')
        ->label('Test Date')
        ->form([
            DatePicker::make('from')->label('From'),
            DatePicker::make('until')->label('To'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('test_date', '>=', $date))
                ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('test_date', '<=', $date));
        }),

    // ✅ Client name filter – NULL safe
    SelectFilter::make('client_name')
        ->label('Client Name')
        ->options(function () {
            return Report::query()
                ->whereNotNull('client_name')       // ❗ NULL hatao
                ->distinct()
                ->pluck('client_name')              // sirf ek column
                ->filter()                          // empty strings bhi hatao
                ->mapWithKeys(fn ($value) => [
                    (string) $value => (string) $value, // ✅ key & label both string
                ])
                ->toArray();
        })
        ->searchable(),

    // ✅ Referred by filter – NULL safe
    SelectFilter::make('referred_by')
        ->label('Referred By')
        ->options(function () {
            return Report::query()
                ->whereNotNull('referred_by')
                ->distinct()
                ->pluck('referred_by')
                ->filter()
                ->mapWithKeys(fn ($value) => [
                    (string) $value => (string) $value,
                ])
                ->toArray();
        })
        ->searchable(),
])


           ->actions([
    Tables\Actions\EditAction::make()
    ->url(fn ($record) => route('reports.edit', $record->id))
    ->openUrlInNewTab(false),
    Tables\Actions\Action::make('preview')
        ->label('Preview')
        ->icon('heroicon-o-eye')
        ->url(fn($record) => route('reports.print', $record))
        ->openUrlInNewTab(),
    Tables\Actions\Action::make('download')
        ->label('PDF')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->url(fn($record) => route('reports.download', $record)),
    // Tables\Actions\DeleteAction::make(),
           ]);


    }

    public static function getRelations(): array
    {
        return [
            ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::check() && Auth::user()->role === 'admin') {
            return $query;
        }

        return $query->where('user_id', Auth::id());
    }
}
