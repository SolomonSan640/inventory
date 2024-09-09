<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Details')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->directory('/products')
                        ->columnSpanFull()
                        ->image(),
                    Forms\Components\Select::make('country_id')
                        ->relationship('country', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name_en')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('sub_category_id')
                        ->relationship('subCategory', 'name_en')
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name_en')
                        ->label('Name (English)')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\TextInput::make('name_mm')
                        ->label('Name (Myanmar)')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->requiredWithout('name_en'),
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->required(),
                    Section::make()->schema([
                        Forms\Components\Toggle::make('new_item'),
                        Forms\Components\Toggle::make('seasonal'),
                        Forms\Components\Toggle::make('organic'),
                        Forms\Components\Toggle::make('recommended'),
                    ])->columns(4),
                    Forms\Components\Textarea::make('description_en')
                        ->rows(5)
                        ->label('Description (English)'),
                    Forms\Components\Textarea::make('remark_en')
                        ->rows(5)
                        ->label('Remark (English)'),
                    Forms\Components\Textarea::make('description_mm')
                        ->rows(5)
                        ->label('Description (Myanmar)'),
                    Forms\Components\Textarea::make('remark_mm')
                        ->rows(5)
                        ->label('Remark (Myanmar)'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->label('Product Name (English)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name_en')
                    ->label('Category (English)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\IconColumn::make('new_item')
                    ->boolean(),
                Tables\Columns\IconColumn::make('seasonal')
                    ->boolean(),
                Tables\Columns\IconColumn::make('organic')
                    ->boolean(),
                Tables\Columns\IconColumn::make('recommended')
                    ->boolean(),
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
                // Tables\Actions\DeleteAction::make()->outlined()->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
