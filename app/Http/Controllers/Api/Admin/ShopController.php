<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ShopProduct;

class ShopController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info($request->all());
        $data = $request->validate([
            'event' => 'required|string|in:created,updated,deleted',
        ]);

        switch ($data['event']) {
            case 'created':
                $this->createShop($request['data']);
                break;

            case 'updated':
                $this->updateShop($request['data']);
                break;

            case 'deleted':
                $this->deleteShop($request['data']['id']);
                break;
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function createShop(array $data)
    {
        unset($data['id']);
        ShopProduct::create($data);
    }

    private function updateShop(array $data)
    {
        // Find the shop to update based on shop_id
        $shop = ShopProduct::where('shop_id', $data['shop_id'])->first();

        if ($shop) {
            // Prepare the update array with null coalescing operator (??) for defaults
            $shopArr = [
                'shop_id' => $data['shop_id'],
            ];
            Log::info('Updating shop with data: ', $shopArr);
            $shop->update($shopArr);

            Log::info('Shop updated successfully.');
        } else {
            Log::error('Shop not found for shop_id: ' . $data['shop_id']);
        }
    }

    private function deleteShop($id)
    {
        $shop = ShopProduct::findOrFail($id);

        Log::info($shop);
        $shop->delete();
    }
}
