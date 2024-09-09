<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScaleResource\Pages;
use App\Models\Scale;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScaleResource extends Resource
{
    protected static ?string $model = Scale::class;

    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Symbol';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scale Details')->schema([
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
            'index' => Pages\ListScales::route('/'),
            'create' => Pages\CreateScale::route('/create'),
            'view' => Pages\ViewScale::route('/{record}'),
            'edit' => Pages\EditScale::route('/{record}/edit'),
        ];
    }
}
