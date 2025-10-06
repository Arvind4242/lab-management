<?php

namespace App\Filament\Admin\Resources\ReportResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ReportResult;
use App\Models\ReportTest;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';
    protected static ?string $recordTitleAttribute = 'parameter_name';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // ✅ Dropdown to select which test this result belongs to
          Forms\Components\Select::make('report_test_id')
    ->label('Assign to Test')
    ->options(function ($livewire) {
        $report = $livewire->ownerRecord->report ?? null;

        if (! $report) {
            return [];
        }

        return $report->report_tests
            ->mapWithKeys(fn ($test) => [$test->id => $test->test_name])
            ->toArray();
    })
    ->getOptionLabelFromRecordUsing(fn ($record) => $record->test_name ?? 'N/A')
    ->required()
    ->searchable()
    ->preload(),


            Forms\Components\TextInput::make('parameter_name')
                ->label('Parameter')
                ->required(),

            Forms\Components\TextInput::make('value')
                ->label('Result')
                ->required(),

            Forms\Components\TextInput::make('unit')
                ->label('Unit'),

            Forms\Components\TextInput::make('reference_range')
                ->label('Reference Range'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reportTest.test_name')
                    ->label('Test Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('parameter_name')->label('Parameter'),
                Tables\Columns\TextColumn::make('value')->label('Result'),
                Tables\Columns\TextColumn::make('unit')->label('Unit'),
                Tables\Columns\TextColumn::make('reference_range')->label('Reference Range'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
