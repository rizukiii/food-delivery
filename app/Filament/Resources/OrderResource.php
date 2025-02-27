<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->label('User')
                ->searchable()
                ->required(),

            TextInput::make('order_amount')
                ->numeric()
                ->required()
                ->prefix('RP'),

            TextInput::make('order_payment')
                ->label('Payment Method')
                ->required(),

            Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                ])
                ->required(),

            Select::make('order_status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

            TextInput::make('total_tax_amount')
                ->numeric()
                ->prefix('RP'),

            TextInput::make('delivery_charge')
                ->numeric()
                ->prefix('RP'),

            TextInput::make('order_note')
                ->maxLength(255),

            DateTimePicker::make('schedule_at')
                ->label('Scheduled At'),

            TextInput::make('otp')
                ->numeric()
                ->maxLength(6),

            Toggle::make('refund_requested')
                ->label('Refund Requested'),

            Toggle::make('refunded')
                ->label('Refunded'),

            Toggle::make('scheduled')
                ->label('Scheduled'),

            TextInput::make('details_count')
                ->numeric(),

            Select::make('delivery_address_id')
                ->relationship('deliveryAddress', 'address')
                ->label('Delivery Address')
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')
                ->label('User')
                ->sortable()
                ->searchable(),

            TextColumn::make('order_amount')
                ->label('Order Amount')
                ->sortable()
                ->money('IDR'),

            TextColumn::make('order_payment')
                ->label('Payment Method')
                ->sortable(),

            TextColumn::make('payment_status')
                ->sortable(),

            TextColumn::make('order_status')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
