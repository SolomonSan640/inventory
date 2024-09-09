<?php

namespace App\Filament\Resources\StockInResource\Pages;

use App\Models\Unit;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockInResource;

class CreateStockIn extends CreateRecord
{
    protected static string $resource = StockInResource::class;

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
        $webhookUrl = 'https://dev-admin-retail.freshmoe.shop/api/webhook/products';
        $payload = [
            'event' => $event,
            'data' => [
                'product' => $data->productName,
                'original_price' => $data['original_price'],
                'quantity' => $data['quantity'],
                'converted_weight' => $data['converted_weight'],
                'unit' => $data->unitName,
                'id' => $data['id'],
            ],
        ];

        Http::post($webhookUrl, $payload);
    }
}
