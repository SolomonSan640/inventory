<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\Product;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class ProductController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        app()->setLocale('en');
        // $this->setLocale(strtolower($country));
        $columns = $this->getColumns(strtolower('en'));
        if (strtolower('en') === "mm") {
            $products = Product::with('categoryMm', 'subCategoryMm', 'countryMm', 'productImage')->select($columns)->orderBy('updated_at', 'desc')->get();
        } else {
            $products = Product::with('categoryEn', 'subCategoryEn', 'countryEn', 'productImage')->select($columns)->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $products], 200);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $this->setLocale(strtolower($request->country));
        $validationResult = $this->validateCreateData($request, null);
        if ($validationResult !== null) {
            return $validationResult;
        }
        try {
            $data = $this->getCreateData($request);
            $data->fill($data->toArray());
            $data->save();

            $folderName = 'products';
            $imageFileName = $this->singleImage($request, 'image', $folderName); // use trait to upload image

            if (!$imageFileName) {
                return response()->json(['error' => 'Image upload failed'], 400);
            }

            GeneralImage::create([
                'product_id' => $data->id,
                'name' => "Product Image",
                'file_path' => $imageFileName,
            ]);

            DB::commit();
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Product'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Product'])], 400);
        }
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $this->setLocale(strtolower($request->country));
        // $decryptId = decrypt($id);
        $validationResult = $this->validateCreateData($request, $id);
        if ($validationResult !== null) {
            return $validationResult;
        }
        try {
            $data = $this->getCreateData($request);
            $products = Product::findOrFail($id);
            $productId = $products->id;
            $products->fill($data->toArray());
            $products->update();

            $folderName = 'products';
            $imageFileName = $this->singleImage($request, 'image', $folderName);

            if (!$imageFileName) {
                return response()->json(['error' => 'Image upload failed'], 400);
            }

            $generalImage = GeneralImage::where('product_id', $productId)->get();
            if ($generalImage->isNotEmpty()) {
                foreach ($generalImage as $image) {
                    $image->update([
                        'file_path' => $imageFileName,
                    ]);
                }
            } else {
                GeneralImage::create([
                    'product_id' => $productId,
                    'file_path' => $imageFileName,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Product'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Product'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFail', ['attribute' => 'Product'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // $decryptId = decrypt($id);
            $products = Product::findOrFail($id);
            $products->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Product'])], 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeleted', ['attribute' => 'Product'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['name_en'] = $request->name_en;
        $data['name_mm'] = $request->name_mm;

        $data['description_en'] = $request->description_en;
        $data['description_mm'] = $request->description_mm;

        $data['remark_en'] = $request->remark_en;
        $data['remark_mm'] = $request->remark_mm;

        $data['category_id'] = $request->category_id;
        $data['sub_category_id'] = $request->sub_category_id;
        $data['country_id'] = $request->country_id;
        $data['sku'] = $request->sku;

        return new Product($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'name_en' => [
                'required',
                Rule::unique('products')
                    ->where(function ($query) use ($request) {
                        return $query->where('country_id', $request->country_id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($id),
            ],
            'name_mm' => [
                'required_without:name_en',
                Rule::unique('products')
                    ->where(function ($query) use ($request) {
                        return $query->where('country_id', $request->country_id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($id),
            ],
            'sku' => [
                'required',
                Rule::unique('products')->ignore($id)->whereNUll('deleted_at'),
            ],
            'category_id' => 'required',
        ], [
            'name_en.unique' => __('validation.dataNameUniqueEnglish', ['attribute' => 'Product']),
            'name_mm.unique' => __('validation.dataNameUniqueMyanmar', ['attribute' => 'Product']),
            'name_en.required' => __('validation.dataNameRequireEnglish', ['attribute' => 'Product']),
            'name_mm.required_without' => __('validation.dataNameRequireMyanmar', ['attribute' => 'Product', 'values' => 'Product (English)']),
            'category_id.required' => __('validation.dataSelect', ['attribute' => 'Category']),
            'country_id.required' => __('validation.dataSelect', ['attribute' => 'Country']),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return null;
    }

    private function setLocale($country)
    {
        $supportedLocales = ['en', 'mm'];
        if (in_array($country, $supportedLocales)) {
            app()->setLocale($country);
        } else {
            app()->setLocale('en');
        }
    }

    private function getColumns($country)
    {
        if ($country == 'mm') {
            return ['id', 'category_id', 'sub_category_id', 'country_id', 'name_mm', 'sku', 'description_mm', 'remark_mm'];
        } else {
            return ['id', 'category_id', 'sub_category_id', 'country_id', 'name_en', 'sku', 'description_en', 'remark_en'];
        }
    }
}
