<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationIcon = 'heroicon-o-user';

    // Optional: default navigation group
    // protected static ?string $navigationGroup = 'Admin';

    /**
     * Change label based on user role
     */
    /**
 * Sidebar label: show "Users" for admins, "Profile" for normal users
 */
public static function getNavigationLabel(): string
{
    return auth()->user()?->role === 'admin' ? 'Users' : 'Profile';
}


    /**
     * Optionally hide the group for normal users
     */
    // public static function getNavigationGroup(): ?string
    // {
    //     return auth()->user()?->role === 'admin' ? 'Admin' : null;
    // }

public static function form(Form $form): Form
{
    $isAdmin = auth()->user()?->role === 'admin';

    return $form
        ->schema([
            Section::make('User Details')
                ->schema([
                    TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->maxLength(255),

                    Select::make('role')
                        ->label('Role')
                        ->options([
                            'admin' => 'Admin',
                            'user' => 'User',
                        ])
                        ->required()
                        ->default('user')
                        ->disabled(!$isAdmin), // only admin can change role
                ])
                ->columns(2),

            Section::make('Lab Information')
                ->schema([
                    Select::make('lab_id')
                        ->label('Select Lab')
                        ->required()
                        ->searchable()
                        ->options(function () use ($isAdmin) {
                            // Admins see all labs
                            if ($isAdmin) {
                                return \App\Models\Lab::pluck('name', 'id');
                            }

                            // Normal users see only their assigned lab
                            $userLabId = auth()->user()?->lab_id;
                            if ($userLabId) {
                                return \App\Models\Lab::where('id', $userLabId)->pluck('name', 'id');
                            }

                            return [];
                        })
                        ->disabled(!$isAdmin && auth()->user()?->lab_id), // editable only if admin or user has no lab yet

                    TextInput::make('lab_code')
                        ->label('Lab Code')
                        ->maxLength(255)
                        ->placeholder('LAB123'),

                    TextInput::make('mobile')
                        ->label('Mobile Number')
                        ->tel()
                        ->maxLength(20),

                    TextInput::make('website')
                        ->label('Website')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://example.com'),

                    Textarea::make('address')
                        ->label('Address')
                        ->rows(2)
                        ->maxLength(500),

                    TextInput::make('reference_lab')
                        ->label('Reference Lab')
                        ->maxLength(255),

                    TextInput::make('qualification')
                        ->label('Qualification')
                        ->maxLength(255),

                    Textarea::make('note')
                        ->label('Note')
                        ->rows(3)
                        ->maxLength(500),
                ])
                ->columns(2),

            Section::make('Uploads')
                ->schema([
                    FileUpload::make('logo')
                        ->label('Lab Logo')
                        ->image()
                        ->directory('logos')
                        ->maxSize(2048)
                        ->imagePreviewHeight('80'),

                    FileUpload::make('digital_signature')
                        ->label('Digital Signature')
                        ->image()
                        ->directory('signatures')
                        ->maxSize(2048)
                        ->imagePreviewHeight('80'),
                ])
                ->columns(2),
        ]);
}


  public static function table(Table $table): Table
{
    $isAdmin = auth()->user()?->role === 'admin';

    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email')->label('Email Address')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('role')->label('Role')->sortable()->searchable(),
            Tables\Columns\ImageColumn::make('logo')->label('Logo'),
            Tables\Columns\ImageColumn::make('digital_signature')->label('Signature'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime('M d, Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime('M d, Y H:i')
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
       ->bulkActions($isAdmin ? [
    Tables\Actions\BulkActionGroup::make([
        Tables\Actions\DeleteBulkAction::make(),
    ]),
] : [])

->emptyStateActions($isAdmin ? [
    Tables\Actions\CreateAction::make(),
] : [])

        ->defaultSort('created_at', 'desc');
}

public static function canCreate(): bool
{
    // Only admins can create users
    return auth()->user()?->role === 'admin';
}


public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    // Admin sees all users
    if (auth()->user()?->role === 'admin') {
        return $query;
    }

    // Normal users see only themselves
    return $query->where('id', auth()->id());
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

// public static function canViewAny(): bool
// {
//     return auth()->user()?->role === 'admin';
// }

// public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
// {
//     $query = parent::getEloquentQuery();

//     if (Auth::user()->role === 'admin') {
//         return $query; // show all
//     }

//     return $query->where('id', Auth::id());
// }


}
