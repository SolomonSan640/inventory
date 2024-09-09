<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Models\PurchaseOrder;
use App\Rules\NotZero;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Purchase Order Details')->schema([
                    Forms\Components\Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('order_status_id')
                        ->relationship('orderStatus', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('payment_id')
                        ->relationship('payment', 'payment_type')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\Select::make('currency_id')
                        ->relationship('currency', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('tax')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('grand_total')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->maxLength(255)
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name_en')
                    ->label('Product Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orderStatus.name_en')
                    ->label('Order Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment.payment_type')
                    ->label('Payment Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency.name')
                    ->label('Currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax')
                    ->label('Tax')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Grand Total')
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
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
