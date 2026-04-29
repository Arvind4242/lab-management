<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LabResource\Pages;
use App\Filament\Admin\Resources\LabResource\RelationManagers;
use App\Models\Lab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\TextColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabResource extends Resource
{
    protected static ?string $model = Lab::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Labs';
    protected static ?string $pluralLabel = 'Labs';
    protected static ?string $modelLabel = 'Lab';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Lab Details')
                    ->icon('heroicon-o-building-office-2')
                    ->description('Enter the laboratory\'s basic contact information.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. City Diagnostics Centre'),

                        TextInput::make('phone')
                            ->maxLength(20)
                            ->tel()
                            ->placeholder('e.g. +91 98765 43210'),

                        TextInput::make('address')
                            ->maxLength(255)
                            ->placeholder('e.g. 12 Main Street, Mumbai')
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
                    ->label('Lab Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('phone')
                    ->label('Phone'),
                TextColumn::make('address')
                    ->label('Address')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLabs::route('/'),
            'create' => Pages\CreateLab::route('/create'),
            'edit' => Pages\EditLab::route('/{record}/edit'),

        ];
    }

    public static function canViewAny(): bool
{
    return auth()->user()?->role === 'admin';
}

}
