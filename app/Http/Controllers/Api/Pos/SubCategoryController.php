<?php

namespace App\Http\Controllers\Api\Pos;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('categoryName', 'subCategoryImage')->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => 'data retrieved successfully', 'data' => $subCategories], 200);
    }
}
