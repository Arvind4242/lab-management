<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestPackageResource\Pages;
use App\Models\Test;
use App\Models\TestPackage;
use App\Models\TestPanel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestPackageResource extends Resource
{
    protected static ?string $model = TestPackage::class;

    // protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('fee')
                    ->label('Fee')
                    ->numeric()
                    ->nullable(),

                Forms\Components\Select::make('gender')
                    ->label('Bill only for gender')
                    ->options([
                        'Both' => 'Both',
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->default('Both')
                    ->required(),

                Forms\Components\MultiSelect::make('tests')
                    ->relationship('tests', 'name')
                    ->preload()
                    ->label('Tests')
                    ->placeholder('Search tests'),

                Forms\Components\MultiSelect::make('panels')
                    ->relationship('panels', 'name')
                    ->preload()
                    ->label('Panels')
                    ->placeholder('Search panels'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('fee'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('created_at')->label('Created')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestPackages::route('/'),
            'create' => Pages\CreateTestPackage::route('/create'),
            'edit' => Pages\EditTestPackage::route('/{record}/edit'),
        ];
    }
}
