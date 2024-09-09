<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Admin 1', 'email' => 'admin@freshmoe.com', 'is_admin' => 1, 'password' => Hash::make('admin')],
            ['name' => 'Tester', 'email' => 'testing@freshmoe.com', 'is_admin' => 0, 'password' => Hash::make('admin')],
        ];
        foreach ($data as $value) {
            User::updateOrCreate($value);
        }
    }
}
