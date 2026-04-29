<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestPanelResource\Pages;
use App\Filament\Admin\Resources\TestPanelResource\RelationManagers;
use App\Models\TestPanel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\Test;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestPanelResource extends Resource
{
    protected static ?string $model = TestPanel::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Panels';
    protected static ?string $navigationGroup = 'Test Setup';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Panel Identity')
                    ->icon('heroicon-o-squares-2x2')
                    ->description('Basic details for this test panel.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Liver Function Test'),

                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('0.00'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Tests Included')
                    ->icon('heroicon-o-beaker')
                    ->description('Select all tests that belong to this panel.')
                    ->schema([
                        Select::make('tests')
                            ->label('Select Tests')
                            ->multiple()
                            ->options(Test::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Display Options')
                    ->icon('heroicon-o-eye')
                    ->description('Control what sections appear on the printed report.')
                    ->schema([
                        Forms\Components\Toggle::make('hide_interpretation')
                            ->label('Hide Interpretation')
                            ->helperText('Suppresses the interpretation block on the report.'),

                        Forms\Components\Toggle::make('hide_method_instrument')
                            ->label('Hide Method & Instrument')
                            ->helperText('Suppresses method and instrument details on the report.'),

                        Forms\Components\Textarea::make('interpretation')
                            ->label('Panel-Level Interpretation')
                            ->placeholder('Optional clinical notes printed at the bottom of this panel...')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Panel Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('hide_interpretation')
                    ->label('Hide Interp.')
                    ->boolean(),

                Tables\Columns\IconColumn::make('hide_method_instrument')
                    ->label('Hide Method')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListTestPanels::route('/'),
            'create' => Pages\CreateTestPanel::route('/create'),
            'edit' => Pages\EditTestPanel::route('/{record}/edit'),
        ];
    }
}
