<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DamageProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DamageProductController extends Controller
{
    public function index()
    {
        // $this->setLocale(strtolower($country));
        app()->setLocale('en');
        $columns = $this->getColumns();
        if (strtolower('en') === "mm") {
            $damages = DamageProduct::with('stockIn.ProductMm', 'stockOut.ProductMm')->select($columns)->orderBy('updated_at', 'desc')->get();
        } else {
            $damages = DamageProduct::with('stockIn.ProductEn', 'stockOut.ProductEn')->select($columns)->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $damages], 200);
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
            DB::commit();
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Damage Product'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Damage Product'])], 400);
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
            $damages = DamageProduct::findOrFail($id);
            $damages->fill($data->toArray());
            $damages->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Damage Product'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Damage Product'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFail', ['attribute' => 'Damage Product'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // $decryptId = decrypt($id);
            $damages = DamageProduct::findOrFail($id);
            $damages->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Damage Product'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Damage Product'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFail', ['attribute' => 'Damage Product'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['stock_in_id'] = $request->stock_in_iden;
        $data['stock_out_id'] = $request->stock_out_id;

        $data['quantity'] = $request->quantity;
        $data['level'] = $request->level;

        return new DamageProduct($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock_in_id' => [
                'required_without:stock_out_id',
            ],
            'stock_out_id' => [
                'required_without:stock_in_id',
                'nullable',
            ],
            'quantity' => [
                'required',
            ],
            'level' => [
                'required',
            ],
        ], [
            'stock_in_id.required_without' => __('validation.dataNameRequire', ['attribute' => 'Damage Product', 'values' => 'Damage Product']),
            'stock_out_id.required_without' => __('validation.dataNameRequire', ['attribute' => 'Damage Product', 'values' => 'Damage Product']),
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

    private function getColumns()
    {
        return ['id', 'stock_in_id', 'stock_out_id', 'quantity', 'level'];
    }
}
