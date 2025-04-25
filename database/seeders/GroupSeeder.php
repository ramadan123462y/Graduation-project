<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $array = [
            ['name' => 'Magnoliopsida'],
        
        ];

        foreach ($array as $item) {
            \App\Models\Group::create($item);
        }
    }
}
