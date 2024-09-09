<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DamageProductResource\Pages;
use App\Models\DamageProduct;
use App\Models\StockIn;
use App\Models\StockOut;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DamageProductResource extends Resource
{
    protected static ?string $model = DamageProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sub Category Details')->schema([
                    Forms\Components\Select::make('stock_in_id')
                        ->label('StockIn Product Name')
                        ->options(StockIn::all()->pluck('product.name_en', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('stock_out_id')
                        ->label('StockOut Product Name')
                        ->options(StockOut::all()->pluck('product.name_en', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('quantity')
                        ->regex('/^[1-9]\d*$/')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('level')
                        ->label("Product's Damage Level")
                        ->maxLength(255),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stockIn.product.name_en')
                    ->label('StockIn Product Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stockOut.product.name_en')
                    ->label('StockOut Product Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label("Product's Damage Level")
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
            'index' => Pages\ListDamageProducts::route('/'),
            'create' => Pages\CreateDamageProduct::route('/create'),
            'view' => Pages\ViewDamageProduct::route('/{record}'),
            'edit' => Pages\EditDamageProduct::route('/{record}/edit'),
        ];
    }
}
