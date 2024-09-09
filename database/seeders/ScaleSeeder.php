<?php

namespace Database\Seeders;

use App\Models\Scale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name_en' => '1 Pc/s', 'name_mm' => '1 Pc/s'],
            ['name_en' => 'Basket', 'name_mm' => 'Basket'],
        ];
        foreach ($data as $value) {
            Scale::updateOrCreate($value);
        }
    }
}
