<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDetailResource\Pages;
use App\Models\OrderDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class OrderDetailResource extends Resource
{
    protected static ?string $model = OrderDetail::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('order_id')
                ->relationship('order', 'id')
                ->label('Order ID')
                ->searchable()
                ->required(),

            Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Product')
                ->searchable()
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->required(),

            TextInput::make('price')
                ->numeric()
                ->prefix('RP')
                ->required(),

            TextInput::make('total_price')
                ->numeric()
                ->prefix('RP')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order.id')
                ->label('Order ID')
                ->sortable()
                ->searchable(),

            TextColumn::make('product.name')
                ->label('Product')
                ->sortable()
                ->searchable(),

            TextColumn::make('quantity')
                ->sortable(),

            TextColumn::make('price')
                ->money('IDR')
                ->sortable(),

            TextColumn::make('total_price')
                ->money('IDR')
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
            'index' => Pages\ListOrderDetails::route('/'),
            'create' => Pages\CreateOrderDetail::route('/create'),
            'edit' => Pages\EditOrderDetail::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
