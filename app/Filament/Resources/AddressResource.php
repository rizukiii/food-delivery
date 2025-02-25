<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->label('User')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('address_type')
                ->label('Address Type')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('contact_person_name')
                ->label('Contact Person Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('contact_person_number')
                ->label('Contact Person Number')
                ->tel()
                ->required()
                ->maxLength(20),

            Forms\Components\Textarea::make('address')
                ->label('Full Address')
                ->required()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('latitude')
                ->label('Latitude')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('longitude')
                ->label('Longitude')
                ->numeric()
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

            TextColumn::make('address_type')
                ->label('Type')
                ->sortable()
                ->searchable(),

            TextColumn::make('contact_person_name')
                ->label('Contact Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('contact_person_number')
                ->label('Contact Number')
                ->sortable(),

            TextColumn::make('address')
                ->label('Address')
                ->limit(50),

            TextColumn::make('latitude')
                ->label('Lat')
                ->sortable(),

            TextColumn::make('longitude')
                ->label('Lng')
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
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
