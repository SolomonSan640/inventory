<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "name_en",
        "name_mm",
        "remark_en",
        "remark_mm",
        "description_en",
        "description_mm",
        "status",
    ];

    public function image()
    {
        return $this->hasMany(GeneralImage::class);
    }

    public function categoryImage()
    {
        return $this->image()->select('id', 'category_id', 'file_path');
    }
}
