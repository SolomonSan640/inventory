<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Location';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Country Details')->schema([
                    Forms\Components\TextInput::make('name_en')
                        ->unique(ignoreRecord: true)
                        ->label('Name (English)')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->required(),
                    Forms\Components\TextInput::make('name_mm')
                        ->unique(ignoreRecord: true)
                        ->label('Name (Myanmar)')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
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
                    Forms\Components\Toggle::make('is_show')
                        ->default(1)
                        ->required(),
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
                Tables\Columns\IconColumn::make('is_show')
                    ->label('Show')
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'view' => Pages\ViewCountry::route('/{record}'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
