<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "category_id",
        "sub_category_id",
        "product_id",
        "user_id",
        "supplier_id",
        "warehouse_id",
        "file_path",
        "status",
    ];
}
