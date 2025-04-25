<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            ['name' => 'Lamiaceae'],
            ['name' => 'Asteraceae'],
            ['name' => 'Solanaceae'],

            ['name' => 'Cucurbitaceae'],

            ['name' => 'Apiaceae'],
        ];

        foreach ($array as $item) {
            \App\Models\Family::create($item);
        }
    }
}
