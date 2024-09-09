<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name_en' => 'Kg', 'name_mm' => 'ကီလိုဂရမ်'],
            ['name_en' => 'Gram', 'name_mm' => 'ဂရမ်'],
        ];
        foreach ($data as $value) {
            Unit::updateOrCreate($value);
        }
    }
}
