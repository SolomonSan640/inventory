<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GeneralImage;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class CategoryController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        app()->setLocale('en');
        $columns = $this->getColumns(strtolower('en'));
        $categories = Category::with('categoryImage')->select($columns)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $categories], 200);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $this->setLocale(strtolower('en'));
        $validationResult = $this->validateCreateData($request, null);
        if ($validationResult !== null) {
            return $validationResult;
        }
        try {
            $data = $this->getCreateData($request);
            $data->fill($data->toArray());
            $data->save();

            $folderName = 'categories';
            $imageFileName = $this->singleImage($request, 'image', $folderName); // use trait to upload image

            if ($imageFileName) {
                GeneralImage::create([
                    'category_id' => $data->id,
                    'name' => "Category Image",
                    'file_path' => $imageFileName,
                ]);
            }
            $this->sendWebhook($data, 'created');
            DB::commit();
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Category'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Category'])], 400);
        }
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        // $this->setLocale(strtolower($request->country));
        app()->setLocale('en');
        $validationResult = $this->validateCreateData($request, $id);
        if ($validationResult !== null) {
            return $validationResult;
        }
        try {
            $data = $this->getCreateData($request);
            $categories = Category::findOrFail($id);
            $categories->fill($data->toArray());
            $categoryId = $categories->id;
            $categories->update();

            $folderName = 'categories';
            $imageFileName = $this->singleImage($request, 'image', $folderName);

            $generalImage = GeneralImage::where('category_id', $categoryId)->get();

            if ($generalImage) {
                foreach ($generalImage as $image) {
                    $image->update([
                        'category_id' => $categoryId,
                        'name' => "Updated Category Image",
                        'file_path' => $imageFileName,
                    ]);
                }
            } else if ($imageFileName) {
                GeneralImage::create([
                    'category_id' => $categoryId,
                    'file_path' => $imageFileName,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Category'])], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Category'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFailed', ['attribute' => 'Category'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        app()->setLocale('en');
        try {
            $categories = Category::findOrFail($id);
            $images = GeneralImage::where('category_id', $categories->id)->get();
            foreach ($images as $image) {
                $image->delete();
            }
            $categories->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Category'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Category'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFailed', ['attribute' => 'Category'])], 400);
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

        return new Category($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => [
                'required',
                Rule::unique('categories')->ignore($id)->whereNull('deleted_at'),
            ],
            'name_mm' => [
                'required_without:name_en',
                'nullable',
                Rule::unique('categories')->ignore($id)->whereNull('deleted_at'),
            ],
        ], [
            'name_en.unique' => __('validation.dataNameUniqueEnglish', ['attribute' => 'Category']),
            'name_mm.unique' => __('validation.dataNameUniqueMyanmar', ['attribute' => 'Category']),
            'name_en.required' => __('validation.dataNameRequireEnglish', ['attribute' => 'Category']),
            'name_mm.required_without' => __('validation.dataNameRequireMyanmar', ['attribute' => 'Category', 'values' => 'Category (English)']),
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
            return ['id', 'name_mm', 'description_mm', 'remark_mm'];
        } else {
            return ['id', 'name_en', 'description_en', 'remark_en'];
        }
    }

    private function sendWebhook($data, $event)
    {
        // URL of the receiving API webhook endpoint
        $webhookUrl = 'https://dev-admin-retail.freshmoe.shop/api/webhook/categories';

        // Payload to send to the receiving API
        $payload = [
            'event' => $event,
            'data' => $data->toArray(),
        ];
        // Send the webhook using HTTP client
        Http::post($webhookUrl, $payload);
    }
}
