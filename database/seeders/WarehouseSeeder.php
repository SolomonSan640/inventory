<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name_en' => 'FreshMoe 1', 'name_mm' => 'FreshMoe 1'],
            ['name_en' => 'FreshMoe 2', 'name_mm' => 'FreshMoe 2'],
        ];
        foreach ($data as $value) {
            Warehouse::updateOrCreate($value);
        }
    }
}
