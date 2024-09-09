<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "supplier_id",
        "product_id",
        "payment_id",
        "currency_id",
        "order_status_id",
        "amount",
        "tax",
        "grand_total",
        "order_status",
        "status",
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->select('id', 'name', 'phone', 'address');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class)->select('id', 'payment_type');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class)->select('id', 'name');
    }

    public function productEn()
    {
        return $this->product()->select('id', 'name_en');
    }

    public function productMm()
    {
        return $this->product()->select('id', 'name_mm');
    }

    public function orderStatusEn()
    {
        return $this->orderStatus()->select('id', 'name_en');
    }

    public function orderStatusMm()
    {
        return $this->orderStatus()->select('id', 'name_mm');
    }
}
