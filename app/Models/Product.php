<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "category_id",
        "sub_category_id",
        "country_id",
        "name_en",
        "name_mm",
        "sku",
        "new_item",
        "seasonal",
        "organic",
        "recommended",
        "description_en",
        "description_mm",
        "remark_en",
        "remark_mm",
        'image',
        "status",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function image()
    {
        return $this->hasMany(GeneralImage::class);
    }

    public function productImage()
    {
        return $this->image()->select('id', 'product_id', 'file_path');
    }

    public function categoryEn()
    {
        return $this->category()->select('id', 'name_en');
    }

    public function categoryMm()
    {
        return $this->category()->select('id', 'name_mm');
    }

    public function subCategoryEn()
    {
        return $this->category()->select('id', 'name_en');
    }

    public function subCategoryMm()
    {
        return $this->category()->select('id', 'name_mm');
    }

    public function countryEn()
    {
        return $this->country()->select('id', 'name_en');
    }

    public function countryMm()
    {
        return $this->country()->select('id', 'name_mm');
    }

    public function categoryName()
    {
        return $this->category()->select('id', 'name_en', 'name_mm', 'description_en', 'remark_en');
    }

    public function subCategoryName()
    {
        return $this->category()->select('id', 'name_en', 'name_mm', 'description_en', 'remark_en');
    }

}
