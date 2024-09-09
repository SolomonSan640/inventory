<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Banana', 'name_mm' => 'ငှက်ပျောသီး', 'sku' => '2345678910'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Orange', 'name_mm' => 'လိမ္မော်သီး', 'sku' => '890251910'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Mango', 'name_mm' => 'သရက်သီး', 'sku' => '454545'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Strawberry', 'name_mm' => 'စတော်ဗယ်ရီ', 'sku' => '12345678910'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Dragon Fruits', 'name_mm' => 'နဂါးမောက်သီး', 'sku' => '2323232323'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Avocado', 'name_mm' => 'ထောပတ်သီး', 'sku' => '89489491651'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Coriander', 'name_mm' => 'နံနံပင်', 'sku' => '898989'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Pear', 'name_mm' => 'သစ်တော်သီး', 'sku' => '11133344'],
            ['country_id' => '20', 'category_id' => '1', 'name_en' => 'Coconut', 'name_mm' => 'အုန်းသီး', 'sku' => '787878'],
            ['country_id' => '20', 'category_id' => '2', 'name_en' => 'Vege Apple', 'name_mm' => 'ပန်းသီး Vege', 'sku' => '11112121'],
        ];
        foreach ($data as $value) {
            Product::updateOrCreate($value);
        }
    }
}
