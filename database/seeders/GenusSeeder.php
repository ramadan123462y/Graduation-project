<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Mentha'],

            ['name' => 'Solanum'],

            ['name' => 'Cucumis'],

            ['name' => 'Daucus'],
          
        ];

        foreach ($array as $item) {
            \App\Models\Genus::create($item);
        }
    }
}
