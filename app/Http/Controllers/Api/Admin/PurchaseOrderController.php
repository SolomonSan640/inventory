<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        app()->setLocale('en');
        // $this->setLocale(strtolower($country));
        $columns = $this->getColumns();
        if (strtolower('en') === "mm") {
            $purchaseOrders = PurchaseOrder::with('supplier', 'productMm', 'orderStatusMm', 'payment', 'currency')->select($columns)->orderBy('updated_at', 'desc')->get();
        } else {
            $purchaseOrders = PurchaseOrder::with('supplier', 'productEn', 'orderStatusEn', 'payment', 'currency')->select($columns)->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $purchaseOrders], 200);
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
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Purchase Order'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Purchase Order'])], 400);
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
            $purchaseOrders = PurchaseOrder::findOrFail($id);
            $purchaseOrders->fill($data->toArray());
            $purchaseOrders->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Purchase Order'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Purchase Order'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFail', ['attribute' => 'Purchase Order'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // $decryptId = decrypt($id);
            $purchaseOrders = PurchaseOrder::findOrFail($id);
            $purchaseOrders->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Purchase Order'])], 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeleted', ['attribute' => 'Purchase Order'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['supplier_id'] = $request->supplier_id;
        $data['product_id'] = $request->product_id;

        $data['order_status_id'] = $request->order_status_id;
        $data['payment_id'] = $request->payment_id;

        $data['amount'] = $request->amount;
        $data['currency_id'] = $request->currency_id;

        $data['tax'] = $request->tax;
        $data['grand_total'] = $request->grand_total;

        return new PurchaseOrder($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'product_id' => 'required',
            'order_status_id' => 'required',
            'payment_id'=> 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'tax' => 'required',
            'grand_total' => 'required',
        ], [
            'name_en.required' => 'PurchaseOrder name in (English) is required.',
            'name_mm.required_without' => 'PurchaseOrder name in (Myanmar) is required.',
            'name_th.required_without' => 'PurchaseOrder name in (Thailand) is required.',
            'name_kr.required_without' => 'PurchaseOrder name in (Korea) is required.',
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

    private function getColumns()
    {
        return ['id', 'supplier_id', 'product_id', 'order_status_id', 'payment_id', 'amount', 'currency_id', 'tax', 'grand_total'];
    }
}
