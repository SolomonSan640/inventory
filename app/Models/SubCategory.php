<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "category_id",
        "name_en",
        "name_mm",
        "remark_en",
        "remark_mm",
        "description_en",
        "description_mm",
        "status",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryName()
    {
        return $this->category()->select('id', 'name_en', 'name_mm');
    }

    public function categoryEn()
    {
        return $this->category()->select('id', 'name_en');
    }

    public function categoryMm()
    {
        return $this->category()->select('id', 'name_mm');
    }

    public function image()
    {
        return $this->hasMany(GeneralImage::class);
    }

    public function subCategoryImage()
    {
        return $this->image()->select('id', 'sub_category_id', 'file_path');
    }
}
