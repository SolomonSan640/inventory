<?php

namespace App\Http\Controllers\Api\Pos;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productImage')->select('id', 'name_en', 'name_mm', 'original_price', 'quantity')->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => 'data retrieved successfully', 'data' => $products], 200);
    }
}
