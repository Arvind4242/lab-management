<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\User;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Subscriptions';
    protected static ?string $pluralLabel = 'Subscriptions';
    protected static ?string $modelLabel = 'Subscription';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Lab & User')
                ->icon('heroicon-o-building-office-2')
                ->description('Assign this subscription to a lab and user.')
                ->schema([
                    Forms\Components\Select::make('lab_id')
                        ->label('Lab')
                        ->relationship('lab', 'name')
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('user_id', null)),

                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->reactive()
                        ->options(function (callable $get) {
                            $labId = $get('lab_id');
                            if (!$labId) {
                                return \App\Models\User::pluck('name', 'id');
                            }
                            return \App\Models\User::where('lab_id', $labId)->pluck('name', 'id');
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $user = \App\Models\User::find($state);
                                if ($user && $user->lab_id) {
                                    $set('lab_id', $user->lab_id);
                                }
                            }
                        }),
                ])
                ->columns(2),

            Forms\Components\Section::make('Plan Details')
                ->icon('heroicon-o-credit-card')
                ->description('Choose the subscription plan, duration, and payment details.')
                ->schema([
                    Forms\Components\Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'free_trial' => 'Free Trial',
                            'monthly' => 'Monthly',
                            'yearly' => 'Yearly',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->prefix('₹')
                        ->placeholder('0.00'),

                    Forms\Components\Select::make('payment_mode')
                        ->label('Mode of Payment')
                        ->options([
                            'cash' => 'Cash',
                            'online' => 'Online',
                            'bank_transfer' => 'Bank Transfer',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active Subscription')
                        ->default(true)
                        ->helperText('Disable to suspend access without deleting the record.'),

                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date')
                        ->required()
                        ->native(false)
                        ->displayFormat('d M Y'),

                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->helperText('Leave blank for open-ended plans.'),
                ])
                ->columns(3),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lab.name')
                    ->label('Lab')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price (₹)')
                    ->money('INR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_mode')
                    ->label('Payment Mode')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin';
    }
}
