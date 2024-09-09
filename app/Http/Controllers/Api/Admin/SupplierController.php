<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralImage;
use App\Models\Supplier;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class SupplierController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        app()->setLocale('en');
        $suppliers = Supplier::with('image')->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $suppliers], 200);
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

            $folderName = 'suppliers';
            $imageFileName = $this->singleImage($request, 'image', $folderName); // use trait to upload image

            if ($imageFileName) {
                GeneralImage::create([
                    'supplier_id' => $data->id,
                    'name' => "Supplier Image",
                    'file_path' => $imageFileName,
                ]);
            }
            DB::commit();
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Supplier'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Supplier'])], 400);
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
            $suppliers = Supplier::findOrFail($id);
            $suppliers->fill($data->toArray());
            $supplierId = $suppliers->id;
            $suppliers->update();

            $folderName = 'suppliers';
            $imageFileName = $this->singleImage($request, 'image', $folderName);

            $generalImage = GeneralImage::where('supplier_id', $supplierId)->get();

            if ($generalImage) {
                foreach ($generalImage as $image) {
                    $image->update([
                        'supplier_id' => $supplierId,
                        'name' => "Updated Supplier Image",
                        'file_path' => $imageFileName,
                    ]);
                }
            } else if ($imageFileName) {
                GeneralImage::create([
                    'supplier_id' => $supplierId,
                    'file_path' => $imageFileName,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Supplier'])], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Supplier'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFailed', ['attribute' => 'Supplier'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        app()->setLocale('en');
        try {
            $suppliers = Supplier::findOrFail($id);
            $images = GeneralImage::where('supplier_id', $suppliers->id)->get();
            foreach ($images as $image) {
                $image->delete();
            }
            $suppliers->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Supplier'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Supplier'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFailed', ['attribute' => 'Supplier'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        return new Supplier($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('suppliers')->ignore($id)->whereNull('deleted_at'),
            ],
            'phone' => [
                'required',
                'exists:users,phone',
                'regex:/^09\d{9}$/',
                Rule::unique('suppliers')->ignore($id)->whereNull('deleted_at'),
            ],
            'address' => 'required',
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
}
