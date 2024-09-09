<?php

namespace App\Http\Controllers\Api\B2B;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('categoryImage')->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => 'data retrieved successfully', 'data' => $categories], 200);
    }
}
