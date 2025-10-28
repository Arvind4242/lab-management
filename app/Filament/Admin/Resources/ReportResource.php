<?php

namespace App\Filament\Admin\Resources;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Filament\Admin\Resources\ReportResource\RelationManagers\ResultsRelationManager;
use App\Models\Report;
use App\Models\Test;
use App\Models\Lab;
use App\Models\User;
use App\Models\TestPanel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Reports';
    protected static ?string $pluralModelLabel = 'Reports';
    protected static ?string $modelLabel = 'Report';
    // protected static ?string $navigationGroup = 'Lab Management';





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
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                $tests = $get('tests') ?? [];
                foreach ($tests as &$test) {
                    $test['reference_range'] =
                        $state === 'Female'
                            ? $test['reference_range_female']
                            : ($state === 'Other'
                                ? $test['reference_range_other']
                                : $test['reference_range_male']);
                }
                $set('tests', $tests);
            }),

        Forms\Components\TextInput::make('age')
            ->numeric()
            ->label('Age (Years)'),

        Forms\Components\DatePicker::make('test_date')
            ->required()
            ->label('Test Date')
            ->dehydrated(true),
    ])
    ->columns(2),

    Forms\Components\Section::make('Test Info')
    ->schema([
        Forms\Components\DatePicker::make('test_date')
            ->required()
            ->label('Test Date'),
        Forms\Components\TextInput::make('referred_by')
            ->label('Referred By'),
        Forms\Components\TextInput::make('client_name')
            ->label('Client Name'),
    ])
    ->columns(3),


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
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
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
                                        } else {
                                            $testIds = [];
                                        }
                                    }


                                    \Log::info('Selected panel', [
                                        'panel_id' => $panelId,
                                        'test_ids' => $testIds,
                                    ]);

                                    $tests = Test::whereIn('id', $testIds)->with('unit')->get();
                                } else {
                                    $testId = Str::replaceFirst('test_', '', $state);
                                    $set('test_panel_id', null);
                                    $tests = Test::where('id', $testId)->with('unit')->get();

                                    \Log::info('Selected single test', ['test_id' => $testId]);
                                }

                                \Log::info('Fetched tests', ['tests' => $tests->toArray()]);

                                $set('tests', $tests->map(function ($test) {
                                return [
                                    'test_id' => $test->id,
                                    'test_name' => $test->name,
                                    'unit' => $test->unit?->name ?? '',
                                    'reference_range_male' => $test->default_result ?? '',
                                    'reference_range_female' => $test->default_result_female ?? '',
                                    'reference_range_other' => $test->default_result_other ?? '',
                                    'reference_range' => $test->default_result ?? '',
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
                                    ->dehydrated(true),

                                Forms\Components\TextInput::make('value')
                                    ->label('Result Value')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get, $context) {
                                        $gender = $get('gender') ?? 'Male';
                                        $range = $gender === 'Female'
                                            ? $get('reference_range_female')
                                            : ($gender === 'Other'
                                                ? $get('reference_range_other')
                                                : $get('reference_range_male'));

                                        $set('is_out_of_range', false);

                                        if (is_numeric($state) && preg_match('/(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/', $range, $m)) {
                                            $min = (float) $m[1];
                                            $max = (float) $m[2];
                                            $set('is_out_of_range', $state < $min || $state > $max);
                                        }
                                    }),

                                Forms\Components\TextInput::make('unit')
                                    ->disabled()
                                    ->label('Unit'),

                                Forms\Components\TextInput::make('reference_range')
                                    ->disabled()
                                    ->label('Reference Range'),

                                Forms\Components\Toggle::make('is_out_of_range')
                                    ->label('Out of Range')
                                    ->disabled(),
                            ])
                            ->columns(5)
                            ->default([]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            // 👇 Serial number column
            Tables\Columns\TextColumn::make('serial_number')
                ->label('Sl. No.')
                ->rowIndex()
                ->alignCenter(),

            Tables\Columns\TextColumn::make('patient_name')
                ->label('Patient Name')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('age')->sortable(),
            Tables\Columns\TextColumn::make('gender'),
            Tables\Columns\TextColumn::make('test_date')->date(),
            // Tables\Columns\TextColumn::make('remarks')->limit(30),
            Tables\Columns\TextColumn::make('created_at')->since()->label('Created'),
        ])

  ->filters([
    // 🗑 Optional: if you use soft deletes
    // TrashedFilter::make(),

    // 🧍 Filter by Patient Name
    Tables\Filters\Filter::make('patient_name')
        ->label('Patient Name')
        ->form([
            Forms\Components\TextInput::make('patient_name')
                ->label('Patient Name')
                ->placeholder('Enter patient name...'),
        ])
        ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
            $name = $data['patient_name'] ?? null;
            if ($name) {
                return $query->where('patient_name', 'like', '%' . $name . '%');
            }
            return $query;
        }),

    // 📅 Filter by Date Range
    Tables\Filters\Filter::make('test_date_range')
        ->label('Date Range')
        ->form([
            Forms\Components\DatePicker::make('from')->label('From Date'),
            Forms\Components\DatePicker::make('to')->label('To Date'),
        ])
        ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
            $from = $data['from'] ?? null;
            $to = $data['to'] ?? null;

            if ($from && $to) {
                return $query->whereBetween('test_date', [$from, $to]);
            }
            if ($from) {
                return $query->whereDate('test_date', '>=', $from);
            }
            if ($to) {
                return $query->whereDate('test_date', '<=', $to);
            }
            return $query;
        }),

    // 🚹 Filter by Gender
    Tables\Filters\SelectFilter::make('gender')
        ->label('Gender')
        ->options([
            'Male' => 'Gents',
            'Female' => 'Ladies',
            'Other' => 'Other',
        ])
        ->placeholder('Select Gender'),

    // 👨‍💼 Filter by User (visible only to admin)
    Tables\Filters\SelectFilter::make('user_id')
        ->label('Created By')
        ->options(fn() => \App\Models\User::pluck('name', 'id')->toArray() ?: [])
        ->visible(fn() => auth()->user()?->role === 'admin'),

    // 📆 Quick Day Filters
    Tables\Filters\Filter::make('today')
        ->label("Today's Reports")
        ->query(fn($query) => $query->whereDate('test_date', today())),

    Tables\Filters\Filter::make('yesterday')
        ->label("Yesterday's Reports")
        ->query(fn($query) => $query->whereDate('test_date', today()->subDay())),
])




        ->actions([
            // Tables\Actions\ViewAction::make(),
              Tables\Actions\EditAction::make()->url(fn($record) => route('reports.edit', $record)),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn($record) => route('reports.print', $record))
                ->openUrlInNewTab(),
            Tables\Actions\Action::make('download')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn($record) => route('reports.download', $record)),
        ]);

        // ->bulkActions([
        //     Tables\Actions\DeleteBulkAction::make(),
        // ]);
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();

    if (Auth::user()->role === 'admin') {
        return $query; // show all
    }

    return $query->where('user_id', Auth::id());
}


}
