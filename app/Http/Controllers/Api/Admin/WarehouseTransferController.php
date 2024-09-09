<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseTransfer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        // $this->setLocale(strtolower($country));
        app()->setLocale('en');
        $columns = $this->getColumns('en');
        if (strtolower('en') === "mm") {
            $transfers = WarehouseTransfer::with('fromWarehouseMm', 'toWarehouseMm', 'productMm')->select($columns)->orderBy('updated_at', 'desc')->get();
        } else {
            $transfers = WarehouseTransfer::with('fromWarehouseEn', 'toWarehouseEn', 'stockOut.productEn')->select($columns)->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $transfers], 200);
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
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Warehouse Transfer'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Warehouse Transfer'])], 400);
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
            $transfers = WarehouseTransfer::findOrFail($id);
            $transfers->fill($data->toArray());
            $transfers->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Warehouse Transfer'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Warehouse Transfer'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFail', ['attribute' => 'Warehouse Transfer'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // $decryptId = decrypt($id);
            $transfers = WarehouseTransfer::findOrFail($id);
            $transfers->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Warehouse Transfer'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Warehouse Transfer'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFail', ['attribute' => 'Warehouse Transfer'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['from_warehouse_id'] = $request->from_warehouse_id;
        $data['to_warehouse_id'] = $request->to_warehouse_id;
        $data['product_id'] = $request->product_id;
        $data['stock_out_id'] = $request->stock_out_id;
        $data['quantity'] = $request->quantity;

        return new WarehouseTransfer($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'from_warehouse_id' => [
                'required',
            ],
            'to_warehouse_id' => [
                'required',
            ],
            // 'product_id' => [
            //     'required',
            // ],
            'stock_out_id' => [
                'required',
            ],
            'quantity' => [
                'required',
                'min:0',
                'numeric',
            ],
        ], [
            'from_warehouse_id.required' => __('validation.dataSelect', ['attribute' => 'From Warehouse']),
            'to_warehouse_id.required' => __('validation.dataSelect', ['attribute' => 'To Warehouse']),
            'product_id.required' => __('validation.dataSelect', ['attribute' => 'Product']),
            'stock_out_id.required' => __('validation.dataSelect', ['attribute' => 'Stock Out']),
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
        return ['id', 'from_warehouse_id', 'to_warehouse_id', 'stock_out_id', 'quantity'];
    }
}
