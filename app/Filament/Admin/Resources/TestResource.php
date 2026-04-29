<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestResource\Pages;
use App\Filament\Admin\Resources\TestResource\RelationManagers;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TestCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationLabel = 'Tests';
    protected static ?string $navigationGroup = 'Test Setup';
    protected static ?int $navigationSort = 10;



public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Test Identity')
                ->icon('heroicon-o-beaker')
                ->description('Basic identification details for this test.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Haemoglobin'),

                    Forms\Components\TextInput::make('short_name')
                        ->maxLength(255)
                        ->placeholder('e.g. HGB'),

                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('test_group')
                        ->label('Test Group')
                        ->maxLength(255)
                        ->placeholder('e.g. Complete Blood Count'),

                    Forms\Components\Select::make('unit_id')
                        ->label('Unit')
                        ->relationship('unit', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('input_type')
                        ->label('Input Type')
                        ->options([
                            'Numeric' => 'Numeric',
                            'Single Line' => 'Single Line',
                            'Paragraph' => 'Paragraph',
                        ])
                        ->required()
                        ->native(false),
                ])
                ->columns(2),

            Forms\Components\Section::make('Reference Ranges')
                ->icon('heroicon-o-chart-bar-square')
                ->description('Normal value ranges by gender — used for automatic out-of-range detection in reports.')
                ->schema([
                    Forms\Components\Textarea::make('default_result')
                        ->label('Normal Range (Male)')
                        ->placeholder('e.g. 13.0 - 17.0 g/dL')
                        ->rows(2),

                    Forms\Components\Textarea::make('default_result_female')
                        ->label('Normal Range (Female)')
                        ->placeholder('e.g. 12.0 - 15.0 g/dL')
                        ->rows(2),

                    Forms\Components\Textarea::make('default_result_other')
                        ->label('Normal Range (Other)')
                        ->placeholder('e.g. 12.0 - 16.0 g/dL')
                        ->rows(2),
                ])
                ->columns(3),

            Forms\Components\Section::make('Advanced Details')
                ->icon('heroicon-o-cog-6-tooth')
                ->description('Pricing, methodology, and reporting preferences.')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->prefix('₹')
                        ->placeholder('0.00'),

                    Forms\Components\TextInput::make('method')
                        ->label('Method')
                        ->maxLength(255)
                        ->placeholder('e.g. Colorimetry'),

                    Forms\Components\TextInput::make('instrument')
                        ->label('Instrument')
                        ->maxLength(255)
                        ->placeholder('e.g. Sysmex XN-1000'),

                    Forms\Components\Toggle::make('optional')
                        ->label('Optional Test')
                        ->helperText('Optional tests can be excluded from panels.')
                        ->default(false),

                    Forms\Components\Textarea::make('interpretation')
                        ->label('Interpretation / Clinical Notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Test Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('short_name')
                    ->label('Short Name')
                    ->badge()
                    ->color('gray')
                    ->limit(20),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->badge()
                    ->color('success'),

                TextColumn::make('input_type')
                    ->label('Input Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Numeric' => 'primary',
                        'Single Line' => 'warning',
                        'Paragraph' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('price')
                    ->label('Price')
                    ->money('INR')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
