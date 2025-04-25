<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Loamy'],
            ['name' => 'Sandy'],
         
  
        ];

        foreach ($array as $item) {
            \App\Models\Type::create($item);
        }
    }
}
