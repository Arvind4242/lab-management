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

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Packages';
    protected static ?string $navigationGroup = 'Test Setup';
    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Details')
                    ->icon('heroicon-o-cube')
                    ->description('Define the package name, fee, and gender applicability.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Complete Health Checkup'),

                        Forms\Components\TextInput::make('fee')
                            ->label('Package Fee')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('0.00'),

                        Forms\Components\Select::make('gender')
                            ->label('Applicable Gender')
                            ->helperText('Restrict billing to a specific gender, or select Both.')
                            ->options([
                                'Both' => 'Both',
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])
                            ->default('Both')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Included Tests & Panels')
                    ->icon('heroicon-o-beaker')
                    ->description('Select the individual tests and panels bundled in this package.')
                    ->schema([
                        Forms\Components\Select::make('tests')
                            ->relationship('tests', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Individual Tests')
                            ->placeholder('Search and select tests...'),

                        Forms\Components\Select::make('panels')
                            ->relationship('panels', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Test Panels')
                            ->placeholder('Search and select panels...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Package Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('fee')
                    ->label('Fee')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',
                        'Female' => 'success',
                        'Both' => 'primary',
                        default => 'gray',
                    }),

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

    public static function canViewAny(): bool
{
    return auth()->user()?->role === 'admin';
}

}
