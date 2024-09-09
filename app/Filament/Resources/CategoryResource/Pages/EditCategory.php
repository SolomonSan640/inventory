<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Http;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

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

    protected function afterSave(): void
    {
        $data = $this->record;
        $this->sendWebhook($data, 'updated');
    }

    private function sendWebhook($data, $event)
    {
        $webhookUrl = 'https://dev-admin-retail.freshmoe.shop/api/webhook/categories';
        $payload = [
            'event' => $event,
            'data' => $data->toArray()
        ];
        Http::post($webhookUrl, $payload);
    }
}
