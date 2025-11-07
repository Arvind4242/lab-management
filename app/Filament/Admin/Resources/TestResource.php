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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                // ->unique()
                ->maxLength(255),

            Forms\Components\TextInput::make('short_name')
                ->maxLength(255),

            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required(),

            Forms\Components\Select::make('unit_id')
                ->label('Unit')
                ->relationship('unit', 'name')
                ->required(),

            Forms\Components\Select::make('input_type')
                ->label('Input Type')
                ->options([
                    'Numeric' => 'Numeric',
                    'Single Line' => 'Single Line',
                    'Paragraph' => 'Paragraph',
                ])
                ->required(),

            Forms\Components\Textarea::make('default_result')
                ->label('Default Result Male')
                ->rows(3),

            Forms\Components\Textarea::make('default_result_female')
            ->label('Default Result Female')
            ->rows(3),

            Forms\Components\Textarea::make('default_result_other')
            ->label('Default Result Other')
            ->rows(3),

            Forms\Components\Toggle::make('optional')
                ->label('Optional')
                ->default(false),

            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->suffix('USD'),

            Forms\Components\TextInput::make('method')
                ->label('Method')
                ->maxLength(255),

            Forms\Components\TextInput::make('instrument')
                ->label('Instrument')
                ->maxLength(255),

            Forms\Components\Textarea::make('interpretation')
                ->label('Interpretation')
                ->rows(4),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              // TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('short_name')->limit(50),
                // TextColumn::make('category'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                 Tables\Actions\DeleteAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
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
