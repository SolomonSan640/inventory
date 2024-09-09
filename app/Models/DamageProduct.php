<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamageProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "stock_in_id",
        "stock_out_id",
        "quantity",
        "level",
        "status",
    ];

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class)->select('id', 'product_id');
    }

    public function stockOut()
    {
        return $this->belongsTo(StockOut::class)->select('id', 'product_id');
    }
}
