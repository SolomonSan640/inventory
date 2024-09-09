<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $data = $this->record;
        $this->sendWebhook($data, 'created');
    }

    private function sendWebhook($data, $event)
    {
        $webhookUrl = 'https://dev-admin-retail.freshmoe.shop/api/webhook/categories';

        $payload = [
            'event' => $event,
            'data' => $data->toArray(),
        ];

        Http::post($webhookUrl, $payload);
    }
}
