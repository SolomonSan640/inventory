<?php

namespace App\Filament\Resources\ShopProductResource\Pages;

use App\Filament\Resources\ShopProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShopProduct extends ViewRecord
{
    protected static string $resource = ShopProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
