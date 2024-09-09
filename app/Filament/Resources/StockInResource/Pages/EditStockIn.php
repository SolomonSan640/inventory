<?php

namespace App\Filament\Resources\StockInResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Http;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StockInResource;

class EditStockIn extends EditRecord
{
    protected static string $resource = StockInResource::class;

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
