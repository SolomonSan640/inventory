<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopProductResource\Pages;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShopProductResource extends Resource
{
    protected static ?string $model = ShopProduct::class;
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Product';
    public static function getLabel(): ?string
    {
        return 'Product';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Shop Details')->schema([
                    Forms\Components\TextInput::make('shop_id')
                        ->label('Shop ID')
                        ->maxLength(255)
                        ->disabled()
                        ->required(),
                    Forms\Components\Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->options(Warehouse::all()->pluck('name_en', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('product_id')
                        ->label('Product')
                        ->options(Product::all()->pluck('name_en', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shop_id')
                    ->label('Shop ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouse.name_en')
                    ->label('Warehouse')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name_en')
                    ->label('Product')
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
            'index' => Pages\ListShopProducts::route('/'),
            'create' => Pages\CreateShopProduct::route('/create'),
            'view' => Pages\ViewShopProduct::route('/{record}'),
            'edit' => Pages\EditShopProduct::route('/{record}/edit'),
        ];
    }
}
