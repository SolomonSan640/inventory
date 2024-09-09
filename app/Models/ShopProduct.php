<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "shop_id",
        "warehouse_id",
        "product_id",
        "name",
        "status",
        "created_by",
        "updated_by",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
