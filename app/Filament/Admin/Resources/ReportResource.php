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
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Auth;

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
            Section::make('Patient Details')
                ->columns(3)
                ->schema([
                    TextInput::make('patient_name')->required(),
                    TextInput::make('age')->numeric()->required(),
                    Select::make('gender')
                        ->required()
                        ->options(['Male'=>'Male','Female'=>'Female','Other'=>'Other']),
                    DatePicker::make('test_date')->required(),
                    TextInput::make('referred_by')->required(),
                    TextInput::make('client_name')->required(),
                ]),

           HasManyRepeater::make('report_tests')
    ->relationship('report_tests')
    ->schema([
        Select::make('test_panel_id')->label('Select Panel')->options(TestPanel::pluck('name','id'))->required(),
        Select::make('test_id')->label('Select Test')->options(Test::pluck('name','id'))->required()
            ->afterStateUpdated(fn($state, callable $set) => $set('test_name', Test::find($state)?->name ?? '')),
        TextInput::make('test_name')->hidden(),

        // Repeater::make('results_temp') // temporary repeater
        //     ->label('Test Results')
        //     ->schema([
        //         TextInput::make('parameter_name')->required(),
        //         TextInput::make('value')->required(),
        //         TextInput::make('unit'),
        //         TextInput::make('reference_range'),
        //     ])
        //     ->columns(4)
        //     ->default([])
        //     ->dehydrated(true),
    ])
    ->columns(1),
 ]);
}
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('patient_name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('age')->sortable(),
            Tables\Columns\TextColumn::make('gender'),
            Tables\Columns\TextColumn::make('test_date')->date(),
            Tables\Columns\TextColumn::make('remarks')->limit(30),
            Tables\Columns\TextColumn::make('created_at')->since()->label('Created'),
        ])
         ->filters([
                // SelectFilter::make('lab_id')
                //     ->label('Lab')
                //     ->options(Lab::pluck('name', 'id'))
                //     ->visible(fn() => Auth::user()->role === 'admin'),

                SelectFilter::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id'))
                    ->visible(fn() => Auth::user()->role === 'admin'),

                // SelectFilter::make('report_tests.test_panel_id')
                //     ->label('Test Panel')
                //     ->options(TestPanel::pluck('name', 'id')),
            ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make()->url(fn($record) => route('reports.edit', $record)),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('preview') ->label('Preview') ->icon('heroicon-o-eye') ->url(fn($record) => route('reports.print', $record)) ->openUrlInNewTab(),
            Tables\Actions\Action::make('download') ->label('Download PDF') ->icon('heroicon-o-arrow-down-tray') ->url(fn($record) => route('reports.download', $record)),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();

    if (Auth::user()->role === 'admin') {
        return $query; // show all
    }

    return $query->where('user_id', Auth::id());
}


}
