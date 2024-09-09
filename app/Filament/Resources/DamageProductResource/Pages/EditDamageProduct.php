<?php

namespace App\Filament\Resources\DamageProductResource\Pages;

use App\Filament\Resources\DamageProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDamageProduct extends EditRecord
{
    protected static string $resource = DamageProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
