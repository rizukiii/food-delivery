<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->label('User')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Product')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required()
                ->prefix('RP'),

            Forms\Components\FileUpload::make('image')
                ->label('Product Image')
                ->directory('cart_images'),

            Forms\Components\DateTimePicker::make('time')
                ->label('Added Time')
                ->required(),

            Forms\Components\Toggle::make('is_exist')
                ->label('Is Exist')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')
                ->label('User')
                ->sortable()
                ->searchable(),

            TextColumn::make('product.name')
                ->label('Product')
                ->sortable()
                ->searchable(),

            TextColumn::make('quantity')
                ->label('Qty')
                ->sortable(),

            TextColumn::make('price')
                ->label('Price')
                ->sortable()
                ->money('IDR'),

            ImageColumn::make('image')
                ->label('Product Image')
                ->square(),

            BooleanColumn::make('is_exist')
                ->label('Is Exist'),
        ])
        ->filters([
            //
        ])
        ->actions([
            //
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
   
}
