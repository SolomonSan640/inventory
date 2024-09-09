<?php

namespace App\Http\Controllers\Api\B2B;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('categoryName', 'subCategoryImage')->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => 'data retrieved successfully', 'data' => $subCategories], 200);
    }
}
