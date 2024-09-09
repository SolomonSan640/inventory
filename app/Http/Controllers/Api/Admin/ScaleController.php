<?php

namespace App\Http\Controllers\Api\Admin;

use Throwable;
use App\Models\Scale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ScaleController extends Controller
{
    public function index()
    {
        app()->setLocale('en');
        $columns = $this->getColumns(strtolower('en'));
        $scales = Scale::select($columns)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $scales], 200);
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
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Scale'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Scale'])], 400);
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
            $scales = Scale::findOrFail($id);
            $scales->fill($data->toArray());
            $scales->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Scale'])], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Scale'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFailed', ['attribute' => 'Scale'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        app()->setLocale('en');
        try {
            $scales = Scale::findOrFail($id);
            $scales->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Scale'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Scale'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFailed', ['attribute' => 'Scale'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];
        $data['name_en'] = $request->name_en;
        $data['name_mm'] = $request->name_mm;
        return new Scale($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_en' => [
                'required',
                Rule::unique('scales')->ignore($id)->whereNull('deleted_at'),
            ],
            'name_mm' => [
                'required_without:name_en',
                'nullable',
                Rule::unique('scales')->ignore($id)->whereNull('deleted_at'),
            ],
        ], [
            'name_en.unique' => __('validation.dataNameUniqueEnglish', ['attribute' => 'Scale']),
            'name_mm.unique' => __('validation.dataNameUniqueMyanmar', ['attribute' => 'Scale']),
            'name_en.required' => __('validation.dataNameRequireEnglish', ['attribute' => 'Scale']),
            'name_mm.required_without' => __('validation.dataNameRequireMyanmar', ['attribute' => 'Scale', 'values' => 'Scale (English)']),
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
            return ['id', 'name_mm'];
        } else {
            return ['id', 'name_en'];
        }
    }
}
