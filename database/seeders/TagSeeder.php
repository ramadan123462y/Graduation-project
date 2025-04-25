<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Edible'],
            ['name' => 'Flowering'],
            ['name' => 'Fruit-bearing'],
            ['name' => 'Easy'],
            ['name' => 'Toxic'],
            ['name' => 'Tall'],
            ['name' => 'Fruit'],
            ['name' => 'Vegetable'],
            ['name' => 'Garden Plant'],
            ['name' => 'Annual'],
            ['name' => 'Root Vegetable'],
            ['name' => 'Sweet'],
            ['name' => 'Garden Plant'],
        ];

        foreach ($array as $item) {
            \App\Models\Tag::create($item);
        }
    }
}
