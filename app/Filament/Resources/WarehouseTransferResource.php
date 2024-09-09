<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseTransferResource\Pages;
use App\Models\WarehouseTransfer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WarehouseTransferResource extends Resource
{
    protected static ?string $model = WarehouseTransfer::class;

    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Warehouse';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Warehouse Transfer Details')->schema([
                    Forms\Components\Select::make('from_warehouse_id')
                        ->relationship('fromWarehouse', 'name_en')
                        ->label('Warehouse From')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('to_warehouse_id')
                        ->relationship('toWarehouse', 'name_en')
                        ->label('Warehouse To')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('product_id')
                        ->relationship('stockOut.product', 'name_en')
                        ->label('Product')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('quantity')
                        ->maxLength(255)
                        ->minValue(0)
                        ->numeric()
                        ->regex('/^[1-9]\d*$/')
                        ->label("Product's Quantity")
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromWarehouse.name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toWarehouse.name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stockOut.product.name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->outlined()->button(),
                Tables\Actions\EditAction::make()->outlined()->button(),
                Tables\Actions\DeleteAction::make()->outlined()->button(),
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
            'index' => Pages\ListWarehouseTransfers::route('/'),
            'create' => Pages\CreateWarehouseTransfer::route('/create'),
            'view' => Pages\ViewWarehouseTransfer::route('/{record}'),
            'edit' => Pages\EditWarehouseTransfer::route('/{record}/edit'),
        ];
    }
}
