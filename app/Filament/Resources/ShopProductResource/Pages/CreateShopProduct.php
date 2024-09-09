<?php

namespace App\Filament\Resources\ShopProductResource\Pages;

use App\Models\Product;
use App\Models\Warehouse;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ShopProductResource;

class CreateShopProduct extends CreateRecord
{
    protected static string $resource = ShopProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
