<?php

namespace App\Filament\Resources\ScaleResource\Pages;

use App\Filament\Resources\ScaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewScale extends ViewRecord
{
    protected static string $resource = ScaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
