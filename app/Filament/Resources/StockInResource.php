<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockInResource\Pages;
use App\Models\StockIn;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockInResource extends Resource
{
    protected static ?string $model = StockIn::class;

    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Stock In Details')->schema([
                    Forms\Components\Select::make('warehouse_id')
                        ->relationship('warehouse', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('original_price')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('quantity')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('sub_total')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('grand_total')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('convert_weight')
                        ->label('Convert Weight')
                        ->regex('/^[1-9]\d*$/')
                        ->default(1)
                        ->minValue(1)
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            // Convert the weight based on the selected unit
                            $unit = $get('unit_id');
                            if ($unit == 1) {
                                $convertedValue = $state;
                            } else if ($unit == 2) {
                                $convertedValue = ($state * 1000);
                            }
                            $set('converted_weight', $convertedValue);
                        }),
                    Forms\Components\Select::make('unit_id')
                        ->label('Unit')
                        ->options(Unit::all()->pluck('name_en', 'id'))
                        ->preload()
                        ->default(1)
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            // dd($state);
                            $weight = $get('convert_weight');
                            if ($state == '1') {
                                $convertedValue = $weight;
                            } else if ($state == '2') {
                                $convertedValue = ($weight * 1000);
                            }
                            $set('converted_weight', $convertedValue);
                        }),
                    Forms\Components\TextInput::make('converted_weight')
                        ->label('Converted Weight')
                        ->regex('/^[1-9]\d*$/')
                        ->required()
                        ->readonly(),
                    Forms\Components\Select::make('scale_id')
                        ->relationship('scale', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('volume')
                        ->minValue(0)
                        ->regex('/^[1-9]\d*$/')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('line')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('level')
                        ->maxLength(255)
                        ->regex('/^[1-9]\d*$/')
                        ->required(),
                    Forms\Components\TextInput::make('stand')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('green')
                        ->required()
                        ->regex('/^[1-9]\d*$/')
                        ->minValue(0)
                        ->numeric(),
                    Forms\Components\TextInput::make('yellow')
                        ->required()
                        ->regex('/^[1-9]\d*$/')
                        ->minValue(0)
                        ->numeric(),
                    Forms\Components\TextInput::make('red')
                        ->required()
                        ->regex('/^[1-9]\d*$/')
                        ->minValue(0)
                        ->numeric(),
                    Forms\Components\Textarea::make('remark_en')
                        ->rows(5)
                        ->columnSpanFull()
                        ->label('Remark (English)'),
                    Forms\Components\Textarea::make('remark_mm')
                        ->rows(5)
                        ->columnSpanFull()
                        ->label('Remark (Myanmar)'),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warehouse.name_en')
                    ->label('Warehouse Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name_en')
                    ->label('Product Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.sku')
                    ->label('Product SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_price')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('product.country.name_en')
                //     ->label('Product Country')
                //     ->searchable(),
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
            'index' => Pages\ListStockIns::route('/'),
            'create' => Pages\CreateStockIn::route('/create'),
            'view' => Pages\ViewStockIn::route('/{record}'),
            'edit' => Pages\EditStockIn::route('/{record}/edit'),
        ];
    }
}
