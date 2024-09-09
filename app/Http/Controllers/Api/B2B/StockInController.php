<?php

namespace App\Http\Controllers\Api\B2B;

use App\Http\Controllers\Controller;
use App\Models\StockIn;

class StockInController extends Controller
{
    public function index()
    {
        $subQuery = StockIn::select('product_id')
            ->selectRaw('MAX(updated_at) as latest_update')
            ->groupBy('product_id');

        $latestStockIns = StockIn::with('unit', 'product.categoryName', 'product.subCategoryName', 'product.productImage')
            ->joinSub($subQuery, 'latest_stock_ins', function ($join) {
                $join->on('stock_ins.product_id', '=', 'latest_stock_ins.product_id')
                    ->on('stock_ins.updated_at', '=', 'latest_stock_ins.latest_update');
            })
            ->select('stock_ins.id', 'stock_ins.unit_id', 'stock_ins.product_id', 'stock_ins.original_price', 'stock_ins.quantity', 'stock_ins.converted_weight')
            ->orderBy('stock_ins.updated_at', 'desc')
            ->get();

        return response()->json(['status' => 200, 'message' => 'data retrieved successfully', 'data' => $latestStockIns], 200);
    }
}
