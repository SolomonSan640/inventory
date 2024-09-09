<?php

namespace App\Filament\Resources\DamageProductResource\Pages;

use App\Filament\Resources\DamageProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDamageProduct extends ViewRecord
{
    protected static string $resource = DamageProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
