<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ShopController;
use App\Http\Controllers\Api\Admin\UnitController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\ScaleController;
use App\Http\Controllers\Api\B2B\StockInController;
use App\Http\Controllers\Api\B2B\CategoryController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\Admin\CurrencyController;
use App\Http\Controllers\Api\Admin\SupplierController;
use App\Http\Controllers\Api\Admin\WarehouseController;
use App\Http\Controllers\Api\B2B\SubCategoryController;
use App\Http\Controllers\Api\Admin\OrderStatusController;
use App\Http\Controllers\Api\Admin\DamageProductController;
use App\Http\Controllers\Api\Admin\PurchaseOrderController;
use App\Http\Controllers\Api\Admin\WarehouseTransferController;
use App\Http\Controllers\Api\Pos\StockInController as PosStockInController;
use App\Http\Controllers\Api\Pos\CategoryController as PosCategoryController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\StockInController as AdminStockInController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\StockOutController as AdminStockOutController;
use App\Http\Controllers\Api\Pos\SubCategoryController as PosSubCategoryController;
use App\Http\Controllers\Api\Admin\SubCategoryController as AdminSubCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('webhook/shops', [ShopController::class, 'handleWebhook']);

Route::prefix('B2B')->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destory');
    });

    Route::prefix('sub-category')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index'])->name('admin.subCategory.index');
        Route::post('/store', [SubCategoryController::class, 'store'])->name('admin.subCategory.store');
        Route::put('/update/{id}', [SubCategoryController::class, 'update'])->name('admin.subCategory.update');
        Route::delete('/destroy/{id}', [SubCategoryController::class, 'destroy'])->name('admin.subCategory.destory');
    });

    Route::prefix('stock-in')->group(function () {
        Route::get('/', [StockInController::class, 'index'])->name('stockIn.index');
        Route::post('/store', [StockInController::class, 'store'])->name('stockIn.store');
        Route::post('/update/{id}', [StockInController::class, 'update'])->name('stockIn.update');
        Route::delete('/destroy/{id}', [StockInController::class, 'destroy'])->name('stockIn.destory');
    });
});

Route::prefix('POS')->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('/', [PosCategoryController::class, 'index'])->name('admin.category.index');
        Route::post('/store', [PosCategoryController::class, 'store'])->name('admin.category.store');
        Route::put('/update/{id}', [PosCategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/destroy/{id}', [PosCategoryController::class, 'destroy'])->name('admin.category.destory');
    });

    Route::prefix('sub-category')->group(function () {
        Route::get('/', [PosSubCategoryController::class, 'index'])->name('admin.subCategory.index');
        Route::post('/store', [PosSubCategoryController::class, 'store'])->name('admin.subCategory.store');
        Route::put('/update/{id}', [PosSubCategoryController::class, 'update'])->name('admin.subCategory.update');
        Route::delete('/destroy/{id}', [PosSubCategoryController::class, 'destroy'])->name('admin.subCategory.destory');
    });

    Route::prefix('stock-in')->group(function () {
        Route::get('/', [PosStockInController::class, 'index'])->name('stockIn.index');
        Route::post('/store', [PosStockInController::class, 'store'])->name('stockIn.store');
        Route::post('/update/{id}', [PosStockInController::class, 'update'])->name('stockIn.update');
        Route::delete('/destroy/{id}', [PosStockInController::class, 'destroy'])->name('stockIn.destory');
    });
});

// Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
Route::prefix('admin')->group(function () {

    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('admin.supplier.index');
        Route::post('/store', [SupplierController::class, 'store'])->name('admin.supplier.store');
        Route::post('/update/{id}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('/destroy/{id}', [SupplierController::class, 'destroy'])->name('admin.supplier.destory');
    });

    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('admin.unit.index');
        Route::post('/store', [UnitController::class, 'store'])->name('admin.unit.store');
        Route::post('/update/{id}', [UnitController::class, 'update'])->name('admin.unit.update');
        Route::delete('/destroy/{id}', [UnitController::class, 'destroy'])->name('admin.unit.destory');
    });

    Route::prefix('currency')->group(function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('admin.currency.index');
        Route::post('/store', [CurrencyController::class, 'store'])->name('admin.currency.store');
        Route::post('/update/{id}', [CurrencyController::class, 'update'])->name('admin.currency.update');
        Route::delete('/destroy/{id}', [CurrencyController::class, 'destroy'])->name('admin.currency.destory');
    });

    Route::prefix('scale')->group(function () {
        Route::get('/', [ScaleController::class, 'index'])->name('admin.scale.index');
        Route::post('/store', [ScaleController::class, 'store'])->name('admin.scale.store');
        Route::post('/update/{id}', [ScaleController::class, 'update'])->name('admin.scale.update');
        Route::delete('/destroy/{id}', [ScaleController::class, 'destroy'])->name('admin.scale.destory');
    });

    Route::prefix('warehouse')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('admin.warehouse.index');
        Route::post('/store', [WarehouseController::class, 'store'])->name('admin.warehouse.store');
        Route::post('/update/{id}', [WarehouseController::class, 'update'])->name('admin.warehouse.update');
        Route::delete('/destroy/{id}', [WarehouseController::class, 'destroy'])->name('admin.warehouse.destory');
    });

    Route::prefix('scale')->group(function () {
        Route::get('/', [ScaleController::class, 'index'])->name('admin.scale.index');
        Route::post('/store', [ScaleController::class, 'store'])->name('admin.scale.store');
        Route::post('/update/{id}', [ScaleController::class, 'update'])->name('admin.scale.update');
        Route::delete('/destroy/{id}', [ScaleController::class, 'destroy'])->name('admin.scale.destory');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('admin.payment.index');
        Route::post('/store', [PaymentController::class, 'store'])->name('admin.payment.store');
        Route::post('/update/{id}', [PaymentController::class, 'update'])->name('admin.payment.update');
        Route::delete('/destroy/{id}', [PaymentController::class, 'destroy'])->name('admin.payment.destory');
    });

    Route::prefix('country')->group(function () {
        Route::get('/', [CountryController::class, 'index'])->name('admin.country.index');
        Route::post('/store', [CountryController::class, 'store'])->name('admin.country.store');
        Route::post('/update/{id}', [CountryController::class, 'update'])->name('admin.country.update');
        Route::delete('/destroy/{id}', [CountryController::class, 'destroy'])->name('admin.country.destory');
    });

    Route::prefix('order-status')->group(function () {
        Route::get('/', [OrderStatusController::class, 'index'])->name('admin.orderStatus.index');
        Route::post('/store', [OrderStatusController::class, 'store'])->name('admin.orderStatus.store');
        Route::post('/update/{id}', [OrderStatusController::class, 'update'])->name('admin.orderStatus.update');
        Route::delete('/destroy/{id}', [OrderStatusController::class, 'destroy'])->name('admin.orderStatus.destory');
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.category.index');
        Route::post('/store', [AdminCategoryController::class, 'store'])->name('admin.category.store');
        Route::post('/update/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/destroy/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.category.destory');
    });

    Route::prefix('sub-category')->group(function () {
        Route::get('/', [AdminSubCategoryController::class, 'index'])->name('admin.subCategory.index');
        Route::post('/store', [AdminSubCategoryController::class, 'store'])->name('admin.subCategory.store');
        Route::post('/update/{id}', [AdminSubCategoryController::class, 'update'])->name('admin.subCategory.update');
        Route::delete('/destroy/{id}', [AdminSubCategoryController::class, 'destroy'])->name('admin.subCategory.destory');
    });

    Route::prefix('product')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.product.index');
        Route::post('/store', [AdminProductController::class, 'store'])->name('admin.product.store');
        Route::post('/update/{id}', [AdminProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/destroy/{id}', [AdminProductController::class, 'destroy'])->name('admin.product.destory');
    });

    Route::prefix('stock-in')->group(function () {
        Route::get('/', [AdminStockInController::class, 'index'])->name('admin.stockIn.index');
        Route::post('/store', [AdminStockInController::class, 'store'])->name('admin.stockIn.store');
        Route::post('/update/{id}', [AdminStockInController::class, 'update'])->name('admin.stockIn.update');
        Route::delete('/destroy/{id}', [AdminStockInController::class, 'destroy'])->name('admin.stockIn.destory');
    });

    Route::prefix('stock-out')->group(function () {
        Route::get('/', [AdminStockOutController::class, 'index'])->name('admin.stockOut.index');
        Route::post('/store', [AdminStockOutController::class, 'store'])->name('admin.stockOut.store');
        Route::post('/update/{id}', [AdminStockOutController::class, 'update'])->name('admin.stockOut.update');
        Route::delete('/destroy/{id}', [AdminStockOutController::class, 'destroy'])->name('admin.stockOut.destory');
    });

    Route::prefix('purchase-order')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('admin.purchaseOrder.index');
        Route::post('/store', [PurchaseOrderController::class, 'store'])->name('admin.purchaseOrder.store');
        Route::post('/update/{id}', [PurchaseOrderController::class, 'update'])->name('admin.purchaseOrder.update');
        Route::delete('/destroy/{id}', [PurchaseOrderController::class, 'destroy'])->name('admin.purchaseOrder.destory');
    });

    Route::prefix('warehouse-transfer')->group(function () {
        Route::get('/', [WarehouseTransferController::class, 'index'])->name('admin.warehouseTransfer.index');
        Route::post('/store', [WarehouseTransferController::class, 'store'])->name('admin.warehouseTransfer.store');
        Route::post('/update/{id}', [WarehouseTransferController::class, 'update'])->name('admin.warehouseTransfer.update');
        Route::delete('/destroy/{id}', [WarehouseTransferController::class, 'destroy'])->name('admin.warehouseTransfer.destory');
    });

    Route::prefix('damage-product')->group(function () {
        Route::get('/', [DamageProductController::class, 'index'])->name('admin.damageProduct.index');
        Route::post('/store', [DamageProductController::class, 'store'])->name('admin.damageProduct.store');
        Route::post('/update/{id}', [DamageProductController::class, 'update'])->name('admin.damageProduct.update');
        Route::delete('/destroy/{id}', [DamageProductController::class, 'destroy'])->name('admin.damageProduct.destory');
    });
});
// });
