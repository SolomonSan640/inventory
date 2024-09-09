<?php

namespace App\Filament\Resources\DamageProductResource\Pages;

use App\Filament\Resources\DamageProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDamageProduct extends CreateRecord
{
    protected static string $resource = DamageProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
