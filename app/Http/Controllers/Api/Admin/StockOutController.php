<?php

namespace App\Http\Controllers\Api\Admin;

use Throwable;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockOutController extends Controller
{
    public function index()
    {
        app()->setLocale('en');
        // $this->setLocale(strtolower($country));
        // $columns = $this->getColumns(strtolower('en'));
        if (strtolower('en') === "mm") {
            $stockOuts = StockOut::with('unit', 'product.categoryMm', 'product.subCategoryMm', 'warehouseMm')->orderBy('updated_at', 'desc')->get();
        } else {
            $stockOuts = StockOut::with('unit', 'product.categoryEn', 'product.subCategoryEn', 'warehouseEn')->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $stockOuts], 200);
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
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'StockOut'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'StockOut'])], 400);
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
            $stockOuts = StockOut::findOrFail($id);
            $stockOuts->fill($data->toArray());
            $stockOuts->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'StockOut'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'StockOut'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFail', ['attribute' => 'StockOut'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // $decryptId = decrypt($id);
            $stockOuts = StockOut::findOrFail($id);
            $stockOuts->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'StockOut'])], 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeleted', ['attribute' => 'StockOut'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['warehouse_id'] = $request->warehouse_id;
        $data['product_id'] = $request->product_id;
        $data['original_price'] = $request->original_price;
        $data['quantity'] = $request->quantity;
        $data['sub_total'] = $request->sub_total;
        $data['tax'] = $request->tax;
        $data['grand_total'] = $request->grand_total;
        $data['convert_weight'] = $request->convert_weight;
        $data['unit_id'] = $request->unit_id;
        $data['converted_weight'] = $request->converted_weight;
        $data['scale_id'] = $request->scale_id;
        $data['volume'] = $request->volume;
        $data['line'] = $request->line;
        $data['level'] = $request->level;
        $data['stand'] = $request->stand;
        $data['green'] = $request->green;
        $data['yellow'] = $request->yellow;
        $data['red'] = $request->red;
        $data['remark_en'] = $request->remark_en;
        $data['remark_mm'] = $request->remark_mm;

        return new StockOut($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            'product_id' => 'required',
            'original_price' => 'required|min:0|integer',
            'quantity' => 'required|min:0|integer',
            'sub_total' => 'required|min:0|integer',
            'tax' => 'required|min:0|integer',
            'grand_total' => 'required|min:0|integer',
            'convert_weight' => 'required|min:0|integer',
            'unit_id' => 'required',
            'converted_weight' => 'required|min:0|integer',
            'scale_id' => 'required',
            'volume' => 'required|min:0|integer',
            'line' => 'required',
            'stand' => 'required',
            'level' => 'required',
            'green' => 'required|min:0|integer',
            'yellow' => 'required|min:0|integer',
            'red' => 'required|min:0|integer',
        ], [
            'name_en.required' => 'StockOut name in (English) is required.',
            'name_mm.required_without' => 'StockOut name in (Myanmar) is required.',
            'name_th.required_without' => 'StockOut name in (Thailand) is required.',
            'name_kr.required_without' => 'StockOut name in (Korea) is required.',
            'category_id.required' => 'Category is required',
            'price.required' => 'Price is required',
            'price.integer' => 'Price must be number',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be integer',
            'unit.required' => 'Unit is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'SKU is already taken',
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

    // private function getColumns($country)
    // {
    //     if ($country == 'mm') {
    //         return ['id', 'category_id', 'sub_category_id', 'country_id', 'name_mm', 'sku', 'description_mm', 'remark_mm'];
    //     } else {
    //         return ['id', 'category_id', 'sub_category_id', 'country_id', 'name_en', 'sku', 'description_en', 'remark_en'];
    //     }
    // }
}
