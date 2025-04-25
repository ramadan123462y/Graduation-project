<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlantTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $array = [
        ['plant_id' => 1, 'tag_id' => 1],
        ['plant_id' => 1, 'tag_id' => 2],
        ['plant_id' => 1, 'tag_id' => 3],
        ['plant_id' => 1, 'tag_id' => 4],
        ['plant_id' => 1, 'tag_id' => 5],
        ['plant_id' => 1, 'tag_id' => 6],



        ['plant_id' => 2, 'tag_id' => 1],
        ['plant_id' => 2, 'tag_id' => 8],
        ['plant_id' => 2, 'tag_id' => 9],
        ['plant_id' => 2, 'tag_id' => 10],
        ['plant_id' => 2, 'tag_id' => 11],
        ['plant_id' => 2, 'tag_id' => 12],


        ['plant_id' => 3, 'tag_id' => 1],
        ['plant_id' => 3, 'tag_id' => 11],
        ['plant_id' => 3, 'tag_id' => 12],
        ['plant_id' => 3, 'tag_id' => 13],


        ['plant_id' => 4, 'tag_id' => 1],
        ['plant_id' => 4, 'tag_id' => 10],
        ['plant_id' => 4, 'tag_id' => 11],
        ['plant_id' => 4, 'tag_id' => 12],


        ['plant_id' => 5, 'tag_id' => 1],
        ['plant_id' => 5, 'tag_id' => 8],
        ['plant_id' => 5, 'tag_id' => 11],
        ['plant_id' => 5, 'tag_id' => 12],
    ];

    DB::table('plant_tag')->insert($array);
}
}
