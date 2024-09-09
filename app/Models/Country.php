<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "name_en",
        "name_mm",
        "code",
        "description_en",
        "description_mm",
        "remark_en",
        "remark_mm",
        "is_show",
        "created_by",
        "updated_by",
        "status",
    ];
}
