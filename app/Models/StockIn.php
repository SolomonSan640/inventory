<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "warehouse_id",
        "product_id",
        "original_price",
        "unit_id",
        "currency_id",
        "sub_total",
        "grand_total",
        "tax",
        "quantity",
        "convert_weight",
        "converted_weight",
        "volume",
        "scale_id",
        "green",
        "yellow",
        "red",
        "line",
        "stand",
        "level",
        "remark_en",
        "remark_mm",
        "status",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scale()
    {
        return $this->belongsTo(Scale::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function productName()
    {
        return $this->product()->select('id', 'name_en', 'name_mm', 'category_id', 'sub_category_id', 'sku', 'image');
    }

    public function unitName()
    {
        return $this->unit()->select('id', 'name_en', 'name_mm');
    }

    public function warehouseEn()
    {
        return $this->warehouse()->select('id', 'name_en',  'location');
    }

    public function warehouseMn()
    {
        return $this->warehouse()->select('id', 'name_mm',  'location');
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
