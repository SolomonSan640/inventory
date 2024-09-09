<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name_en' => 'Fruit', 'name_mm' => 'သစ်သီး'],
            ['name_en' => 'Vegetables', 'name_mm' => 'ဟင်းသီးဟင်းရွက်'],
            ['name_en' => 'Ready to Eat', 'name_mm' => 'အသင့်စား'],
            ['name_en' => 'Ready to Cook', 'name_mm' => 'အသင့်ချက်'],
            ['name_en' => 'Juices', 'name_mm' => 'ဖျော်ရည်'],
        ];
        foreach ($data as $value) {
            Category::updateOrCreate($value);
        }
    }
}
