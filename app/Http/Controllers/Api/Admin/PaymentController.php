<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class PaymentController extends Controller
{
    public function index()
    {
        app()->setLocale('en');
        $columns = $this->getColumns(strtolower('en'));
        $payments = Payment::select($columns)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 200, 'message' => __('success.dataRetrieved'), 'data' => $payments], 200);
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
            return response()->json(['status' => 201, 'message' => __('success.dataCreated', ['attribute' => 'Payment'])], 201);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataCreatedFailed', ['attribute' => 'Payment'])], 400);
        }
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        // $this->setLocale(strtolower($request->Payment));
        app()->setLocale('en');
        $validationResult = $this->validateCreateData($request, $id);
        if ($validationResult !== null) {
            return $validationResult;
        }
        try {
            $data = $this->getCreateData($request);
            $payments = Payment::findOrFail($id);
            $payments->fill($data->toArray());
            $payments->update();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataUpdated', ['attribute' => 'Payment'])], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Payment'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataUpdatedFailed', ['attribute' => 'Payment'])], 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        app()->setLocale('en');
        try {
            $payments = Payment::findOrFail($id);
            $payments->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => __('success.dataDeleted', ['attribute' => 'Payment'])], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => __('error.dataNotFound', ['attribute' => 'Payment'])], 404);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return response()->json(['status' => 400, 'message' => __('error.dataDeletedFailed', ['attribute' => 'Payment'])], 400);
        }
    }

    protected function getCreateData($request)
    {
        $data = [];

        $data['payment_type'] = $request->payment_type;
        $data['is_cash'] = $request->is_cash;

        return new Payment($data);
    }

    protected function validateCreateData($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => [
                'required_without:is_cash',
                Rule::unique('payments')->whereNull('deleted_at')->ignore($id),
            ],
            'is_cash' => 'required_without:payment_type',
        ], [
            'payment_type.unique' => __('validation.dataNameUnique', ['attribute' => 'Payment']),
            'payment_type.required' => __('validation.dataNameRequire', ['attribute' => 'Payment']),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return null;
    }

    private function setLocale($Payment)
    {
        $supportedLocales = ['en', 'mm'];
        if (in_array($Payment, $supportedLocales)) {
            app()->setLocale($Payment);
        } else {
            app()->setLocale('en');
        }
    }

    private function getColumns()
    {
        return ['id', 'payment_type', 'is_cash'];
    }
}
