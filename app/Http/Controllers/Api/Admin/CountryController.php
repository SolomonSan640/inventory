<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class CountryController extends Controller
{
    public function index()
    {
        app()->setLocale('en');
        $columns = $this->getColumns(strtolower('en'));
        $countries = Country::select($columns)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $countries], 200);
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
            DB::commit();
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Country'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Country'])], 400);
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
            $countries = Country::findOrFail($id);
            $countries->fill($data->toArray());
            $countries->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Country'])], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Country'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFailed', ['attribute' => 'Country'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        app()->setLocale('en');
        try {
            $countries = Country::findOrFail($id);
            $countries->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Country'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Country'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFailed', ['attribute' => 'Country'])], 400);
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
        $data['code'] = $request->code;

        return new Country($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => [
                'required',
                Rule::unique('countries')->whereNull('deleted_at')->ignore($id),
            ],
            'name_mm' => [
                'required_without:name_en',
                'nullable',
                Rule::unique('countries')->whereNull('deleted_at')->ignore($id),
            ],
            'code' => [
                'required',
                Rule::unique('countries')->whereNull('deleted_at')->ignore($id),
            ],
        ], [
            'name_en.unique' => __('validation.dataNameUniqueEnglish', ['attribute' => 'Country']),
            'name_mm.unique' => __('validation.dataNameUniqueMyanmar', ['attribute' => 'Country']),
            'name_en.required' => __('validation.dataNameRequireEnglish', ['attribute' => 'Country']),
            'name_mm.required_without' => __('validation.dataNameRequireMyanmar', ['attribute' => 'Country', 'values' => 'Country (English)']),
            'code.required' => __('validation.dataNameRequire', ['attribute' => 'Country']),
            'code.unique' => __('validation.dataNameUnique', ['attribute' => 'Country']),
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
            return ['id', 'name_mm', 'code', 'description_mm', 'remark_mm'];
        } else {
            return ['id', 'name_en', 'code', 'description_en', 'remark_en'];
        }
    }
}
