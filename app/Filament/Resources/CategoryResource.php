<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Details')->schema([
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
                    ->label('Name (English)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_mm')
                    ->label('Name (Myanmar)')
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
                Tables\Actions\DeleteAction::make()->outlined()->button()->after(function (Category $record) {
                    $webhookUrl = 'https://dev-admin-retail.freshmoe.shop/api/webhook/categories';
                    $payload = [
                        'event' => 'deleted',
                        'data' => $record->toArray(),
                    ];
                    Http::post($webhookUrl, $payload);
                }),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
