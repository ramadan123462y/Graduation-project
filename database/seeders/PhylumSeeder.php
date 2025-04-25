<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhylumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Tracheophyta'],

            ['name' => 'Angiosperms'],
            
        ];

        foreach ($array as $item) {
            \App\Models\Phylum::create($item);
        }
    }
}
