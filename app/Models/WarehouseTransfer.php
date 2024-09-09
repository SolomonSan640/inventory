<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "stock_in_id",
        "stock_out_id",
        "quantity",
        "product_id",
        "from_warehouse_id",
        "to_warehouse_id",
        "status",
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    // Define the relationship for the to_warehouse
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function stockOut()
    {
        return $this->belongsTo(StockOut::class)->select('id', 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromWarehouseEn()
    {
        return $this->fromWarehouse()->select('id', 'name_en');
    }

    public function fromWarehouseMm()
    {
        return $this->fromWarehouse()->select('id', 'name_mm');
    }

    public function toWarehouseEn()
    {
        return $this->toWarehouse()->select('id', 'name_en');
    }

    public function toWarehouseMm()
    {
        return $this->toWarehouse()->select('id', 'name_mm');
    }

    public function productEn()
    {
        return $this->product()->select('id', 'name_en');
    }

    public function productMm()
    {
        return $this->product()->select('id', 'name_mm');
    }
}
